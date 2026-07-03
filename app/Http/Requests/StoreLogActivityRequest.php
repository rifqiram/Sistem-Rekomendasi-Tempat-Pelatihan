<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_type' => 'required|string|max:100', // e.g. view_detail, enroll, click
            'training_center_id' => 'nullable|exists:training_centers,id',
            'pelatihan_id' => 'nullable|exists:tabel_pelatihan,id',
            'details' => 'nullable|string',
        ];
    }
}
