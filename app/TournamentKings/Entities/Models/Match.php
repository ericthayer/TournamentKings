<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Match extends Model
{
    protected $auth_user_player_id  = 0;
    protected $result_screen_folder = '';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'round_id',
        'round',
        'number',
        'bye',
    ];

    protected $appends = [
        'player_one',
        'player_two',
        'bronze_match',
        'result_screen_url',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {
            $this->auth_user_player_id = Auth::user()->player->id;
        } else {
            $this->auth_user_player_id = session()->get('auth_user_player_id', 0);
        }

        $this->result_screen_folder = config('tk.match_image_path');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withPivot('order', 'score');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class, 'round_id', 'id');
    }

    public function getPlayerOneAttribute()
    {
        return $this->players->first();
    }

    public function getPlayerTwoAttribute()
    {
        $playerTwo = null;
        if ($this->players->count() > 1) {
            $playerTwo = $this->players->last();
        }

        return $playerTwo;
    }

    public function getBronzeMatchAttribute()
    {
        $isBronzeMatch = false;

        $totalRounds = $this->tournament->rounds->count();
        $round       = Round::find($this->round_id);

        if ($this->round === ($totalRounds - 1)) {
            if ($round->matches->count() === 1) {
                $isBronzeMatch = true;
            }
        }

        return $isBronzeMatch;
    }

    public function getFinalMatchAttribute()
    {
        $isFinalMatch = false;

        $lastMatch = $this->tournament->matches->last();

        if ($this->id === $lastMatch->id) {
            $isFinalMatch = true;
        }

        return $isFinalMatch;
    }

    public function getCurrentOpenRoundAttribute()
    {
        $isCurrentOpenRound = false;

        if ($this->nextRound > $this->round) {
            $nextRoundMatches = $this->tournament->rounds->where('number', $this->nextRound)->load('matches', 'matches.players')->map(function ($round) {
                return $round->matches->filter(function ($match) {
                    return $match->players->count() > 0;
                })->flatten();
            })->flatten();

            if ($nextRoundMatches->isEmpty()) {
                $isCurrentOpenRound = true;
            }
        }

        return $isCurrentOpenRound;
    }

    public function getNextRoundAttribute()
    {
        $nextRound = $this->round;
        if (! $this->isLastRound()) {
            $nextRound = $nextRound + 1;
        }

        return $nextRound;
    }

    public function isOwner()
    {
        return $this->auth_user_player_id == $this->tournament->created_by_player_id;
    }

    public function hasTournamentStarted(): bool
    {
        return Carbon::now() > $this->tournament->start_datetime;
    }

    public function isMatchReady(): bool
    {
        return $this->players->count() == 2;
    }

    public function isLastRound(): bool
    {
        return $this->round == $this->tournament->rounds->count();
    }

    public function isPlayer()
    {
        foreach ($this->players()->get() as $player) {
            if ($player->id == $this->auth_user_player_id) {
                return true;
            }
        }

        return false;
    }

    public function isConfirmed()
    {
        $confirmed = false;

        if ($this->areResultsPosted()) {
            if (! is_null($this->result_confirmed_by)) {
                $confirmed =  true;
            }
        }

        return $confirmed;
    }

    public function mayConfirmResult()
    {
        $canConfirm = false;

        // results have already been posted
        if ($this->areResultsPosted()) {
            // results have not been confirmed
            if (! $this->isConfirmed()) {
                if ($this->isOwner()) {
                    $canConfirm = true;
                }

                if ($this->isPlayer() && ($this->auth_user_player_id != $this->result_posted_by)) {
                    $canConfirm = true;
                }
            }
        }

        return $canConfirm;
    }

    public function isRoundOpen()
    {
        return $this->round == $this->currentOpenRound;
    }

    public function mayEditResult()
    {
        // have to be a player of the match and match has more then one player registered
        if ($this->isPlayer() && $this->areResultsPosted() && ! $this->isConfirmed()) {
            return true;
        }

        return false;
    }

    public function mayDeleteResult()
    {
        // can only modify results on open rounds
        if ($this->areResultsPosted() && ! $this->isConfirmed()) {

            // the tournament admin may delete
            if ($this->isOwner()) {
                return true;
            } elseif ($this->isPlayer()) {
                return true;
            }
        }

        return false;
    }

    public function mayPostResult()
    {
        // can only modify results on open rounds, and tournament has started, and results are not confirmed
        if ($this->hasTournamentStarted() && ! $this->isConfirmed()) {
            // have to be a player of the match and match has more then one player registered
            if ($this->isPlayer() && $this->isMatchReady() && ! $this->areResultsPosted()) {
                return true;
            }
        }

        return false;
    }

    public function areResultsPosted()
    {
        $isPosted = false;

        if (! is_null($this->result_posted_at)) {
            $isPosted = true;
        }

        return $isPosted;
    }

    public function existsResultScreen()
    {
        if (is_null($this->result_screen)) {
            return false;
        }

        return Storage::cloud()->exists($this->getResultScreenPath());
    }

    public function getResultScreenPath()
    {
        return $this->result_screen_folder.'/'.$this->result_screen;
    }

    public function getResultScreenUrlAttribute()
    {
        if ($this->existsResultScreen()) {
            return Storage::cloud()->url($this->getResultScreenPath());
        }

        return '';
    }

    public function getWinnerGamerTag()
    {
        if (! empty($this->winner_player_id)) {
            if ($this->winner_player_id == $this->player_one->id) {
                return $this->player_one->gamer_tag;
            }

            return $this->player_two->gamer_tag;
        }

        return '';
    }

    public function getWinnerScore()
    {
        if (! empty($this->winner_player_id)) {
            if ($this->winner_player_id == $this->player_one->id) {
                return $this->player_one->pivot->score;
            }

            return $this->player_two->pivot->score;
        }

        return '';
    }

    public function getLoserGamerTag()
    {
        if (! empty($this->winner_player_id)) {
            if ($this->winner_player_id == $this->player_one->id) {
                return $this->player_two->gamer_tag;
            }

            return $this->player_one->gamer_tag;
        }

        return '';
    }

    public function getLoserScore()
    {
        if (! empty($this->winner_player_id)) {
            if ($this->winner_player_id == $this->player_one->id) {
                return $this->player_two->pivot->score;
            }

            return $this->player_one->pivot->score;
        }

        return '';
    }
}
