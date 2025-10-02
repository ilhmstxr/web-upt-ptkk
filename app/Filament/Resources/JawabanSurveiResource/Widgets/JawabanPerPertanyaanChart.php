<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanPerPertanyaanChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Skala Likert (Akumulatif 1–4)';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId = request()->get('pelatihanId');

        // 1) Kumpulkan pertanyaan_id yang muncul pada jawaban; filter pelatihan via join ke percobaan -> tes
        $pertanyaanIds = JawabanUser::query()
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
                    ->join('tes as t', 't.id', '=', 'pr.tes_id')
                    ->where('t.pelatihan_id', $pelatihanId)
                    ->select('jawaban_user.*');
            })
            ->pluck('jawaban_user.pertanyaan_id')
            ->unique()
            ->values();

        if ($pertanyaanIds->isEmpty()) {
            return [
                'labels' => ['Skala 1', 'Skala 2', 'Skala 3', 'Skala 4'],
                'datasets' => [[
                    'label' => 'Jumlah Jawaban',
                    'data' => [0, 0, 0, 0],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                ]],
            ];
        }

        // 2) Pivot pertanyaan -> template_pertanyaan (fallback sumber opsi)
        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $pertanyaanIds)
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        // 3) Ambil opsi untuk pertanyaan & template terkait
        $opsi = OpsiJawaban::whereIn(
            'pertanyaan_id',
            $pertanyaanIds->merge($pivot->values())->unique()->all()
        )
            ->orderBy('id') // ganti ke kolom 'urutan' bila tersedia
            ->get(['id', 'pertanyaan_id', 'teks_opsi']);

        // 3a) Peta {pertanyaan_id => [opsi_id => skala]}
        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) {
                $map[$id] = $i + 1; // 1..N
            }
            return $map;
        });

        // 3b) Peta {pertanyaan_id => [teks_opsi => opsi_id]}
        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
                fn($id, $teks) => [trim($teks) => $id]
            ));

        // 4) Ambil jawaban user untuk pertanyaan-pertanyaan tersebut, filter pelatihan via percobaan -> tes
        $jawaban = JawabanUser::query()
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
                    ->join('tes as t', 't.id', '=', 'pr.tes_id')
                    ->where('t.pelatihan_id', $pelatihanId)
                    ->select('jawaban_user.*');
            })
            ->whereIn('jawaban_user.pertanyaan_id', $pertanyaanIds)
            ->select('jawaban_user.pertanyaan_id', 'jawaban_user.opsi_jawaban_id', 'jawaban_user.jawaban_teks')
            ->get();

        // 5) Hitung akumulasi skala 1..4
        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

        foreach ($jawaban as $j) {
            $pid = (int)$j->pertanyaan_id;
            $source = !empty($opsiIdToSkala->get($pid)) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = $opsiTextToId->get($source, collect())->get(trim($j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;

            if ($skala && $skala >= 1 && $skala <= 4) {
                $counts[$skala]++;
            }
        }

        return [
            'labels' => ['Skala 1', 'Skala 2', 'Skala 3', 'Skala 4'],
            'datasets' => [[
                'label' => 'Jumlah Jawaban',
                'data' => [
                    $counts[1] ?? 0,
                    $counts[2] ?? 0,
                    $counts[3] ?? 0,
                    $counts[4] ?? 0,
                ],
                'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
