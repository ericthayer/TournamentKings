<?php

namespace App\TournamentKings\Managers;

use App\TournamentKings\Entities\Models\Match;
use App\TournamentKings\Entities\Models\Tournament;

class RoundManager
{
    public static $byesForRoundTwo       = 0;
    public static $nextHighestPowerOfTwo = 0;

    public static function buildMatches(Tournament $tournamentWithRounds): Tournament
    {
        foreach ($tournamentWithRounds->rounds as $round) {
            $matches         = self::getMatches($round->number, $tournamentWithRounds->total_slots);
            $existingMatches = $round->matches;

            for ($i = 1; $i <= $matches; $i++) {
                $match = [
                    'tournament_id' => $tournamentWithRounds->id,
                    'round_id'      => $round->id,
                    'round'         => $round->number,
                    'number'        => $i,
                ];

                $match2 = $match;

                if ($round->number == 2 && self::$byesForRoundTwo != 0) {
                    $byeMatches = self::$byesForRoundTwo / 2;
                    if ($i <= $byeMatches) {
                        $match2['bye'] = true;
                    }
                }

                if ($round->number === 2 && self::$byesForRoundTwo === 0) {
                    $match2['bye'] = false;
                }

                Match::updateOrCreate(
                    $match,
                    $match2
                );
            }
        }

        return $tournamentWithRounds->load('rounds.matches');
    }

    public static function getMatches(int $roundNumber, int $totalSlots)
    {
        $slotCount = $totalSlots;

        $isPowerOfTwo = self::isPowerOfTwo($slotCount);

        if (! $isPowerOfTwo && $roundNumber === 1) {
            self::$nextHighestPowerOfTwo = self::highestPowerOfTwo($slotCount);

            self::$byesForRoundTwo = self::$nextHighestPowerOfTwo - $slotCount;
            $slotCount             = $slotCount - self::$byesForRoundTwo;
        }

        if ($roundNumber === 2) {
            $slotCount = $slotCount + self::$byesForRoundTwo;
        }

        if ($roundNumber > 2 && self::$nextHighestPowerOfTwo) {
            $slotCount = self::$nextHighestPowerOfTwo;
        }

        for ($i = 1; $i <= $roundNumber; $i++) {
            $slotCount = round($slotCount / 2);
        }

        return $slotCount;
    }

    public static function isPowerOfTwo(int $slots)
    {
        $bin = decbin($slots);

        return preg_match('/^0*10*$/', $bin);
    }

    public static function highestPowerOfTwo($number)
    {
        $res = 0;
        for ($i = $number; $i >= 1; $i++) {
            // If i is a power of 2
            if (($i & ($i - 1)) == 0) {
                $res = $i;
                break;
            }
        }

        return $res;
    }
}
