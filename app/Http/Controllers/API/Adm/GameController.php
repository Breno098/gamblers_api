<?php

namespace App\Http\Controllers\Adm\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::orderBy('date')->get();
        foreach ($games as &$game) {
            $game->country;
            $game->competition;
            $game->stadium->country;
            $game->teamHome->country;
            $game->teamGuest->country;
            $game->goals;
            $game->scoreboard = $game->scoreboard()->where('type', 'official')->first();
        }

        return $this->send([
            'games' => $games
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'team_home_id' => 'required',
            'team_guest_id' => 'required',
            'stadium_id' => 'required',
            'competition_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'stage' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendErrorValidation($validator->errors());
        }

        $data['status'] = 'open';
        $game = Game::create($data);

        return $this->send([
            'game' => $game,
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return $this->send([
            'game' => $game,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $game->update($request->all());

        return $this->send([
            'game' => $game,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        try {
            $game->delete();
            return $this->send([
                'message' => "Deleted successfully"
            ]);
        } catch(\Exception $e){
            return $this->sendError([
                'message' => $e->getCode() === '23000' ? "Dependency error" : $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }
}
