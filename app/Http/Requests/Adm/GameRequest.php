<?php

namespace App\Http\Requests\Adm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->type === 'adm';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_home_id' => 'required',
            'team_guest_id' => 'required',
            'stadium_id' => 'required',
            'competition_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'stage' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'team_home_id.required' => 'Informe o time mandante.',
            'team_guest_id.required' => 'Informe o time visitante.',
            'stadium_id.required' => 'Selecione o estádio.',
            'competition_id.required' => 'Selecione a competição.',
            'date.required' => 'Informe a data da partida.',
            'time.required' => 'Informe o horário da partida.',
            'stage.required' => 'Informe a etapa.'
        ];
    }
}
