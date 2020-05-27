<?php

namespace Tests\Feature\Auth\Payments;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Managers\BalanceManager;
use App\TournamentKings\Entities\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\TournamentKings\Entities\Models\UserWithdrawal;
use App\TournamentKings\Entities\Models\WithdrawalType;
use App\Tournamentkings\Auth\Notifications\VerifyWithdrawalEmail;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public function testAuthOnly()
    {
        $this->seed();

        $this->get(route('balance.show'))
            ->assertRedirect(route('login'));
    }

    public function testCanSeeBalance()
    {
        $this->seed();

        $user = User::role('Admin')->first();

        $user->transactions()->save(

            new Transaction([
                'transaction_id'        => 'abc',
                'amount'                => 5,
                'transaction_type_name' => 'square',
            ])
        );

        $this->actingAs($user)
            ->get(route('home'))
            ->assertSee(5);
    }

    public function testCanSeeDeposit()
    {
        $this->seed();

        $this->actingAs(User::role('Admin')->first())
            ->get(route('balance.show'))
            ->assertSee('Deposit');
    }

    public function testCanMakeDeposit()
    {
        $this->seed();

        $amount = '100.00';

        $this->actingAs(User::role('Admin')->first())
            ->post(route('balance.deposit.post', [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ]))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');
    }

    public function testAmountRequired()
    {
        $this->seed();

        $this->actingAs(User::role('Admin')->first())
            ->post(route('balance.deposit.post', [
                'nonce' => 'fake-card-nonce-ok',
            ]))
            ->assertSessionHasErrors(['amount']);
    }

    /*
     * See https://docs.connect.squareup.com/basics/handling-errors for more
     * on possible error conditions.
     *
     * https://docs.connect.squareup.com/api/connect/v2#type-errorcode has all the error codes.
     */
    public function testCanFailDeposit()
    {
        $this->seed();

        $amount = '1.00';

        $this->actingAs(User::role('Admin')->first())
            ->post(route('balance.deposit.post', [
                'nonce'  => 'fake-card-nonce-rejected-cvv',
                'amount' => $amount,
            ]))
            ->assertSessionHasErrors(['VERIFY_CVV_FAILURE'])
            ->assertSessionHas('amount', $amount);
    }

    public function testVerifyPayments()
    {
        $this->seed();

        $amounts = collect([
            '17',
            '0.40',
            '12.33',
        ]);
        $amountSum = $amounts->reduce(function ($acc, $cur) {
            return $acc + intval(floatval($cur) * 100);
        }, 0);

        $users = User::role('Admin')->limit(3)->get();

        $amounts->each(function ($amount) use ($users, $amountSum) {
            $users->each(function ($user) use ($amount) {
                $this->actingAs($user)
                    ->post(route('balance.deposit.post', [
                        'nonce'  => 'fake-card-nonce-ok',
                        'amount' => $amount,
                    ]))
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('success');

                $this->assertTrue($user->transactions()->where('amount', floatval($amount))->get()->isNotEmpty());
            });
        });

        $users->each(function ($user) use ($amountSum) {
            $this->assertEquals($user->balance->balance, $amountSum / 100);
        });
    }

    public function testCanWithdraw()
    {
        $this->seed();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount       = '1.00';
        $phone_number = '1234567890';
        $response     = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::PAYPAL,
                'email'                => '',
                'phone_number'         => $phone_number,
            ]);
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user = User::find($user->id);
        $this->assertEquals($user->withdrawals->first()->amount, $amount);
        $this->assertEquals($user->withdrawals->first()->phone_number, $phone_number);
    }

    public function testOptionalPhone()
    {
        $this->seed();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount   = '1.00';
        $response = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::PAYPAL,
                'email'                => '',
                'phone_number'         => '',
            ]);
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user = User::find($user->id);

        $this->assertEquals($user->withdrawals->first()->amount, $amount);
    }

    public function testSquareCashNeedsPhone()
    {
        $this->seed();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount   = '1.00';
        $response = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::SQUARE_CASH,
                'email'                => '',
                'phone_number'         => '',
            ]);
        $response->assertSessionHasErrors('phone_number');
    }

    public function testNeedsAmountUnderBalance()
    {
        $this->seed();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount   = '101.00';
        $response = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::PAYPAL,
                'email'                => '',
                'phone_number'         => '',
            ])->assertSessionHasErrors('amount');
        $amountErrors = $response->getSession()->get('errors')->messages()['amount'];
        $this->assertEquals(__('balance.insufficient-funds'), $amountErrors[0]);
    }

    public function testWithdrawalDefaultEmail()
    {
        $this->seed();

        Notification::fake();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount       = '1.00';
        $phone_number = '1234567890';
        $response     = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::PAYPAL,
                'email'                => '',
                'phone_number'         => $phone_number,
            ]);
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user = User::find($user->id);

        $userWithdrawal = $user->withdrawals->first();

        $this->assertNotNull($userWithdrawal->email_verified_at);
        $this->assertEquals($user->email, $userWithdrawal->email);

        Notification::assertNothingSent();
    }

    public function testWithdrawalEmailVerified()
    {
        $this->seed();

        $firstUserWithdrawal = factory(UserWithdrawal::class)->states('email_verified')->create();
        $email               = $firstUserWithdrawal->email;

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount               = '1.00';
        $phone_number         = '1234567890';
        $withdrawal_type_name = WithdrawalType::PAYPAL;
        $response             = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), compact(
                'amount',
                'withdrawal_type_name',
                'email',
                'phone_number'
            ));
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user = User::find($user->id);

        $user->withdrawals->each(function ($withdrawal) {
            $this->assertNotNull($withdrawal->email_verified_at);
        });
    }

    public function testWithdrawalEmailVerificationSent()
    {
        $this->seed();
        Notification::fake();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $amount                 = '1.00';
        $phone_number           = '1234567890';
        $withdrawal_type_name   = WithdrawalType::PAYPAL;
        $email                  = $this->faker->email;
        $response               = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), compact(
                'amount',
                'withdrawal_type_name',
                'email',
                'phone_number'
            ));
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user = User::find($user->id);

        $userWithdrawal = $user->withdrawals->first();

        Notification::assertSentTo($userWithdrawal, VerifyWithdrawalEmail::class);
    }

    public function testCanRefundUser()
    {
        $this->seed();

        $amount = '100.00';
        $user   = User::role('Admin')->first();

        $this->actingAs($user)
            ->post(route('balance.deposit.post'), [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');
        $user            = User::find($user->id);
        $originalBalance = $user->balance->balance;

        $amount       = '1.00';
        $phone_number = '1234567890';
        $response     = $this->actingAs($user)
            ->post(route('balance.withdrawal.post'), [
                'amount'               => $amount,
                'withdrawal_type_name' => WithdrawalType::PAYPAL,
                'email'                => '',
                'phone_number'         => $phone_number,
            ]);
        $response->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $user           = User::find($user->id);
        $userWithdrawal = $user->withdrawals->first();

        BalanceManager::completeUserRefund($userWithdrawal);
        $user = User::find($user->id);

        $this->assertEquals($originalBalance, $user->balance->balance);
    }
}
