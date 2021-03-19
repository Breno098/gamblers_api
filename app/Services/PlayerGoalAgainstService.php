<?php

namespace App\Services;

use App\Models\Player;

class PlayerGoalAgainstService
{
    public function create($team){

        Player::create([
            'name' => 'Gol Contra',
            'team_id' => $team->id,
        ]);
    }
}
