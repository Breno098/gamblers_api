<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\Player;
use App\Models\Scoreboard;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ScoreService
{
    public function saveRequest($request, $type, $user_id = null)
    {
        if($user_id){
            $scoreboard = Scoreboard::where('game_id', $request->game['id'])->where('user_id', $user_id)->where('type', 'bet')->first();
        } else {
            $scoreboard = Scoreboard::where('game_id', $request->game['id'])->where('type', 'official')->first();
        }

        if($scoreboard){
            $scoreboard->update([
                'team_home_scoreboard' => count($request->goalsHome) ?? 0,
                'team_guest_scoreboard' => count($request->goalsGuest) ?? 0
            ]);
        } else {
            $scoreboard = Scoreboard::create([
                'game_id'   => $request->game['id'],
                'team_home_scoreboard' => count($request->goalsHome) ?? 0,
                'team_guest_scoreboard' => count($request->goalsGuest) ?? 0,
                'type'      => $type,
                'user_id'   => $user_id,
            ]);
        }

        Goal::where('scoreboard_id', $scoreboard->id)->delete();

        $goalsHome = $request->goalsHome;
        collect($goalsHome)->each(function($player) use ($scoreboard) {
            Goal::create([
                'player_id' => $player['id'],
                'team_id'   => $player['team_id'],
                'scoreboard_id' => $scoreboard->id
            ]);
        });

        $goalsGuest = $request->goalsGuest;
        collect($goalsGuest)->each(function($player) use ($scoreboard) {
            Goal::create([
                'player_id' => $player['id'],
                'team_id'   => $player['team_id'],
                'scoreboard_id' => $scoreboard->id
            ]);
        });
    }

    public function calculate($request)
    {
        $official = Scoreboard::where('game_id', $request->game['id'])->where('type', 'official')->first();

        $bets = Scoreboard::where('game_id', $request->game['id'])->where('type', 'bet')->get();

        collect($bets)->each(function($bet) use ($official) {
            $score = 0;
            $report = [];

            $calc = $this->calculateScoreByScoreboard($bet, $official);
            $score += $calc['score'];
            $report['scoreboard'] = $calc['report'];

            $calc = $this->calculateScoreByGoals($bet->goals->toArray(), $official->goals->toArray());
            $score += $calc['score'];
            $report['goals'] = $calc['report'];

            $bet->update([
                'score' => $score,
                $report = json_encode($report)
            ]);

            $bet->game()->update([
                'status' => 'finished'
            ]);
        });

    }

    private function calculateScoreByGoals(array $betGoals, array $officialGoals)
    {
        $score = 0;
        $report = [];

        foreach ($betGoals as $betGoal) {
            $contiue = true;

            for ($i = 0; $i < count($officialGoals) && $contiue; $i++) {
                if($betGoal['player_id'] === $officialGoals[$i]['player_id'] && $officialGoals[$i]['player_id']){

                    $contiue = false;
                    $score += 0.5;
                    $officialGoals[$i]['player_id'] = null;

                    $player = Player::find($betGoal['player_id']) ?? '--';

                    $report[] = [
                        'score' => 0.5,
                        'message' => "Acertar gol marcado de {$player->name}"
                    ];
                }
            }
        }

        return [
            'score' => $score,
            'report' => $report
        ];
    }

    private function calculateScoreByScoreboard(Scoreboard $bet, Scoreboard $official){
        $score = 0;
        $report = [];

        if($bet->team_home_scoreboard === $official->team_home_scoreboard && $bet->team_guest_scoreboard === $official->team_guest_scoreboard){
            $score = $score + 3;
            $report['score'] = 3;
            $report['message'] = 'Acertar placar exato';
        } else if($bet->team_home_scoreboard > $bet->team_guest_scoreboard && $official->team_home_scoreboard > $official->team_guest_scoreboard){
            $score = $score + 1;
            $report['score'] = 1;
            $report['message'] = "Acertar vitória do time mandante";
        } else if($bet->team_home_scoreboard < $bet->team_guest_scoreboard && $official->team_home_scoreboard < $official->team_guest_scoreboard){
            $score = $score + 1;
            $report['score'] = 1;
            $report['message'] = "Acertar vitória do time visitante";
        } else if($bet->team_home_scoreboard == $bet->team_guest_scoreboard && $official->team_home_scoreboard == $official->team_guest_scoreboard){
            $score = $score + 1;
            $report['score'] = 1;
            $report['message'] = 'Acertar empate';
        }

        return [
            'score' => $score,
            'report' => $report
        ];
    }
}
