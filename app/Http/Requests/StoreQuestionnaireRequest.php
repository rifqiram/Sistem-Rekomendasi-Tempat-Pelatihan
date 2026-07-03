<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionnaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => 'required|array',
            'answers.bidang_diminati' => 'required|string',
            'answers.tingkat_keahlian' => 'required|string',
            'answers.metode_pelatihan' => 'required|string',
            'answers.jarak_maksimal' => 'required|numeric|min:0',
        ];
    }
}
