<?php

namespace Tests\Browser\Auth\Payments;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Tournamentkings\Entities\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageTest extends DuskTestCase
{
    use DatabaseMigrations;

    const PAYMENT_DATA = [
        'card'  => '4111 1111 1111 1111',
        'exp'   => '04/99',
        'cvv'   => '111',
        'postal'=> '94103',
//        'wrong-cvv' => '911',
//        'wrong-postal' => '99999',
//        'wrong-amount' => '403'
    ];

    private function submitPayment(Browser $browser, string $amount)
    {
        $browser->loginAs(User::role('Admin')->first())
            ->visit(route('balance.deposit.create'))
            ->assertSee('Deposit')
            ->waitFor('iframe#sq-card-number');

        $browser->driver->switchTo()->frame('sq-card-number');
        $browser->keys('> form > input', self::PAYMENT_DATA['card']);
        $browser->driver->switchTo()->defaultContent();

        $browser->driver->switchTo()->frame('sq-expiration-date');
        $browser->keys('> form > input', self::PAYMENT_DATA['exp']);
        $browser->driver->switchTo()->defaultContent();

        $browser->driver->switchTo()->frame('sq-cvv');
        $browser->keys('> form > input', self::PAYMENT_DATA['cvv']);
        $browser->driver->switchTo()->defaultContent();

        $browser->driver->switchTo()->frame('sq-postal-code');
        $browser->keys('> form > input', self::PAYMENT_DATA['postal']);
        $browser->driver->switchTo()->defaultContent();

        $browser->keys('#amount', $amount);
    }

    /*
     * Example test data for the sandbox: https://docs.connect.squareup.com/testing/test-values?q=sandbox
     */
    public function testCanMakeDeposit()
    {
        $this->seed();

        $this->browse(function (Browser $browser) {
            $this->submitPayment($browser, '100');

            $browser->click('#sq-creditcard')
                    ->waitForReload()
                    ->assertSee(__('balance.deposit.success'));
        });
    }
}
