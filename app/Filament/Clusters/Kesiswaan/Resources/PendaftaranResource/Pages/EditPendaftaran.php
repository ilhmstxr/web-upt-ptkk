<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Models\KompetensiPelatihan;
use App\Models\LampiranPeserta;
use App\Models\Percobaan;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    /**
     * Ambil skor pre/post dari tabel percobaan untuk peserta & pelatihan ini.
     *
     * FIX utama: jangan sampai kosong karena data percobaan sering:
     * - pelatihan_id null / tidak konsisten
     * - waktu_selesai null (legacy), padahal skor ada
     *
     * Strategi:
     * 1) prioritas: peserta_id + pelatihan_id + tipe + skor + waktu_selesai
     * 2) fallback: peserta_id + tipe + skor + waktu_selesai (abaikan pelatihan_id)
     * 3) fallback terakhir (legacy): peserta_id + tipe + skor (abaikan waktu_selesai)
     */
    protected function getPercobaanScore(int $pesertaId, int $pelatihanId, string $mode): float
    {
        if ($pesertaId <= 0) {
            return 0.0;
        }

        $mode = strtolower(trim($mode));

        $candidates = $mode === 'pre'
            ? ['pre-test', 'pre_test', 'pretest', 'pre', 'tes awal', 'tes awal peserta']
            : ['post-test', 'post_test', 'posttest', 'post', 'tes akhir', 'tes akhir peserta'];

        // base query (peserta + tipe + skor not null)
        $base = Percobaan::query()
            ->where('peserta_id', $pesertaId)
            ->whereNotNull('skor')
            ->where(function ($qq) use ($candidates) {
                foreach ($candidates as $t) {
                    $qq->orWhere('tipe', 'like', '%' . $t . '%');
                }
            });

        // 1) PRIORITAS: match pelatihan_id + sudah selesai
        if ($pelatihanId > 0) {
            $row = (clone $base)
                ->where('pelatihan_id', $pelatihanId)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            if ($row) {
                return (float) $row->skor;
            }
        }

        // 2) FALLBACK: abaikan pelatihan_id, tapi tetap yang sudah selesai
        $row2 = (clone $base)
            ->whereNotNull('waktu_selesai')
            ->latest('waktu_selesai')
            ->first();

        if ($row2) {
            return (float) $row2->skor;
        }

        // 3) FALLBACK TERAKHIR (legacy): skor ada walau waktu_selesai null
        $row3 = $base->latest('updated_at')->first();

        return $row3 ? (float) $row3->skor : 0.0;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record  = $this->getRecord();
        $peserta = $record->peserta;

        // Map Pendaftaran attributes
        $data['kompetensi_keahlian'] = $record->kompetensi_pelatihan_id;

        // ===== AUTO NILAI DARI PERCOBAAN =====
        $pesertaId   = (int) ($record->peserta_id ?? 0);
        $pelatihanId = (int) ($record->pelatihan_id ?? 0);

        // ✅ selalu hitung dari percobaan biar tampil (tidak ngandelin nilai yang tersimpan)
        $data['nilai_pre_test']  = $this->getPercobaanScore($pesertaId, $pelatihanId, 'pre');
        $data['nilai_post_test'] = $this->getPercobaanScore($pesertaId, $pelatihanId, 'post');

        // ✅ input manual admin (INI YANG KAMU MAU)
        $data['nilai_praktek'] = (float) ($record->nilai_praktek ?? 0);

        // rata-rata tampil (akan dihitung ulang saat save)
        $data['rata_rata'] = (float) ($record->rata_rata ?? 0);

        if ($peserta) {
            // Map Peserta attributes
            $data['nama']          = $peserta->nama;
            $data['nik']           = $peserta->nik;
            $data['no_hp']         = $peserta->no_hp;
            $data['tempat_lahir']  = $peserta->tempat_lahir;
            $data['tanggal_lahir'] = $peserta->tanggal_lahir;
            $data['jenis_kelamin'] = $peserta->jenis_kelamin;
            $data['agama']         = $peserta->agama;
            $data['alamat']        = $peserta->alamat;

            // Map User email
            if ($peserta->user) {
                $data['email'] = $peserta->user->email;
            }

            // Map Instansi attributes
            if ($peserta->instansi) {
                $instansi = $peserta->instansi;
                $data['asal_instansi']   = $instansi->asal_instansi;
                $data['alamat_instansi'] = $instansi->alamat_instansi;
                $data['kota']            = $instansi->kota;
                $data['cabangDinas_id']  = $instansi->cabangDinas_id;
            }

            // Map Lampiran attributes
            if ($peserta->lampiran) {
                $lampiran = $peserta->lampiran;
                $data['fc_ktp']            = $lampiran->fc_ktp;
                $data['fc_ijazah']         = $lampiran->fc_ijazah;
                $data['fc_surat_tugas']    = $lampiran->fc_surat_tugas;
                $data['fc_surat_sehat']    = $lampiran->fc_surat_sehat;
                $data['pas_foto']          = $lampiran->pas_foto;
                $data['nomor_surat_tugas'] = $lampiran->no_surat_tugas;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $get = fn (string $key, $default = null) => $data[$key] ?? $default;

            // 1) Update Instansi
            if ($record->peserta && $record->peserta->instansi) {
                $record->peserta->instansi->update([
                    'asal_instansi'   => $get('asal_instansi', $record->peserta->instansi->asal_instansi),
                    'alamat_instansi' => $get('alamat_instansi', $record->peserta->instansi->alamat_instansi),
                    'kota'            => $get('kota', $record->peserta->instansi->kota),
                    'cabangDinas_id'  => $get('cabangDinas_id', $record->peserta->instansi->cabangDinas_id),
                ]);
            }

            // 2) Update User (Email/Name/Phone)
            if ($record->peserta && $record->peserta->user) {
                $record->peserta->user->update([
                    'email' => $get('email', $record->peserta->user->email),
                    'name'  => $get('nama', $record->peserta->user->name),
                    'phone' => $get('no_hp', $record->peserta->user->phone),
                ]);
            }

            // 3) Update Peserta
            if ($record->peserta) {
                $record->peserta->update([
                    'nama'          => $get('nama', $record->peserta->nama),
                    'nik'           => $get('nik', $record->peserta->nik),
                    'no_hp'         => $get('no_hp', $record->peserta->no_hp),
                    'tempat_lahir'  => $get('tempat_lahir', $record->peserta->tempat_lahir),
                    'tanggal_lahir' => $get('tanggal_lahir', $record->peserta->tanggal_lahir),
                    'jenis_kelamin' => $get('jenis_kelamin', $record->peserta->jenis_kelamin),
                    'agama'         => $get('agama', $record->peserta->agama),
                    'alamat'        => $get('alamat', $record->peserta->alamat),
                ]);

                // 4) Update Lampiran (updateOrCreate)
                $lampiranData = [
                    'no_surat_tugas' => $get('nomor_surat_tugas', null),
                ];

                foreach (['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'] as $f) {
                    if (array_key_exists($f, $data)) {
                        $lampiranData[$f] = $data[$f];
                    }
                }

                LampiranPeserta::updateOrCreate(
                    ['peserta_id' => $record->peserta->id],
                    $lampiranData
                );
            }

            // ===== AUTO nilai dari percobaan (assign) =====
            $pesertaId   = (int) ($record->peserta_id ?? 0);
            $pelatihanId = (int) ($get('pelatihan_id', $record->pelatihan_id) ?? 0);

            $pre  = $this->getPercobaanScore($pesertaId, $pelatihanId, 'pre');
            $post = $this->getPercobaanScore($pesertaId, $pelatihanId, 'post');

            // ✅ nilai_praktek input manual
            $praktek = (float) ($get('nilai_praktek', $record->nilai_praktek ?? 0) ?? 0);

            // hitung rata-rata hanya dari nilai > 0
            $vals = array_filter([$pre, $post, $praktek], fn ($v) => is_numeric($v) && (float) $v > 0);
            $rata = count($vals) ? round(array_sum($vals) / count($vals), 2) : 0;

            // kompetensi_id resolve dari kompetensi_keahlian (tetap)
            $kompetensiKeahlianId = (int) ($get('kompetensi_keahlian', $record->kompetensi_pelatihan_id) ?? 0);
            $kompetensiIdResolved = KompetensiPelatihan::find($kompetensiKeahlianId)?->kompetensi_id
                ?? $record->kompetensi_id;

            // 5) Update Pendaftaran + Nilai
            $record->update([
                'pelatihan_id'            => $pelatihanId ?: $record->pelatihan_id,
                'kompetensi_pelatihan_id' => $kompetensiKeahlianId ?: $record->kompetensi_pelatihan_id,
                'kelas'                   => $get('kelas', $record->kelas),
                'kompetensi_id'           => $kompetensiIdResolved,

                // ✅ ini yang kamu minta
                'nilai_pre_test'          => $pre,
                'nilai_post_test'         => $post,
                'nilai_praktek'           => $praktek,
                'rata_rata'               => $rata,
            ]);

            // 6) Update statistik pelatihan (tetap, optional guard)
            if (Schema::hasTable('statistik_pelatihan') && $pelatihanId > 0) {
                $batch = 'AUTO_DB_' . now()->format('Y_m_d');

                DB::statement("
                    INSERT INTO statistik_pelatihan
                    (batch, pelatihan_id, pre_avg, post_avg, praktek_avg, rata_avg, created_at, updated_at)
                    SELECT
                      ? AS batch,
                      pp.pelatihan_id,
                      COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0)  AS pre_avg,
                      COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) AS post_avg,
                      COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0)   AS praktek_avg,
                      COALESCE(ROUND(AVG(NULLIF(pp.rata_rata, 0)), 2), 0)       AS rata_avg,
                      NOW(), NOW()
                    FROM pendaftaran_pelatihan pp
                    WHERE pp.pelatihan_id = ?
                    GROUP BY pp.pelatihan_id
                    ON DUPLICATE KEY UPDATE
                      pre_avg = VALUES(pre_avg),
                      post_avg = VALUES(post_avg),
                      praktek_avg = VALUES(praktek_avg),
                      rata_avg = VALUES(rata_avg),
                      updated_at = NOW()
                ", [$batch, $pelatihanId]);
            }

            return $record;
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
