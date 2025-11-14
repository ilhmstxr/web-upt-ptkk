{{-- resources/views/exports/reportJawbanSurvei.blade.php --}}
@php
    request()->merge(['print' => 1]);
@endphp

@include('filament.resources.jawaban-surveis.pages.report-page', [
    'pelatihanId' => $pelatihanId ?? request()->integer('pelatihanId'),
    'title' => $title ?? ($heading ?? 'Report Jawaban Survei'),
    'subtitle' => $subtitle ?? null,
    'print' => true,
])
