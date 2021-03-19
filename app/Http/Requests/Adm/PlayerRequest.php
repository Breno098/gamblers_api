<?php

namespace App\Http\Requests\Adm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PlayerRequest extends FormRequest
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
            'name' => 'required',
            'position' => 'required',
            'country_id' => 'required',
            'team_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome obrigatório.',
            'position.required' => 'Informe a posição.',
            'country_id.required' => 'Selecione o país.',
            'team_id.required' => 'Selecione o time.'
        ];
    }
}
