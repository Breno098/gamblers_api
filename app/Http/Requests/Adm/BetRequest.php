<?php

namespace App\Http\Requests\Adm;

use Illuminate\Foundation\Http\FormRequest;

class BetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game' => 'required',
            'goalsHome' => 'nullable',
            'goalsGuest' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'game.required' => 'Informe o jogo.',
            'goalsHome.required' => 'Informe os gols do time mandante.',
            'goalsGuest.required' => 'Informe os gols do time visitante',
        ];
    }
}
