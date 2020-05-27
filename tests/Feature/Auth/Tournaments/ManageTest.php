<?php

namespace Tests\Feature\Auth\Tournaments;

use Tests\TestCase;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Managers\RoundManager;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Managers\TournamentManager;
use App\Tournamentkings\Entities\Models\EntryFeeType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthOnly()
    {
        $this->seed();

        $this->get(route('tournaments.index'))
            ->assertRedirect(route('login'));
    }

    public function testCanCreateFreeTournament()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament      = factory(Tournament::class)->make();
        $tournamentArray = $tournament->toArray();

        $this->actingAs($user)->post(route('tournaments.store'), $tournamentArray)
            ->assertSessionHas('tk_message_status', __('tournaments.created', ['name' => $tournament->name]));
        /*
        for some reason, the players relation is being included in the tournament dataset,
        and when serialized as an Array returns []. The intersect method on the
        collection is trying to convert that into a string to compare, throwing
        an array to string conversion exception. Un-setting 'players' on
        both arrays resolves the issue.
        */
        $DBTournament = Tournament::first()->toArray();
        unset($tournamentArray['players']);
        unset($DBTournament['players']);

        $this->assertEquals(
            collect($tournamentArray)
                        ->intersect($DBTournament)
                        ->toArray(),
            $tournamentArray
        );
    }

    public function testCanCreateFlatFee()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament      = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->make();
        $tournamentArray = $tournament->toArray();

        $this->actingAs($user)->post(route('tournaments.store'), $tournamentArray)
            ->assertSessionHas('tk_message_status', __('tournaments.created', ['name' => $tournament->name]));

        $factoryFeeData = collect($tournamentArray)->only([
            'entry_fee_type_name',
            'entry_fee',
        ]);

        $createdData = collect(Tournament::first()->toArray())->only([
            'entry_fee_type_name',
            'entry_fee',
        ]);

        $this->assertEquals($factoryFeeData, $createdData);
    }

    public function testFlatFeeNeedsAmount()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament      = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->make();
        $tournamentArray = $tournament->toArray();
        $tournamentArray = collect($tournamentArray)->forget('entry_fee');

        $this->actingAs($user)->post(route('tournaments.store'), $tournamentArray->toArray())
            ->assertSessionHasErrors('entry_fee');
    }

    public function testFlatFeeCanRegister()
    {
        $this->seed();

        $user       = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->create();

        $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
        $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

        $amount = $tournamentWithRoundsAndMatches->entry_fee;
        $nonce  = 'fake-card-nonce-ok';

        $this->actingAs($user)
            ->post(route('balance.deposit.post', compact('amount', 'nonce')))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->actingAs($user)->post(route('register.post', compact('tournamentWithRoundsAndMatches', 'amount')))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');
    }

    public function testCanCreateTargetPot()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament      = factory(Tournament::class)->states(EntryFeeType::TARGET_POT)->make();
        $tournamentArray = $tournament->toArray();

        $this->actingAs($user)->post(route('tournaments.store'), $tournamentArray)
            ->assertSessionHas('tk_message_status', __('tournaments.created', ['name' => $tournament->name]));

        $factoryFeeData = collect($tournamentArray)->only([
            'entry_fee_type_name',
            'target_pot',
        ]);

        $createdData = collect(Tournament::first()->toArray())->only([
            'entry_fee_type_name',
            'target_pot',
        ]);

        $this->assertEquals($factoryFeeData, $createdData);
    }

    public function testTargetPotNeedsAmount()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament      = factory(Tournament::class)->states(EntryFeeType::TARGET_POT)->make();
        $tournamentArray = $tournament->toArray();
        $tournamentArray = collect($tournamentArray)->forget('target_pot');

        $this->actingAs($user)->post(route('tournaments.store'), $tournamentArray->toArray())
            ->assertSessionHasErrors('target_pot');
    }

    public function testEntryFeeBeforeRegistration()
    {
        $this->seed();

        $user = User::role('Admin')->first();
        session()->put('auth_user_player_id', $user->player->id);

        $tournament = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->create();

        $this->actingAs($user)->post(route('register.post', compact('tournament')))
            ->assertSessionHasErrors('no-balance');
    }

    public function testRemainingFee()
    {
        $this->seed();

        $users       = User::role('Admin')->limit(3)->get();
        $tournaments = factory(Tournament::class, 5)->states(EntryFeeType::FLAT_FEE)->create();

        $users->each(function ($user) use ($tournaments) {
            $amount = '0.50';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', [
                    'nonce'  => 'fake-card-nonce-ok',
                    'amount' => $amount,
                ]))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $tournaments->each(function ($tournament) use ($user) {
                $amount = number_format(5, 2);
                $response = $this->actingAs($user)
                    ->post(route('register.post', compact('tournament')))
                    ->assertSessionHasErrors('insufficient-funds');
            });
        });
    }

    public function testRemainingTargetPot()
    {
        $this->seed();

        $users       = User::role('Admin')->limit(3)->get();
        $tournaments = factory(Tournament::class, 5)->states(EntryFeeType::TARGET_POT)->create();

        $users->each(function ($user) use ($tournaments) {
            $originalPlayerDeposits = [];

            $amount = '1.85';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', [
                    'nonce'  => 'fake-card-nonce-ok',
                    'amount' => $amount,
                ]))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $tournaments->each(function ($tournament) use ($user, $originalPlayerDeposits) {
                $originalPlayerDeposits[$user->id][$tournament->id] = Tournament::find($tournament->id)->player_deposit;
                $response = $this->actingAs($user)->post(route('register.post', ['tournament' => $tournament]))
                    ->assertSessionHasErrors('insufficient-funds');
            });
        });
    }

    /**
     * The `api/tournaments` response should have objects with
     * a `current_pot` value for non-free tournaments.
     */
    public function testTotalPotAttribute()
    {
        $this->seed();

        $numOfUsers  = 3;
        $users       = User::role('Admin')->limit($numOfUsers)->get();
        factory(Tournament::class, 3)->states(EntryFeeType::TARGET_POT)->create();
        factory(Tournament::class, 3)->states(EntryFeeType::FLAT_FEE)->create();

        $tournaments = Tournament::all();

        $users->each(function ($user) use ($tournaments, $numOfUsers) {
            $amount = '1000.00';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', [
                    'nonce'  => 'fake-card-nonce-ok',
                    'amount' => $amount,
                ]))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $tournaments->each(function ($tournament) use ($user, $numOfUsers) {
                $amount = $tournament->entryFeeType->is_flat_fee ? $tournament->entry_fee : $tournament->player_deposit;

                $response = $this->actingAs($user)->post(route('register.post', compact('tournament', 'amount')))
                    ->assertSessionHasNoErrors();
            });
        });

        $response = $this->json('GET', route('tournaments.fetch'));
        collect(json_decode($response->getContent()))->each(function ($tournament) {
            $this->assertEquals(Tournament::find($tournament->id)->total_pot, $tournament->total_pot);
        });
    }
}
