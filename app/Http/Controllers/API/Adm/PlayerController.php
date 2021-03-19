<?php

namespace App\Http\Controllers\Adm\API;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\PlayerGoalAgainstService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::whereNotNull('country_id')->orderBy('name')->get();
        foreach ($players as $player) {
            $player->team;
            $player->country;
        }
        return $this->send([
            'players' => $players
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
            'name' => 'required|max:255',
            'country_id' => 'required|int',
            'team_id' => 'required|int'
        ]);

        if($validator->fails()){
            return $this->sendErrorValidation($validator->errors());
        }

        $player = Player::create($data);

        return $this->send([
            'player' => $player,
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return $this->send([
            'player' => $player,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        $player->update($request->all());

        return $this->send([
            'player' => $player,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        try {
            $player->delete();
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
