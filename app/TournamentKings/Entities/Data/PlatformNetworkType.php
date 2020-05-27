<?php

namespace App\TournamentKings\Entities\Data;

use MabeEnum\Enum;

class PlatformNetworkType extends Enum
{
    const STEAM          = 'Steam';
    const BATTLE_NET     = 'BattleNet';
    const EPIC           = 'Epic';
    const ORIGIN         = 'Origin';
    const PSN            = 'PSN';
    const XBOX_LIVE      = 'XBOX Live';
    const CROSS_PLATFORM = 'Cross-Platform';
}
