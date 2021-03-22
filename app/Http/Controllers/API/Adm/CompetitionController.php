<?php

namespace App\Http\Controllers\API\Adm;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competitons =  Competition::where('active', 1)->orderBy('name')->get();
        return $this->send([
            'competitons' => $competitons
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
            'name' => 'required',
            'season' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendErrorValidation($validator->errors());
        }

        $data['active'] = 1;

        $competiton = Competition::create($data);

        return $this->send([
            'competiton' => $competiton,
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Competition  $competiton
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competiton)
    {
        return $this->send([
            'competiton' => $competiton,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Competition  $competiton
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competition $competiton)
    {
        $competiton->update($request->all());

        return $this->send([
            'competiton' => $competiton,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Competition  $competiton
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition $competiton)
    {
        try {
            $competiton->delete();
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

    /**
     * Update the specified resource in storage.
     * Method: POST
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Competition  $competiton
     * @return \Illuminate\Http\Response
     */
    public function updateWithImage(Request $request, Competition $competiton)
    {
        $data = $request->all();

        $name_photo = $request->hasFile('photo') ? Carbon::now()->format('YmdHis') . $request->file('photo')->getClientOriginalName() : false;

        if($name_photo){
            $data['name_photo'] = $name_photo;

            if(!$request->file('photo')->storeAs('competiton', $name_photo)){
                return $this->sendError([
                    "message" => 'Upload image failed'
                ]);
            }

            if(Storage::exists('competiton/' . $competiton->name_photo)){
                Storage::delete('competiton/' . $competiton->name_photo);
            }
        }

        $competiton->update($data);

        return $this->send([
            'team' => $competiton,
            'message' => 'Updated successfully'
        ]);
    }
}
