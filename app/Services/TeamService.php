<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use Carbon\Carbon;

class TeamService
{
    public function createPlayerGoalAgainst($team){
        Player::create([
            'name' => 'Gol Contra',
            'team_id' => $team->id,
        ]);
    }
}
