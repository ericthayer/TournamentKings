<?php

namespace Tests\Feature\Auth\Payments;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Managers\RoundManager;
use App\Tournamentkings\Entities\Models\Player;
use App\Tournamentkings\Entities\Models\TkSetting;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Managers\TournamentManager;
use App\Tournamentkings\Entities\Models\Transaction;
use App\Tournamentkings\Entities\Models\EntryFeeType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Tournamentkings\Entities\Models\PlacementType;
use App\Tournamentkings\Entities\Models\TransactionType;
use App\Tournamentkings\Entities\Models\TournamentPrizes;
use App\Tournamentkings\Entities\Models\TournamentBalance;

class TournamentTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /**
     * Users can add funds to tournaments, after which the balances of the
     * tournaments and users will be accurate.
     *
     * @return void
     */
    public function testTournamentBalance()
    {
        $this->seed();

        $users       = User::role('Admin')->limit(3)->get();
        $tournaments = factory(Tournament::class, 5)->states(EntryFeeType::FLAT_FEE)->create();

        $originalBalances = [];
        $submittedFees    = [];
        $users->each(function ($user) use ($tournaments, &$originalBalances, &$submittedFees) {
            $amount = '5000.00';
            $nonce = 'fake-card-nonce-ok';
            $this->actingAs($user)
                ->post(route('balance.deposit.post'), compact('amount', 'nonce'))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $tournaments->each(function ($tournament) use ($user, &$originalBalances, &$submittedFees) {
                $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
                $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

                $amount = $tournamentWithRoundsAndMatches->entry_fee;
                $submittedFees[$tournament->id] = $submittedFees[$tournament->id] ?? 0;
                $submittedFees[$tournament->id] =  bcadd($amount, $submittedFees[$tournament->id], 2);
                $registerPost = $this->actingAs($user)
                    ->post(
                        route(
                            'register.post',
                            compact('tournament')
                        )
                    );
                $registerPost->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('tournaments.registration-success'));

                $user = User::find($user->id);
                $originalBalances[$user->id] = $user->balance->balance;
            });
        });

        $tournaments->each(function ($tournament) use ($submittedFees) {
            $this->assertEquals($submittedFees[$tournament->id], $tournament->balance->balance);
        });

        $users = User::role('Admin')->limit(3)->get();
        $users->each(function ($user) use ($originalBalances) {
            $this->assertEquals($originalBalances[$user->id], $user->balance->balance);
        });
    }

    /**
     * A user should not be able to add funds to a tournament if they do not
     * have enough in their balance.
     */
    public function testInsufficientBalance()
    {
        $this->seed();

        $amount = '1.00';
        $user   = User::role('Admin')->first();
        $this->actingAs($user)
            ->post(route('balance.deposit.post', [
                'nonce'  => 'fake-card-nonce-ok',
                'amount' => $amount,
            ]))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $tournament                     = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->create();
        $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
        $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

        $diffIntVal    = $tournamentWithRoundsAndMatches->entry_fee - floatval($amount);
        $diffFloatVal  = floatval($diffIntVal) / 100;
        $formattedDiff = money_format('%+.2n', $diffFloatVal);
        $this->actingAs($user)
            ->post(
                route(
                    'register.post',
                    compact('tournament')
                )
            )
            ->assertSessionHasErrors(['insufficient-funds'], __('balance.no-balance'));
    }

    public function testTournamentPrizes()
    {
        $this->seed();

        $users       = User::role('Admin')->limit(3)->get();
        $tournaments = factory(Tournament::class, 5)->states(EntryFeeType::FLAT_FEE)->create();

        $userAmountString = '5000.00';

        $users->each(function ($user) use ($tournaments, $userAmountString) {
            $amount = $userAmountString;
            $this->actingAs($user)
                ->post(route('balance.deposit.post', [
                    'nonce'  => 'fake-card-nonce-ok',
                    'amount' => $amount,
                ]))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            $tournaments->each(function ($tournament) use ($amount, $user) {
                $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
                $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

                $amount = $tournamentWithRoundsAndMatches->entry_fee;
                $this->actingAs($user)
                    ->post(route('register.post', [
                        'tournament' => $tournament,
                    ]))
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('tournaments.registration-success'));
            });
        });

        $tournaments->each(function ($tournament) {
            collect(PlacementType::prizeNameList())->each(function ($name) use ($tournament) {
                $tournamentBalance = TournamentBalance::find($tournament->id)->balance;
                $feeDecimal = TkSetting::find(1)->split;
                $feeFactor = bcsub(1, $feeDecimal, 4);
                $balanceAfterFee = bcmul($tournamentBalance, $feeFactor, 2);

                $placementSplit = PlacementType::$name()->split;
                $expectedPrize = bcmul($placementSplit, $balanceAfterFee, 2);
                $this->assertEquals(
                    $expectedPrize,
                    TournamentPrizes::where(
                        ['tournament_id' => $tournament->id, 'placement_type_name' => $name]
                    )->get()[0]->amount);
            });
        });
    }

    public function testTournamentPayouts()
    {
        Storage::fake('s3');
        $this->seed();

        $numOfTournaments = 5;
        $tournaments      = factory(Tournament::class, $numOfTournaments)->states(EntryFeeType::FLAT_FEE)->create();

        $userStartingBalance       = $numOfTournaments * 1000;
        $userStartingBalanceString = number_format($userStartingBalance, 2, '.', '');

        $numOfUsers       = $tournaments->pluck('total_slots')->max();
        $users            = User::role('Admin')->limit($numOfUsers)->get();
        $originalBalances = [];

        // Give the users a balance
        $users->each(function ($user) use ($userStartingBalanceString) {
            $amount = $userStartingBalanceString;
            $nonce = 'fake-card-nonce-ok';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', compact('amount', 'nonce')))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');
        });

        $tournaments->each(function ($tournament) use ($numOfUsers, $users, &$originalBalances) {
            // Setup rounds and matches
            $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
            $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

            // Register user for the tournament
            for ($i = 0; $i < $tournament->total_slots; $i++) {
                $user = $users[$i];
                $amount = $tournamentWithRoundsAndMatches->entry_fee;
                $this->actingAs($users[$i])
                    ->post(route('register.post', [
                        'tournament' => $tournament,
                    ]))
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('success');

                $user = User::find($user->id);
                $originalBalances[$user->id] = $user->balance->balance;
            }

            // Go through each match by round
            $tournament->matches->sortBy('round')->each(function ($match) use (&$originalBalances, $tournament) {
                $playerOne = $match->players[0];
                $player_1_gamer_tag = $playerOne->id;
                $playerTwo = $match->players[1];
                $player_2_gamer_tag = $playerTwo->id;

                $player_1_score = 50;
                $player_2_score = 20;

                $roundNumber = $match->round;
                $matchNumber = $match->number;
                $otherTag = htmlspecialchars($playerTwo->gamer_tag);
                $result_screen = UploadedFile::fake()->image('animage.png');

                // Post Results
                $postResults = $this->actingAs($playerOne->user)
                    ->put(
                        route('matches.update',
                            compact(
                                'match'
                            )
                        ),
                        compact(
                            'player_1_gamer_tag',
                            'player_2_gamer_tag',
                            'player_1_score',
                            'player_2_score',
                            'result_screen'
                        )
                    );
                $postResults->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('matches.update-success',
                        compact('roundNumber', 'matchNumber', 'otherTag')
                    ));

                $this->actingAs($playerTwo->user)
                    ->post(
                      route('matches.confirm.post', compact('match'))
                    )
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('matches.confirmation-success',
                        compact('roundNumber', 'matchNumber')
                    ));

                $playerOne = Player::find($playerOne->id);
                $playerTwo = Player::find($playerTwo->id);
                if ($match->final_match) {
                    $placementType = PlacementType::find(PlacementType::GOLD);
                    $expectedBalance = $this->getBalanceAfterPrize(
                        $placementType,
                        $tournament,
                        $originalBalances[$playerOne->user->id]
                    );
                    $this->assertEquals($expectedBalance, $playerOne->user->balance->balance);
                    $originalBalances[$playerOne->user->id] = $expectedBalance;

                    $prize = TournamentPrizes::where('tournament_id', $tournament->id)
                        ->where('placement_type_name', $placementType->name)
                        ->first()
                        ->amount;

                    $expectedPrize = $this->getExpectedPrize($tournament, $placementType);

                    $this->assertEquals($expectedPrize, $prize);

                    $expectedBalance = $originalBalances[$playerTwo->user->id];
                    if ($tournament->total_slots > 2) {
                        $placementType = PlacementType::find(PlacementType::SILVER);
                        $expectedBalance = $this->getBalanceAfterPrize(
                            $placementType,
                            $tournament,
                            $originalBalances[$playerTwo->user->id]
                        );

                        $prize = TournamentPrizes::where('tournament_id', $tournament->id)
                            ->where('placement_type_name', $placementType->name)
                            ->first()
                            ->amount;

                        $expectedPrize = $this->getExpectedPrize($tournament, $placementType);

                        $this->assertEquals($expectedPrize, $prize);
                    }

                    $this->assertEquals($expectedBalance, $playerTwo->user->balance->balance);
                    $originalBalances[$playerTwo->user->id] = $expectedBalance;
                }

                if ($match->bronze_match) {
                    $expectedBalance = $originalBalances[$playerOne->user->id];
                    if ($tournament->total_slots > 2) {
                        $placementType = PlacementType::find(PlacementType::BRONZE);
                        $expectedBalance = $this->getBalanceAfterPrize(
                            $placementType,
                            $tournament,
                            $originalBalances[$playerOne->user->id]
                        );

                        $prize = TournamentPrizes::where('tournament_id', $tournament->id)
                            ->where('placement_type_name', $placementType->name)
                            ->first()
                            ->amount;

                        $expectedPrize = $this->getExpectedPrize($tournament, $placementType);

                        $this->assertEquals($expectedPrize, $prize);
                    }
                    $this->assertEquals($expectedBalance, $playerOne->user->balance->balance);
                    $originalBalances[$playerOne->user->id] = $expectedBalance;
                }
            });
        });
    }

    public function testWinnerTakeAll()
    {
        Storage::fake('s3');
        $this->seed();

        $numOfTournaments = 5;
        $numOfUsers       = 2;
        $tournaments      = factory(Tournament::class, $numOfTournaments)->states(EntryFeeType::FLAT_FEE)->create([
            'total_slots' => $numOfUsers,
        ]);

        $userStartingBalance       = $numOfTournaments * 1000;
        $userStartingBalanceString = number_format($userStartingBalance, 2, '.', '');

        $users            = User::role('Admin')->limit($numOfUsers)->get();
        $originalBalances = [];

        // Give the users a balance
        $users->each(function ($user) use ($userStartingBalanceString) {
            $amount = $userStartingBalanceString;
            $nonce = 'fake-card-nonce-ok';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', compact('amount', 'nonce')))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');
        });

        $tournaments->each(function ($tournament) use ($users, &$originalBalances) {
            // Setup rounds and matches
            $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
            $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

            // Register user for the tournament
            for ($i = 0; $i < $tournament->total_slots; $i++) {
                $user = $users[$i];
                $amount = $tournamentWithRoundsAndMatches->entry_fee;
                $this->actingAs($users[$i])
                    ->post(route('register.post', [
                        'tournament' => $tournament,
                    ]))
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('success');

                $user = User::find($user->id);
                $originalBalances[$user->id] = $user->balance->balance;
            }

            // Go through each match by round
            $tournament->matches->sortBy('round')->each(function ($match) use (&$originalBalances, $tournament) {
                $playerOne = $match->players[0];
                $player_1_gamer_tag = $playerOne->id;
                $playerTwo = $match->players[1];
                $player_2_gamer_tag = $playerTwo->id;

                $player_1_score = 50;
                $player_2_score = 20;

                $roundNumber = $match->round;
                $matchNumber = $match->number;
                $otherTag = htmlspecialchars($playerTwo->gamer_tag);
                $result_screen = UploadedFile::fake()->image('animage.png');

                // Post Results
                $postResults = $this->actingAs($playerOne->user)
                    ->put(
                        route('matches.update',
                            compact(
                                'match'
                            )
                        ),
                        compact(
                            'player_1_gamer_tag',
                            'player_2_gamer_tag',
                            'player_1_score',
                            'player_2_score',
                            'result_screen'
                        )
                    );
                $postResults->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('matches.update-success',
                        compact('roundNumber', 'matchNumber', 'otherTag')
                    ));

                $this->actingAs($playerTwo->user)
                    ->post(
                        route('matches.confirm.post', compact('match'))
                    )
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('tk_message_status', __('matches.confirmation-success',
                        compact('roundNumber', 'matchNumber')
                    ));

                $playerOne = Player::find($playerOne->id);
                $playerTwo = Player::find($playerTwo->id);
                if ($match->final_match) {
                    $placementType = PlacementType::find(PlacementType::GOLD);
                    $expectedBalance = $this->getBalanceAfterPrize(
                        $placementType,
                        $tournament,
                        $originalBalances[$playerOne->user->id]
                    );
                    $this->assertEquals($expectedBalance, $playerOne->user->balance->balance);
                    $originalBalances[$playerOne->user->id] = $expectedBalance;

                    $prize = TournamentPrizes::where('tournament_id', $tournament->id)
                        ->where('placement_type_name', PlacementType::GOLD)
                        ->first()
                        ->amount;

                    $expectedPrize = $this->getExpectedPrize($tournament);

                    $this->assertEquals($expectedPrize, $prize);

                    $expectedBalance = $originalBalances[$playerTwo->user->id];
                    $this->assertEquals($expectedBalance, $playerTwo->user->balance->balance);
                    $originalBalances[$playerTwo->user->id] = $expectedBalance;
                }
            });
        });
    }

    /**
     * Make sure there is an internal_tk_payout transaction at the end of a tournament with an amount
     * of the tournaments remaining balance.
     */
    public function testTkPayout()
    {
        Storage::fake('s3');
        $this->seed();

        $amount     = '1000.00';
        $numOfUsers = 2;
        $users      = User::role('Admin')->limit($numOfUsers)->get();

        $tournament = factory(Tournament::class)->states(EntryFeeType::FLAT_FEE)->create([
            'total_slots' => 2,
        ]);

        $tournamentWithRounds           = TournamentManager::buildRounds($tournament);
        $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

        $users->each(function ($user) use ($amount, $tournament, $tournamentWithRoundsAndMatches) {
            // Give the users a balance
            $nonce = 'fake-card-nonce-ok';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', compact('amount', 'nonce')))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');

            // Register user for the tournament
            $amount = $tournamentWithRoundsAndMatches->entry_fee;
            $this->actingAs($user)
                ->post(route('register.post', [
                    'tournament' => $tournament,
                ]))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');
        });

        $tournament                = Tournament::find($tournament->id);
        $originalTournamentBalance = $tournament->balance->balance;

        // Go through each match by round
        $tournament->matches->sortBy('round')->each(function ($match) use ($tournament) {
            $playerOne = $match->players[0];
            $player_1_gamer_tag = $playerOne->id;
            $playerTwo = $match->players[1];
            $player_2_gamer_tag = $playerTwo->id;

            $player_1_score = 50;
            $player_2_score = 20;

            $roundNumber = $match->round;
            $matchNumber = $match->number;
            $otherTag = htmlspecialchars($playerTwo->gamer_tag);
            $result_screen = UploadedFile::fake()->image('animage.png');

            // Post Results
            $postResults = $this->actingAs($playerOne->user)
                ->put(
                    route('matches.update',
                        compact(
                            'match'
                        )
                    ),
                    compact(
                        'player_1_gamer_tag',
                        'player_2_gamer_tag',
                        'player_1_score',
                        'player_2_score',
                        'result_screen'
                    )
                );
            $postResults->assertSessionHasNoErrors()
                ->assertSessionHas('tk_message_status', __('matches.update-success',
                    compact('roundNumber', 'matchNumber', 'otherTag')
                ));

            $this->actingAs($playerTwo->user)
                ->post(
                    route('matches.confirm.post', compact('match'))
                )
                ->assertSessionHasNoErrors()
                ->assertSessionHas('tk_message_status', __('matches.confirmation-success',
                    compact('roundNumber', 'matchNumber')
                ));
        });

        $tournament = Tournament::find($tournament->id);

        $this->assertEquals(0, $tournament->balance->balance);

        $prizeSum = TournamentPrizes::where('tournament_id', $tournament->id)
            ->sum('amount');

        $expectedTkAmount = bcsub($originalTournamentBalance, $prizeSum, 2);

        $tkPayoutAmount = abs(Transaction::where('transaction_type_name', 'internal_tk_payout')->first()->amount);

        $this->assertEquals($expectedTkAmount, $tkPayoutAmount);
    }

    public function testTournamentRefunds()
    {
        $this->seed();

        $numOfTournaments = 5;
        $tournaments      = factory(Tournament::class, $numOfTournaments)->states(EntryFeeType::FLAT_FEE)->create();

        $userStartingBalance       = $numOfTournaments * 1000;
        $userStartingBalanceString = number_format($userStartingBalance, 2, '.', '');

        $numOfUsers       = $tournaments->pluck('total_slots')->max();
        $users            = User::role('Admin')->limit($numOfUsers)->get();
        $originalBalances = [];

        // Give the users a balance
        $users->each(function ($user) use ($userStartingBalanceString) {
            $amount = $userStartingBalanceString;
            $nonce = 'fake-card-nonce-ok';
            $this->actingAs($user)
                ->post(route('balance.deposit.post', compact('amount', 'nonce')))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('success');
        });

        $tournaments->each(function ($tournament) use ($numOfUsers, $users, &$originalBalances) {
            // Setup rounds and matches
            $tournamentWithRounds = TournamentManager::buildRounds($tournament);
            $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

            // Register user for the tournament
            for ($i = 0; $i < $tournament->total_slots; $i++) {
                $user = $users[$i];
                $amount = $tournamentWithRoundsAndMatches->entry_fee;
                $this->actingAs($users[$i])
                    ->post(route('register.post', [
                        'tournament' => $tournament,
                    ]))
                    ->assertSessionHasNoErrors()
                    ->assertSessionHas('success');

                $user = User::find($user->id);
                $originalBalances[$user->id] = $user->balance->balance;
            }
        });

        $tournaments->each(function ($tournament) {
            $this->actingAs(User::role('admin')->first())->delete(route('tournaments.destroy', compact('tournament')));
        });

        $users->each(function ($user) use ($originalBalances) {
            $user = User::find($user->id);
            $transactionSum = abs($user->transactions->where('transaction_type_name', TransactionType::TOURNAMENT_BUY_IN)->sum('amount'));
            $expectedBalance = bcadd($originalBalances[$user->id], $transactionSum, 2);

            $this->assertEquals($expectedBalance, $user->balance->balance);
        });
    }

    private function getBalanceAfterPrize(
        PlacementType $placementType,
        Tournament $tournament,
        float $balance
    ): float {
        $prize = TournamentPrizes::where('tournament_id', $tournament->id)
            ->where('placement_type_name', $placementType->name)
            ->first()
            ->amount;

        return floatval(bcadd($balance, $prize, 2));
    }

    private function getExpectedPrize(Tournament $tournament, PlacementType $placementType = null)
    {
        $totalSlots = $tournament->total_slots;

        $expectedPrize = bcmul(
            bcmul(
                $tournament->entry_fee,
                $totalSlots,
                2),
            bcsub(
                1, TkSetting::find(1)->split,
                4),
            2
        );

        if ($totalSlots > 2) {
            $expectedPrize = bcmul($expectedPrize, $placementType->split, 2);
        }

        return $expectedPrize;
    }
}
