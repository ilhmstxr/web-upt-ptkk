<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use App\Models\Pertanyaan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait BuildsLikertData
{
    /** Ambil pertanyaan_id berbasis pelatihan → tes(survei) → pertanyaan(skala_likert). */
    protected function collectPertanyaanIds(?int $pelatihanId): Collection
    {
        $q = JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'jawaban_user.pertanyaan_id')
            ->where('t.tipe', 'survei')
            ->where('p.tipe_jawaban', 'skala_likert');

        if ($pelatihanId) {
            $q->where('t.pelatihan_id', $pelatihanId);
        }

        return $q->distinct()
            ->pluck('jawaban_user.pertanyaan_id')
            ->values();
    }

    /** Normalisasi input apa pun menjadi daftar integer pertanyaan_id unik. */
    protected function normalizePertanyaanIds(mixed $input): Collection
    {
        return collect($input)
            ->flatten(6)
            ->map(function ($v) {
                if ($v instanceof Pertanyaan) return $v->id;
                if (is_array($v) && array_key_exists('id', $v)) return $v['id'];
                return $v;
            })
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();
    }

    protected function buildLikertMaps($pertanyaanIds): array
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty()) {
            return [collect(), collect(), collect()];
        }

        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $ids->all())
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        $opsi = OpsiJawaban::whereIn(
            'pertanyaan_id',
            $ids->merge($pivot->values())->unique()->all()
        )
            ->orderBy('id')
            ->get(['id', 'pertanyaan_id', 'teks_opsi']);

        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) {
                $map[$id] = $i + 1; // urutan opsi → skala 1..n
            }
            return $map;
        });

        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
                fn($id, $teks) => [trim($teks) => $id]
            ));

        return [$pivot, $opsiIdToSkala, $opsiTextToId];
    }

    protected function normalizedAnswers(
        ?int $pelatihanId,
        $pertanyaanIds,
        $pivot,
        $opsiIdToSkala,
        $opsiTextToId
    ) {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty()) return collect();

        $jawaban = JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->whereIn('jawaban_user.pertanyaan_id', $ids->all())
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->select([
                'jawaban_user.pertanyaan_id',
                'jawaban_user.opsi_jawaban_id',
                'jawaban_user.jawaban_teks',
            ])
            ->get();

        return $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
            $pid = (int) $j->pertanyaan_id;

            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim((string) $j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;
            if ($skala !== null) {
                $skala = max(1, min(4, (int) $skala)); // clamp 1..4
            }

            return [
                'pertanyaan_id'   => $pid,
                'opsi_jawaban_id' => $opsiId ? (int) $opsiId : null,
                'skala'           => $skala,
            ];
        });
    }
}
