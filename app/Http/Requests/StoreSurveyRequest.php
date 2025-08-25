<?php

namespace App\Http\Requests;

use App\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $section = Survey::where('order', $this->route('order'))->firstOrFail();
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
