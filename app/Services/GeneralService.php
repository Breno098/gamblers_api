<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Goal;
use App\Models\Player;
use App\Models\Scoreboard;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralService
{
    public function usersRanking()
    {
        $sql = "select user.name as username,
                       sum(score.score) as score
                from gamblers.scoreboards as score
                join gamblers.users user
                    on user.id = score.user_id
                group by user.name
                order by sum(score.score) desc";

        return DB::select($sql);
    }

    public function userReportById($id)
    {
        $sql = "select
                    game.stage					as stage,
                    team_home.name  			as team_home_name,
                    score.team_home_scoreboard	as team_home_scoreboard,
                    team_guest.name 			as team_guest,
                    score.team_guest_scoreboard	as team_guest_scoreboard,
                    score.score					as score,
                    score.report				as report
                from gamblers.games as game
                join gamblers.teams as team_home
                    on game.team_home_id = team_home.id
                join gamblers.teams as team_guest
                    on game.team_guest_id = team_guest.id
                join gamblers.scoreboards as score
                    on game.id = score.game_id
                where score.user_id = :id ";

        $reports =  DB::select($sql, [
            'id' => $id
        ]);

        foreach ($reports as &$report) {
            $report->report = json_decode(json_encode($report->report));
        }

        return $reports;
    }

    public function competitions()
    {
        return Competition::where('active', 1)->orderBy('name')->get();
    }
}
