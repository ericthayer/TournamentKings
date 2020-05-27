<?php

namespace App\TournamentKings\Managers;

use App\TournamentKings\Entities\Models\Round;
use App\Tournamentkings\Entities\Models\Player;
use App\TournamentKings\Entities\Models\Tournament;

class TournamentManager
{
    public static function buildRounds(Tournament $tournament): Tournament
    {
        $rounds = self::getRounds($tournament->total_slots);

        self::createRounds($rounds, $tournament->total_slots, $tournament->id);

        return $tournament->load('rounds');
    }

    public static function getRounds(int $totalSlots): float
    {
        return ceil(log($totalSlots, 2));
    }

    public static function createRounds(float $rounds, int $totalSlots, int $tournament_id)
    {
        if ($totalSlots > 2) {
            $rounds += 1;
        }

        for ($i = 1; $i <= $rounds; $i++) {
            Round::updateOrCreate(
                ['tournament_id' => $tournament_id, 'number' => $i],
                ['tournament_id' => $tournament_id, 'number' => $i]
            );
        }
    }

    public static function registerPlayer(Player $player, Tournament $tournament)
    {
        $firstRound  = $tournament->rounds->load('matches', 'matches.players')->first();
        $byeMatch    = null;

        if ($tournament->rounds->count() > 1) {
            $secondRound = $tournament->rounds->load('matches', 'matches.players')->offsetGet(1);
            $byeMatch    = MatchManager::matchesHaveAnOpenByeMatch($secondRound->matches);
        }

        if ($byeMatch) {
            $openMatch = $byeMatch;
        } else {
            $openMatch = MatchManager::findRandomOpenMatch($firstRound->matches);
        }

        MatchManager::registerPlayer($player, $openMatch);
    }

    public static function updateTournament(Tournament $tournament, Tournament $updatedTournament): Tournament
    {
        $currentRounds = self::getRounds($tournament->total_slots);
        $updatedRounds = self::getRounds($updatedTournament->total_slots);

        if ($updatedRounds > $currentRounds) {
            self::createRounds($updatedRounds, $updatedTournament->total_slots, $tournament->id);
        }

        return $updatedTournament->load('rounds');
    }
}
