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

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    /**
     * Ambil skor pre/post dari tabel percobaan untuk peserta & pelatihan ini.
     * tipe di percobaan kadang beda penamaan, jadi dibuat fleksibel.
     */
    protected function getPercobaanScore(int $pesertaId, int $pelatihanId, string $mode): float
    {
        // mode: 'pre' atau 'post'
        // cocokkan tipe yang umum:
        $candidates = $mode === 'pre'
            ? ['pre', 'pretest', 'pre-test', 'pre_test', 'pre tes', 'pre tes', 'tes awal']
            : ['post', 'posttest', 'post-test', 'post_test', 'post tes', 'tes akhir'];

        $q = Percobaan::query()
            ->where('peserta_id', $pesertaId)
            ->where('pelatihan_id', $pelatihanId)
            ->whereNotNull('skor');

        // tipe bisa kosong / tidak konsisten -> coba LIKE
        $q->where(function ($qq) use ($candidates) {
            foreach ($candidates as $t) {
                $qq->orWhere('tipe', 'like', '%' . $t . '%');
            }
        });

        // ambil yang paling terbaru
        $row = $q->latest('updated_at')->first();

        return $row ? (float) $row->skor : 0.0;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record  = $this->getRecord();
        $peserta = $record->peserta;

        // Map Pendaftaran attributes
        $data['kompetensi_keahlian'] = $record->kompetensi_pelatihan_id;

        // ====== AUTO nilai dari percobaan ======
        $pesertaId   = (int) ($record->peserta_id ?? 0);
        $pelatihanId = (int) ($record->pelatihan_id ?? 0);

        if ($pesertaId && $pelatihanId) {
            $data['nilai_pre_test']  = $this->getPercobaanScore($pesertaId, $pelatihanId, 'pre');
            $data['nilai_post_test'] = $this->getPercobaanScore($pesertaId, $pelatihanId, 'post');
        } else {
            $data['nilai_pre_test']  = (float) ($record->nilai_pre_test ?? 0);
            $data['nilai_post_test'] = (float) ($record->nilai_post_test ?? 0);
        }

        // praktek tetap dari pendaftaran (manual admin)
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
            // 1. Update Instansi
            if ($record->peserta && $record->peserta->instansi) {
                $record->peserta->instansi->update([
                    'asal_instansi'   => $data['asal_instansi'],
                    'alamat_instansi' => $data['alamat_instansi'],
                    'kota'            => $data['kota'],
                    'cabangDinas_id'  => $data['cabangDinas_id'],
                ]);
            }

            // 2. Update User (Email/Name)
            if ($record->peserta && $record->peserta->user) {
                $record->peserta->user->update([
                    'email' => $data['email'],
                    'name'  => $data['nama'],
                    'phone' => $data['no_hp'],
                ]);
            }

            // 3. Update Peserta
            if ($record->peserta) {
                $record->peserta->update([
                    'nama'          => $data['nama'],
                    'nik'           => $data['nik'],
                    'no_hp'         => $data['no_hp'],
                    'tempat_lahir'  => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama'         => $data['agama'],
                    'alamat'        => $data['alamat'],
                ]);

                // 4. Update Lampiran
                $lampiranData = [
                    'no_surat_tugas' => $data['nomor_surat_tugas'] ?? null,
                ];

                if (isset($data['fc_ktp'])) $lampiranData['fc_ktp'] = $data['fc_ktp'];
                if (isset($data['fc_ijazah'])) $lampiranData['fc_ijazah'] = $data['fc_ijazah'];
                if (isset($data['fc_surat_tugas'])) $lampiranData['fc_surat_tugas'] = $data['fc_surat_tugas'];
                if (isset($data['fc_surat_sehat'])) $lampiranData['fc_surat_sehat'] = $data['fc_surat_sehat'];
                if (isset($data['pas_foto'])) $lampiranData['pas_foto'] = $data['pas_foto'];

                LampiranPeserta::updateOrCreate(
                    ['peserta_id' => $record->peserta->id],
                    $lampiranData
                );
            }

            // ====== AUTO nilai dari percobaan (ambil ulang supaya selalu latest) ======
            $pesertaId   = (int) $record->peserta_id;
            $pelatihanId = (int) ($data['pelatihan_id'] ?? $record->pelatihan_id);

            $pre  = $this->getPercobaanScore($pesertaId, $pelatihanId, 'pre');
            $post = $this->getPercobaanScore($pesertaId, $pelatihanId, 'post');

            // praktek manual admin
            $praktek = (float) ($data['nilai_praktek'] ?? 0);

            // hitung rata-rata hanya dari nilai > 0
            $vals = array_filter([$pre, $post, $praktek], fn ($v) => is_numeric($v) && $v > 0);
            $rata = count($vals) ? round(array_sum($vals) / count($vals), 2) : 0;

            // 5. Update Pendaftaran + Nilai
            $record->update([
                'pelatihan_id'            => $pelatihanId,
                'kompetensi_pelatihan_id' => $data['kompetensi_keahlian'],
                'kelas'                   => $data['kelas'],
                'kompetensi_id'           => KompetensiPelatihan::find($data['kompetensi_keahlian'])?->kompetensi_id
                    ?? $record->kompetensi_id,

                // nilai tersimpan di pendaftaran
                'nilai_pre_test'          => $pre,
                'nilai_post_test'         => $post,
                'nilai_praktek'           => $praktek,
                'rata_rata'               => $rata,
            ]);

            // 6) Update statistik pelatihan (batch harian)
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
