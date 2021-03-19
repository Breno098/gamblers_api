<?php

namespace App\Http\Controllers\Adm\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Stadium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StadiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stadia = Stadium::orderBy('name')->get();
        foreach ($stadia as &$stadium) {
            $stadium->country;
        }

        return $this->send([
            'stadia' => $stadia
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
        ]);

        if($validator->fails()){
            return $this->sendErrorValidation($validator->errors());
        }

        $stadium = Stadium::create($data);

        return $this->send([
            'stadium' => $stadium,
            'message' => 'Created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return \Illuminate\Http\Response
     */
    public function show(Stadium $stadium)
    {
        return $this->send([
            'stadium' => $stadium,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stadium $stadium)
    {
        $stadium->update($request->all());

        return $this->send([
            'stadium' => $stadium,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stadium $stadium)
    {
        try {
            $stadium->delete();
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
