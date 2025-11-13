<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadLampiranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()?->can('uploadLampiran', $this->route('pendaftaran')) ?? false;
        return true;
    }

    public function rules(): array
    {
        return [
            'lampiran'         => ['required','array','min:1'],
            'lampiran.*.file'  => ['required','file','max:5120', 'mimes:pdf,jpg,jpeg,png'], // sesuaikan mimes
            'lampiran.*.tipe'  => ['required', Rule::in(['KTP','IJAZAH','SERTIFIKAT','LAINNYA'])],
            'lampiran.*.catatan' => ['nullable','string','max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'lampiran.*.file' => 'berkas lampiran',
            'lampiran.*.tipe' => 'tipe lampiran',
        ];
    }
}
