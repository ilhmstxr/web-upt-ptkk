<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Models\Instansi;
use App\Models\Kompetensi;
use App\Models\KompetensiPelatihan;
use App\Models\LampiranPeserta;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // 2. Resolve Kompetensi & User
            // Get Kompetensi first to use its name in Instansi if needed
            $kp = KompetensiPelatihan::with('kompetensi')->findOrFail($data['kompetensi_pelatihan_id']);
            $realKompetensiId = $kp->kompetensi_id;
            $namaKompetensi = $kp->kompetensi->nama_kompetensi ?? '-';

            // 1. INSTRANSI
            $instansi = Instansi::firstOrCreate(
                [
                    'asal_instansi'   => $data['asal_instansi'],
                    'alamat_instansi' => $data['alamat_instansi'],
                    'kota'            => $data['kota'],
                    'kota_id'         => 0, // Fallback
                ],
                [
                    'kompetensi_keahlian' => $namaKompetensi,
                    'cabangDinas_id'      => $data['cabang_dinas_id'],
                ]
            );

            // 2. USER
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nama'],
                    'password' => Hash::make(Carbon::parse($data['tanggal_lahir'])->format('dmY')),
                    'phone'    => $data['no_hp'],
                    'role'     => 'peserta', // Ensure role
                ]
            );

            // 3. PESERTA
            // $kp and $realKompetensiId already resolved above

            $peserta = Peserta::updateOrCreate(
                ['nik' => $data['nik']],
                [
                    'pelatihan_id'  => $data['pelatihan_id'],
                    'instansi_id'   => $instansi->id,
                    'user_id'       => $user->id,
                    'kompetensi_id' => $realKompetensiId,
                    'nama'          => $data['nama'],
                    'tempat_lahir'  => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama'         => $data['agama'],
                    'alamat'        => $data['alamat'],
                    'no_hp'         => $data['no_hp'],
                ]
            );

            // 4. PENDAFTARAN & TOKEN
            $existingPendaftaran = PendaftaranPelatihan::where('pelatihan_id', $data['pelatihan_id'])
                ->where('kompetensi_id', $realKompetensiId)
                ->count();

            // Logic generate nomor (Simplified from Controller)
            $kodeKompetensi = $kp->kompetensi->kode ?? 'X';
            $nextUrut = $existingPendaftaran + 1;
            $nomorReg = sprintf('%d-%s-%03d', $data['pelatihan_id'], strtoupper($kodeKompetensi), $nextUrut);

            // Generate Assessment token
            $namaDepan = Str::upper(Str::slug(explode(' ', $data['nama'])[0] ?? '', ''));
            $namaClean = substr($namaDepan, 0, 5);
            $token = sprintf('%s-%s-%s-%s', $namaClean, $data['pelatihan_id'], date('Y'), Str::upper(Str::random(4)));

            $pendaftaran = PendaftaranPelatihan::create([
                'peserta_id'              => $peserta->id,
                'pelatihan_id'            => $data['pelatihan_id'],
                'kompetensi_pelatihan_id' => $data['kompetensi_pelatihan_id'],
                'kompetensi_id'           => $realKompetensiId,
                'kelas'                   => $data['kelas'],
                'status'                  => 'Belum Lulus',
                'status_pendaftaran'      => 'Diterima',
                'nomor_registrasi'        => $nomorReg,
                'tanggal_pendaftaran'     => now(),
                'assessment_token'        => $token,
                'token_expires_at'        => now()->addMonths(3),
                'nilai_pre_test'          => 0,
                'nilai_post_test'         => 0,
                'nilai_praktek'           => 0,
                'rata_rata'               => 0,
                'nilai_survey'            => 0,
            ]);

            // 5. LAMPIRAN
            // Handle file uploads (Filament handles upload, we just get paths from $data)
            $lampiranData = [
                'peserta_id' => $peserta->id,
                'no_surat_tugas' => $data['nomor_surat_tugas'] ?? null,
                'fc_ktp' => $data['fc_ktp'] ?? null,
                'fc_ijazah' => $data['fc_ijazah'] ?? null,
                'fc_surat_tugas' => $data['fc_surat_tugas'] ?? null,
                'fc_surat_sehat' => $data['fc_surat_sehat'] ?? null,
                'pas_foto' => $data['pas_foto'] ?? null,
            ];

            LampiranPeserta::updateOrCreate(
                ['peserta_id' => $peserta->id],
                $lampiranData
            );

            // 6. SEND EMAIL NOTIFICATION (Auto-Accept for Admin)
            try {
                $kamarAsrama = 'Belum Ditentukan';

                $emailData = [
                    'id_peserta' => $pendaftaran->nomor_registrasi,
                    'nama_peserta' => $peserta->nama,
                    'asal_lembaga' => $instansi->asal_instansi ?? '-',
                    'cabang_dinas' => $instansi->cabangDinas->nama ?? '-',
                    'kompetensi' => $kp->kompetensi->nama_kompetensi ?? '-',
                    'kamar_asrama' => $kamarAsrama,
                    'waktu_mulai' => '-',
                    'waktu_selesai' => '-',
                    'lokasi' => 'UPT PTKK Surabaya',
                    'alamat_lengkap' => 'Jl. Menur No. 123, Surabaya',
                ];

                $pelatihan = \App\Models\Pelatihan::find($data['pelatihan_id']);
                if ($pelatihan) {
                    $emailData['waktu_mulai'] = $pelatihan->tanggal_mulai ? $pelatihan->tanggal_mulai->translatedFormat('d F Y') : '-';
                    $emailData['waktu_selesai'] = $pelatihan->tanggal_selesai ? $pelatihan->tanggal_selesai->translatedFormat('d F Y') : '-';
                    $emailData['alamat_lengkap'] = $pelatihan->lokasi_text ?? $emailData['alamat_lengkap'];
                }

                if ($user->email) {
                    \App\Services\EmailService::send(
                        $user->email,
                        'Informasi Pendaftaran dan Undangan Pelatihan',
                        '',
                        $emailData,
                        'template_surat.informasi_kegiatan'
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send auto-accept email: ' . $e->getMessage());
            }

            return $pendaftaran;
        });
    }
}
