{{-- resources/views/dashboard/pages/monev/monev.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Monev / Survei')
@section('page-title', 'Monev / Survei')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($tes as $t)
        @php
            // ✅ Monev tidak pakai kompetensi, hanya filter by pelatihan user
            $okPelatihan = !session('pelatihan_id') || $t->pelatihan_id == session('pelatihan_id');
            if(!$okPelatihan) continue;

            if (!in_array($t->tipe, ['survei','monev'])) continue;
        @endphp

        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover flex flex-col">
            <div class="flex justify-between items-start gap-4">
                <div class="min-w-0">
                    <h3 class="font-bold text-lg mb-2 truncate">{{ $t->judul }}</h3>
                    <p class="text-gray-600 mb-3">
                        {!! \Illuminate\Support\Str::limit($t->deskripsi ?? '-', 140) !!}
                    </p>

                    <div class="text-sm text-gray-500 space-y-1">
                        <div>Pelatihan:
                            <strong class="text-gray-700">
                                {{ $t->pelatihan->nama_pelatihan ?? '-' }}
                            </strong>
                        </div>

                        @if(!empty($t->durasi_menit))
                            <div>Estimasi: <strong>{{ $t->durasi_menit }} menit</strong></div>
                        @endif
                    </div>
                </div>

                <span class="text-xs px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 shrink-0">
                    Monev
                </span>
            </div>

            {{-- STATUS --}}
            <div class="mt-5 border-t pt-4">
                @if(!empty($t->__already_done))
                    <div class="flex flex-col gap-2">
                        <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg w-fit">
                            ✅ Sudah diisi
                        </div>

                        @if(!empty($t->__done_id))
                            <a href="{{ route('dashboard.monev.result', ['percobaan' => $t->__done_id]) }}"
                               class="text-sm text-blue-600 font-semibold underline w-fit">
                                Lihat hasil →
                            </a>
                        @endif
                    </div>

                @elseif(!empty($t->__running_id))
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">
                            ⏳ Survei sedang berjalan
                        </div>

                        <a href="{{ route('dashboard.monev.show', $t->id).'?percobaan='.$t->__running_id }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-semibold">
                            Lanjutkan
                        </a>
                    </div>

                @else
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-slate-600">Belum diisi</div>

                        <a href="{{ route('dashboard.monev.start', $t->id) }}"
                           class="inline-block px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition text-sm font-semibold">
                           Mulai
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @empty
        <div class="col-span-full p-6 bg-white rounded-xl shadow-sm">
            <p class="text-gray-500 mb-2">Tidak ada Monev / Survei untuk pelatihan Anda.</p>
            <p class="text-gray-500 text-sm">Silakan hubungi admin jika seharusnya ada survei.</p>
        </div>
    @endforelse

</div>
@endsection
