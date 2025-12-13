<?php

namespace App\Http\Requests;

use App\Models\Survei;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $section = Survei::where('order', $this->route('order'))->firstOrFail();
        $questionIds = $section->pertanyaan()->pluck('id')->toArray();

        $rules = [
            'jawaban' => ['required', 'array'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ];

        foreach ($questionIds as $id) {
            $rules['jawaban.' . $id] = ['required', 'integer', 'between:1,5'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'jawaban.*.required' => 'Semua pertanyaan wajib diisi.',
        ];
    }
}
