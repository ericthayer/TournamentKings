<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Support\Carbon;
use App\Events\TournamentDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    const TOURNAMENT_TYPES = [
      'public'  => 'Public',
      'private' => 'Private',
   ];

    protected $dates               = ['start_datetime'];
    protected $auth_user_player_id = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'game_type_id',
        'total_slots',
        'created_by_player_id',
        'start_datetime',
        'tournament_type',
        'target_pot',
        'password',
        'entry_fee_type_name',
        'entry_fee',
    ];

    protected $appends = [
        'available_slots',
        'winner_gamer_tag',
        'can_edit',
        'can_delete',
    ];

    /*
     * This does not get triggered when tournaments are deleted through the tournament controller's "destroy" method.
     * With hope, there might be a way to properly associate this to the model deletions...
     */
//    protected $dispatchesEvents = [
//        'deleted' => TournamentDeleted::class,
//    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->auth_user_player_id = Auth::user()->player->id;
        } else {
            $this->auth_user_player_id = session()->get('auth_user_player_id', 0);
        }
    }

    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }

    public function entryFeeType()
    {
        return $this->belongsTo(EntryFeeType::class, 'entry_fee_type_name', 'name');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withPivot('id');
    }

    public function createdByPlayer()
    {
        return $this->belongsTo(Player::class, 'created_by_player_id');
    }

    public function winner()
    {
        return $this->hasOne(Player::class, 'id', 'winner_player_id');
    }

    public function balance()
    {
        return $this->hasOne(TournamentBalance::class);
    }

    public function matches()
    {
        return $this->hasMany(Match::class, 'tournament_id', 'id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function placement(Player $player): ?Placement
    {
        $playerTournament = $this->players->where('id', $player->id)->first();

        if (! $playerTournament) {
            return null;
        }

        return Placement::find($playerTournament->pivot->id);
    }

    public function prizes()
    {
        return $this->hasMany(TournamentPrizes::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function getAvailableSlotsAttribute()
    {
        $playerCount = $this->players->count();

        return $this->total_slots - $playerCount;
    }

    public function getWinnerGamerTagAttribute()
    {
        $winner_gamer_tag = null;

        if (isset($this->winner)) {
            $winner_gamer_tag = $this->winner->gamer_tag;
        }

        return $winner_gamer_tag;
    }

    public function getCanEditAttribute()
    {
        return $this->mayEdit();
    }

    public function getCanDeleteAttribute()
    {
        return $this->mayDelete();
    }

    public function mayEdit()
    {
        // only possible to modify tournament if no results have been posted
        $matches = $this->matches()->whereNotNull('winner_player_id')->get();

        if (! $matches->isEmpty()) {
            return false;
        }

        // only possible to modify tournament if it hasn't started yetß
        if ($this->start_datetime >= Carbon::now()->toFormattedDateString()) {
            return false;
        }

        // user may edit if they created tournament
        if ($this->created_by_player_id == $this->auth_user_player_id) {
            return true;
        }

        return false;
    }

    public function mayDelete()
    {
        // permissions are same as Edit, for now...
        return $this->mayEdit();
    }

    public function mayRegister()
    {
        $this->auth_user_player_id = Auth::user()->player->id;

        // only can register if there is a slot left
        if ($this->available_slots > 0) {
            $is_registered = $this->players->find($this->auth_user_player_id);
            // and you are not already registered
            if (! isset($is_registered)) {
                return true;
            }
        }

        return false;
    }

    public function getRegisteredPlayerList()
    {
        $registered_players = $this->players;
        if ($registered_players->count()) {
            $registered_players_gamer_tags = $registered_players->pluck('gamer_tag')->all();

            return implode(', ', $registered_players_gamer_tags);
        }

        return '';
    }

    /**
     * determine if any tournament matches need set up.
     *
     * @param int $tournament_id
     * @deprecated
     */
    public function determineMatches()
    {
        // if no available_slots left, proceed
        if ($this->available_slots == 0) {

            // determine if any matches are set up
            $matches     = $this->matches()->orderby('round', 'desc')->orderby('number', 'desc')->get();
            $match_count = $matches->count();

            // if no matches exist, create the first round
            if ($match_count == 0) {
                // pass round 1, tournament players, match number 1
                $this->setRound(1, $this->players, 1);
            } else {
                // there are matches already

                // find the highest round number set up
                $current_round = $matches->first()->round;
                // find the highest round match number
                $current_round_match_number = $matches->first()->number;
                // find all the matches in the current round
                $current_round_matches = $matches->where('round', $current_round);
                // count how many matches are set up for the current_round
                $current_round_matches_count = $current_round_matches->count();
                // get array of all match id's in the round
                $current_round_matches_ids = $current_round_matches->pluck('id')->all();
                // get array of all the players in the current round
                $current_round_match_players = MatchPlayer::whereIn('match_id', $current_round_matches_ids)->pluck('player_id')->all();

                // determine if matches in the first round are missing and if so, set them up
                if (($current_round == 1) && ($this->total_slots != count($current_round_match_players))) {
                    // loop through tournament players
                    $new_player_ids = [];
                    foreach ($this->players as $player) {
                        if (! in_array($player->id, $current_round_match_players)) {
                            $new_player_ids[] = $player->id;
                        }
                    }

                    // if there are new players, set up new matches
                    if (count($new_player_ids)) {
                        // find the players by their id's
                        $players = Player::whereIn('id', $new_player_ids)->get();
                        // increment the match number by 1
                        $next_match_number = $current_round_match_number + 1;
                        // pass the current round, new players, and next match number
                        $this->setRound($current_round, $players, $next_match_number);
                    }
                } else {
                    // get the confirmed current matches count
                    $current_round_matches_confirmed_count = $current_round_matches->where('result_confirmed_by', '!=', null)->count();
                    // if there are unconfirmed matches, do nothing
                    if ($current_round_matches_confirmed_count != $current_round_matches_count) {
                        return;
                    }
                    // get the current match/round's winner
                    $current_round_matches_first_winner_player_id = $current_round_matches->first()->winner_player_id;
                    // determine if there should be a next round, or if this is the final round and there is a winner
                    if ($current_round_matches_count == 1 && $current_round_matches_first_winner_player_id) {
                        // this was the final round, record the winner in the tournament table
                        $this->winner_player_id = $current_round_matches_first_winner_player_id;
                        $this->save();
                    } else {
                        // find the winners who will continue onto the next round
                        $winner_player_ids = [];
                        foreach ($current_round_matches as $current_round_match) {
                            if ($current_round_match->winner_player_id) {
                                $winner_player_ids[] = $current_round_match->winner_player_id;
                            }
                        }

                        // if there are all matches with winners, set up next Round
                        if (count($winner_player_ids) == $current_round_matches_count) {
                            // find the players by their id's
                            $players = Player::whereIn('id', $winner_player_ids)->get();
                            // increment the round by 1
                            $next_round = $current_round + 1;
                            // pass next round, winner players, match number 1
                            $this->setRound($next_round, $players, 1);
                        }
                    }
                }
            }
        }
    }

    /**
     * Set up a tournament round of matches.
     *
     * @param int $round
     * @param collection $players
     * @param int $match_number
     */
    public function setRound($round, $players, $match_number = 1)
    {
        // loop through a randomized order of players
        foreach ($players->shuffle()->all() as $iteration=>$player) {
            // check if this iteration is divisible by 2
            if (($iteration + 1) % 2 == 0) {
                // increment the match number
                $match_number++;
            } else {
                $match = new Match(
                    [
                        'round'      => $round,
                        'number'     => $match_number,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    ]
                );

                $this->matches()->save($match);
            }
            // insert a match_player record for each player
            $match->players()->attach(
                $player->id,
                ['order' => ((($iteration + 1) % 2 == 0) ? 2 : 1)]
            );
        }
    }

    public function getDisplayEntryFeeAttribute()
    {
        setlocale(LC_MONETARY, config('app.monetary_locale'));

        return money_format('%+.2n', $this->entry_fee);
    }

    public function getDisplayTargetPotAttribute()
    {
        setlocale(LC_MONETARY, config('app.monetary_locale'));

        return money_format('%+.2n', $this->target_pot);
    }

    public function getDisplayPlayerDepositAttribute()
    {
        setlocale(LC_MONETARY, config('app.monetary_locale'));

        return money_format('%+.2n', $this->player_deposit);
    }

    public function getTotalPotAttribute()
    {
        $pot = null;
        if ($this->target_pot) {
            $pot = $this->target_pot;
        } elseif ($this->balance) {
            $pot = $this->balance->balance;
        }

        return $pot;
    }
}
