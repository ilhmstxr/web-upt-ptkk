<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Models\Instansi;
use App\Models\KompetensiPelatihan;
use App\Models\LampiranPeserta;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    /**
     * Kalau admin ingin langsung "diterima" + kirim email saat create.
     * Default false biar mengikuti alur verifikasi.
     */
    protected bool $autoAcceptAndSendEmail = false;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            /**
             * 0) Ambil kompetensi pelatihan
             */
            $kp = KompetensiPelatihan::with('kompetensi')->findOrFail($data['kompetensi_pelatihan_id']);
            $realKompetensiId = (int) $kp->kompetensi_id;
            $namaKompetensi   = $kp->kompetensi->nama_kompetensi ?? '-';
            $kodeKompetensi   = $kp->kompetensi->kode ?? 'X';

            /**
             * 1) INSTANSI
             * NOTE: kamu belum punya kota_id di form, jadi fallback 0 (sesuai versi kamu).
             * Kalau tabel instansi butuh FK valid, nanti kita ganti jadi select kota_id beneran.
             */
            $instansi = Instansi::firstOrCreate(
                [
                    'asal_instansi'   => $data['asal_instansi'],
                    'alamat_instansi' => $data['alamat_instansi'],
                    'kota'            => $data['kota'],
                    'kota_id'         => 0,
                ],
                [
                    // simpan nama kompetensi (bukan id) kalau kolom memang string
                    'kompetensi_keahlian' => $namaKompetensi,
                    'cabangDinas_id'     => $data['cabang_dinas_id'],
                ]
            );

            /**
             * 2) USER
             */
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nama'],
                    'password' => Hash::make(Carbon::parse($data['tanggal_lahir'])->format('dmY')),
                    'phone'    => $data['no_hp'],
                    'role'     => 'peserta',
                ]
            );

            /**
             * 3) PESERTA (updateOrCreate by NIK)
             */
            $peserta = Peserta::updateOrCreate(
                ['nik' => $data['nik']],
                [
                    'pelatihan_id'  => $data['pelatihan_id'],
                    'instansi_id'   => $instansi->id,
                    'user_id'       => $user->id,
                    'kompetensi_id' => $realKompetensiId, // pastikan kolom ini memang ada di tabel peserta kalau dipakai

                    'nama'          => $data['nama'],
                    'tempat_lahir'  => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama'         => $data['agama'],
                    'alamat'        => $data['alamat'],
                    'no_hp'         => $data['no_hp'],
                ]
            );

            /**
             * 4) PENDAFTARAN: nomor registrasi
             */
            $existing = PendaftaranPelatihan::query()
                ->where('pelatihan_id', $data['pelatihan_id'])
                ->where('kompetensi_id', $realKompetensiId)
                ->count();

            $nextUrut = $existing + 1;
            $nomorReg = sprintf('%d-%s-%03d', $data['pelatihan_id'], strtoupper($kodeKompetensi), $nextUrut);

            // ✅ enum lowercase sesuai migration
            $statusPendaftaran = $this->autoAcceptAndSendEmail ? 'diterima' : 'pending';

            $pendaftaran = PendaftaranPelatihan::create([
                'peserta_id'              => $peserta->id,
                'pelatihan_id'            => $data['pelatihan_id'],
                'kompetensi_pelatihan_id' => $data['kompetensi_pelatihan_id'],
                'kompetensi_id'           => $realKompetensiId,
                'kelas'                   => $data['kelas'],

                'status'                  => 'Belum Lulus',
                'status_pendaftaran'      => $statusPendaftaran,

                'nomor_registrasi'        => $nomorReg,
                'tanggal_pendaftaran'     => now(),

                'nilai_pre_test'          => 0,
                'nilai_post_test'         => 0,
                'nilai_praktek'           => 0,
                'rata_rata'               => 0,
                'nilai_survey'            => 0,
            ]);

            /**
             * 5) LAMPIRAN (updateOrCreate by peserta_id)
             */
            LampiranPeserta::updateOrCreate(
                ['peserta_id' => $peserta->id],
                [
                    'peserta_id'      => $peserta->id,
                    'no_surat_tugas'  => $data['nomor_surat_tugas'] ?? null,
                    'fc_ktp'          => $data['fc_ktp'] ?? null,
                    'fc_ijazah'       => $data['fc_ijazah'] ?? null,
                    'fc_surat_tugas'  => $data['fc_surat_tugas'] ?? null,
                    'fc_surat_sehat'  => $data['fc_surat_sehat'] ?? null,
                    'pas_foto'        => $data['pas_foto'] ?? null,
                ]
            );

            /**
             * 6) OPTIONAL: Auto-send email kalau langsung diterima
             */
            if ($this->autoAcceptAndSendEmail) {
                try {
                    $pelatihan = Pelatihan::find($data['pelatihan_id']);

                    $waktuMulai   = $pelatihan?->tanggal_mulai ? $pelatihan->tanggal_mulai->translatedFormat('d F Y') : '-';
                    $waktuSelesai = $pelatihan?->tanggal_selesai ? $pelatihan->tanggal_selesai->translatedFormat('d F Y') : '-';

                    $emailData = [
                        'id_peserta'     => $pendaftaran->nomor_registrasi,
                        'nama_peserta'   => $peserta->nama ?? '-',
                        'asal_lembaga'   => $instansi->asal_instansi ?? '-',
                        'cabang_dinas'   => $instansi?->cabangDinas?->nama ?? '-',
                        'kompetensi'     => $namaKompetensi,
                        'kamar_asrama'   => 'Belum Ditentukan',
                        'waktu_mulai'    => $waktuMulai,
                        'waktu_selesai'  => $waktuSelesai,
                        'lokasi'         => 'UPT PTKK Surabaya',
                        'alamat_lengkap' => $pelatihan?->lokasi_text ?? 'Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
                    ];

                    if (! empty($user->email)) {
                        \App\Services\EmailService::send(
                            $user->email,
                            'Informasi Pendaftaran dan Undangan Pelatihan',
                            '',
                            $emailData,
                            'template_surat.informasi_kegiatan'
                        );
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to send auto-accept email: ' . $e->getMessage());
                }
            }

            return $pendaftaran;
        });
    }
}
