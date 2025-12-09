<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Pendaftaran;
use App\Rules\KompetensiBelongsToInstansi;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()?->can('update', $this->route('pendaftaran')) ?? false;
        return true;
    }

    public function rules(): array
    {
        $pendaftaran = $this->route('pendaftaran'); // pastikan route model binding
        $ignoreId = $pendaftaran?->id;

        return [
            'instansi_id'      => ['sometimes','required', 'integer', 'exists:instansi,id'],
            'kompetensi_id'        => [
                'sometimes','nullable','integer','exists:kompetensi,id',
                new KompetensiBelongsToInstansi($this->input('instansi_id', $pendaftaran?->instansi_id)),
            ],
            'operator_user_id' => ['sometimes','nullable','integer','exists:users,id'],
            'catatan'          => ['sometimes','nullable','string','max:1000'],

            // Jika kamu izinkan update nomor_registrasi (umumnya TIDAK disarankan):
            'nomor_registrasi' => ['sometimes','nullable','string','max:50', Rule::unique('pendaftaran','nomor_registrasi')->ignore($ignoreId)],
        ];
    }

    public function attributes(): array
    {
        return [
            'instansi_id' => 'instansi',
            'kompetensi_id'   => 'kompetensi',
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['instansi_id','kompetensi_id','operator_user_id'] as $key) {
            if ($this->has($key)) {
                $this->merge([$key => $this->toNullableInt($key)]);
            }
        }
    }

    private function toNullableInt($key): ?int
    {
        $v = $this->input($key);
        return ($v === null || $v === '' ? null : (int) $v);
    }
}
