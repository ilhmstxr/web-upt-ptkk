<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use Illuminate\Support\Facades\DB;

trait BuildsLikertData
{
    protected function collectPertanyaanIds(?int $pelatihanId)
    {
        return JawabanUser::query()
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
                  ->join('tes as t', 't.id', '=', 'pr.tes_id')
                  ->where('t.pelatihan_id', $pelatihanId)
                  ->select('jawaban_user.pertanyaan_id');
            })
            ->pluck('jawaban_user.pertanyaan_id')
            ->unique()
            ->values();
    }

    protected function buildLikertMaps($pertanyaanIds): array
    {
        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $pertanyaanIds)
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        $opsi = OpsiJawaban::whereIn(
                'pertanyaan_id',
                collect($pertanyaanIds)->merge($pivot->values())->unique()->all()
            )
            ->orderBy('id')
            ->get(['id','pertanyaan_id','teks_opsi']);

        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) $map[$id] = $i + 1;
            return $map;
        });

        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id','teks_opsi')->mapWithKeys(
                fn($id,$teks) => [trim($teks) => $id]
            ));

        return [$pivot, $opsiIdToSkala, $opsiTextToId];
    }

    protected function normalizedAnswers(?int $pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId)
    {
        $jawaban = JawabanUser::query()
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
                  ->join('tes as t', 't.id', '=', 'pr.tes_id')
                  ->where('t.pelatihan_id', $pelatihanId)
                  ->select('jawaban_user.pertanyaan_id','jawaban_user.opsi_jawaban_id','jawaban_user.jawaban_teks');
            })
            ->whereIn('jawaban_user.pertanyaan_id', $pertanyaanIds)
            ->get();

        return $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
            $pid = (int) $j->pertanyaan_id;
            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim($j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;

            if ($skala !== null) $skala = max(1, min(4, (int) $skala)); // clamp 1..4

            return [
                'pertanyaan_id'   => $pid,
                'opsi_jawaban_id' => $opsiId ? (int) $opsiId : null,
                'skala'           => $skala,
            ];
        });
    }
}
