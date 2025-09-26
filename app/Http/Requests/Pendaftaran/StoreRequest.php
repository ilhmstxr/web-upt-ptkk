<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\{Pendaftaran, Peserta, Pelatihan, Instansi, Bidang};
use App\Rules\{UniquePendaftaranPerPesertaPelatihan, BidangBelongsToInstansi};

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Jika pakai Policy: return $this->user()?->can('create', Pendaftaran::class) ?? false;
        return true;
    }

    public function rules(): array
    {
        return [
            'peserta_id'      => ['required', 'integer', 'exists:peserta,id'],
            'pelatihan_id'    => ['required', 'integer', 'exists:pelatihan,id', new UniquePendaftaranPerPesertaPelatihan($this->input('peserta_id'))],
            'instansi_id'     => ['required', 'integer', 'exists:instansi,id'],
            'bidang_id'       => [
                'nullable',
                'integer',
                'exists:bidang,id',
                new BidangBelongsToInstansi($this->input('instansi_id')),
            ],
            'operator_user_id'=> ['nullable', 'integer', 'exists:users,id'],
            // status awal biasanya dikunci di service; kalau mau kirim:
            'status'          => ['nullable', Rule::in(['draft','submitted','verified','approved','rejected'])],
            // nomor_registrasi biasanya di-generate otomatis (observer/service). Jika diperbolehkan input manual:
            'nomor_registrasi'=> ['nullable', 'string', 'max:50', 'unique:pendaftaran,nomor_registrasi'],
            // Lampiran saat create (opsional); jika ada endpoint khusus, hapus aturan ini.
            'lampiran'        => ['nullable', 'array'],
            'lampiran.*.file' => ['required_with:lampiran', 'file', 'max:5120'], // 5MB
            'lampiran.*.tipe' => ['required_with:lampiran', Rule::in(['KTP','IJAZAH','SERTIFIKAT','LAINNYA'])],
            'catatan'         => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'pelatihan_id.exists' => 'Pelatihan tidak ditemukan.',
            'peserta_id.exists'   => 'Peserta tidak ditemukan.',
            'instansi_id.exists'  => 'Instansi tidak ditemukan.',
            'bidang_id.exists'    => 'Bidang tidak ditemukan.',
            'nomor_registrasi.unique' => 'Nomor registrasi sudah dipakai.',
        ];
    }

    public function attributes(): array
    {
        return [
            'peserta_id'   => 'peserta',
            'pelatihan_id' => 'pelatihan',
            'instansi_id'  => 'instansi',
            'bidang_id'    => 'bidang',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'peserta_id'       => $this->toNullableInt('peserta_id'),
            'pelatihan_id'     => $this->toNullableInt('pelatihan_id'),
            'instansi_id'      => $this->toNullableInt('instansi_id'),
            'bidang_id'        => $this->toNullableInt('bidang_id'),
            'operator_user_id' => $this->toNullableInt('operator_user_id'),
        ]);
    }

    private function toNullableInt($key): ?int
    {
        $v = $this->input($key);
        return ($v === null || $v === '' ? null : (int) $v);
    }
}
