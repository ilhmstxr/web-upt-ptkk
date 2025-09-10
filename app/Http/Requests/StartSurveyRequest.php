<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function rules()
    {
        return [
            // Validasi bahwa 'peserta_id' harus ada dan merupakan ID yang valid di tabel 'pesertas'
            'peserta_id' => 'required|exists:pesertas,id'
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'peserta_id.required' => 'Anda harus memilih nama peserta.',
            'peserta_id.exists' => 'Peserta yang dipilih tidak valid.',
        ];
    }
}
