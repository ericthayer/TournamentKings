<?php

namespace App\TournamentKings\Managers;

use Illuminate\Support\Collection;
use App\Tournamentkings\Entities\Models\Match;
use App\Tournamentkings\Entities\Models\Round;
use App\TournamentKings\Entities\Models\Player;
use App\TournamentKings\Entities\Models\Tournament;

class MatchManager
{
    public static function findFirstOpenMatch(Tournament $tournament): Match
    {
        $firstRound     = $tournament->rounds->first();
        $firstOpenMatch = new Match;

        foreach ($firstRound->matches as $match) {
            if (empty($match->players) || count($match->players) < 2) {
                $firstOpenMatch = $match;
                break;
            }
        }

        return $firstOpenMatch;
    }

    public static function findNextOpenMatch(Tournament $tournament, Match $match): ?Match
    {
        $nextRound = Round::where('tournament_id', $tournament->id)->where('number', $match->nextRound)->first();
        $nextMatch = null;

        if ($match->round != $nextRound->number) {
            $nextMatch = self::findOpenMatch($nextRound->matches);
        }

        return $nextMatch;
    }

    public static function findFinalMatch(Tournament $tournament): ?Match
    {
        return $tournament->matches->last();
    }

    public static function findRandomOpenMatch(Collection $matches): ?Match
    {
        return self::getOpenMatches($matches)->random();
    }

    public static function findOpenMatch(Collection $matches): ?Match
    {
        return self::getOpenMatches($matches)->first();
    }

    public static function findOpenBronzeMatch(Collection $matches): ?Match
    {
        return self::getOpenMatches($matches)->filter(function ($match) {
            if ($match->bronze_match) {
                return $match;
            }
        })->first();
    }

    public static function getOpenMatches(Collection $matches): Collection
    {
        $openMatches = new Collection;

        if (! $matches->isEmpty()) {
            $openMatches = $matches->filter(function ($item) {
                if (count($item->players) < 2) {
                    return $item;
                }
            });
        }

        return $openMatches;
    }

    public static function matchesHaveAnOpenByeMatch(Collection $matches): ?Match
    {
        $match =  $matches->filter(function ($item) {
            if ($item->bye && count($item->players) < 2) {
                return $item;
            }
        })->first();

        return $match;
    }

    public static function registerPlayer(Player $player, Match $match)
    {
        if ($match) {
            $order = count($match->players) + 1;
            $match->players()->attach($player, ['order' => $order]);
        }
    }
}
