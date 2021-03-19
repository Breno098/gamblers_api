<?php

namespace App\Http\Controllers\Adm\API;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\PlayerGoalAgainstService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::orderBy('name')->get();
        foreach ($teams as $team) {
            $team->country;
        }
        return $this->send([
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PlayerGoalAgainstService $playerGoalAgainstService)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'country_id' => 'required|int'
        ]);

        if($validator->fails()){
            return $this->sendErrorValidation($validator->errors());
        }

        $team = Team::create($data);
        $team->competitions()->sync($request->competitions);

        $playerGoalAgainstService->create($team);

        return $this->send([
            'team' => $team,
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return $this->send([
            'team' => $team,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $team->update($request->all());

        return $this->send([
            'team' => $team,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        try {
            $competitions = $team->competitions;
            $team->competitions()->sync([]);
            $team->delete();

            return $this->send([
                'message' => "Deleted successfully"
            ]);
        } catch(\Exception $e){
            $team->competitions()->sync($competitions);

            return $this->sendError([
                'message' => $e->getCode() === '23000' ? "Dependency error" : $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }
}
