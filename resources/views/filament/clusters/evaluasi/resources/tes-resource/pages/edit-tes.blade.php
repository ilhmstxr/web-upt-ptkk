<x-filament-panels::page>
    <!-- Custom Styles -->
    <style>
        /* Animations */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Radio highlight (oranye penuh) */
        .correct-radio:checked + div {
            border-color: #ea580c;
            background-color: #fed7aa;
        }

        .correct-radio:checked + div .check-icon {
            display: block;
        }

        /* Tab button */
        .tab-btn {
            border-color: transparent;
            color: #7c2d12;
        }

        .tab-btn.active {
            border-color: #ea580c;
            color: #ea580c;
        }
    </style>

    <!-- TABS NAVIGATION -->
    <div
        class="rounded-xl shadow-sm border overflow-hidden"
        style="background-color:#fdba74; border-color:#ea580c;"
    >
        <div
            class="px-6 overflow-x-auto"
            style="border-bottom:2px solid #ea580c;"
        >
            <nav class="-mb-px flex space-x-8">
                <button onclick="switchTab('setup')" id="tab-setup" type="button"
                    class="tab-btn active py-4 px-1 border-b-2 font-medium text-sm flex items-center whitespace-nowrap">
                    <x-heroicon-o-list-bullet class="w-5 h-5 mr-2" />
                    Setup Soal
                </button>

                <button onclick="switchTab('analisis')" id="tab-analisis" type="button"
                    class="tab-btn py-4 px-1 border-b-2 font-medium text-sm flex items-center whitespace-nowrap">
                    <x-heroicon-o-chart-pie class="w-5 h-5 mr-2" />
                    Analisis Hasil
                </button>

                <button onclick="switchTab('nilai')" id="tab-nilai" type="button"
                    class="tab-btn py-4 px-1 border-b-2 font-medium text-sm flex items-center whitespace-nowrap">
                    <x-heroicon-o-users class="w-5 h-5 mr-2" />
                    Lihat Nilai
                </button>
            </nav>
        </div>
    </div>

    {{-- CONTENT 1: SETUP SOAL --}}
    <div id="content-setup" class="tab-content active mt-6">
        @php
            $submitAction = method_exists($this, 'create') ? 'create' : 'save';
        @endphp

        <x-filament-panels::form wire:submit="{{ $submitAction }}" class="space-y-6">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </x-filament-panels::form>
    </div>

    {{-- CONTENT 2: ANALISIS HASIL --}}
<div id="content-analisis" class="tab-content mt-6">
    {{-- Kartu Mudah/Sedang/Sulit (punyamu tetap) --}}
    <div
        class="rounded-xl shadow-sm p-6 mb-6"
        style="background-color:#fed7aa; border:2px solid #ea580c;"
    >
        <h3 class="text-lg font-bold mb-4" style="color:#7c2d12;">
            Analisis Butir Soal
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div style="background-color:#bbf7d0; border:2px solid #16a34a;" class="p-4 rounded-lg">
                <p class="text-xs font-bold uppercase" style="color:#166534;">Soal Mudah</p>
                <p class="text-2xl font-bold mt-1" style="color:#166534;">
                    {{ $mudah }} <span class="text-sm font-normal">Butir</span>
                </p>
            </div>

            <div style="background-color:#fde68a; border:2px solid #ca8a04;" class="p-4 rounded-lg">
                <p class="text-xs font-bold uppercase" style="color:#854d0e;">Soal Sedang</p>
                <p class="text-2xl font-bold mt-1" style="color:#854d0e;">
                    {{ $sedang }} <span class="text-sm font-normal">Butir</span>
                </p>
            </div>

            <div style="background-color:#fecaca; border:2px solid #dc2626;" class="p-4 rounded-lg">
                <p class="text-xs font-bold uppercase" style="color:#7f1d1d;">Soal Sulit</p>
                <p class="text-2xl font-bold mt-1" style="color:#7f1d1d;">
                    {{ $sulit }} <span class="text-sm font-normal">Butir</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ✅ TAMBAHAN: Analisis Per Kategori --}}
    <div
        class="rounded-xl shadow-sm p-6 mb-6"
        style="background-color:#fff7ed; border:2px solid #ea580c;"
    >
        <h3 class="text-lg font-bold mb-4" style="color:#7c2d12;">
            Analisis Per Kategori
        </h3>

        @if (!empty($kategoriStats))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($kategoriStats as $k)
                    @php
                        $health = $k['health'] ?? 'aman';
                        $healthLabel = match ($health) {
                            'rawan_sulit' => 'Rawan: dominan sulit',
                            'rawan_mudah' => 'Rawan: dominan mudah',
                            'rawan_kunci' => 'Rawan: kunci lemah',
                            default => 'Aman',
                        };
                        $healthBg = match ($health) {
                            'rawan_sulit' => '#fee2e2',
                            'rawan_mudah' => '#fde68a',
                            'rawan_kunci' => '#fecaca',
                            default => '#bbf7d0',
                        };
                        $healthText = match ($health) {
                            'rawan_sulit' => '#991b1b',
                            'rawan_mudah' => '#854d0e',
                            'rawan_kunci' => '#7f1d1d',
                            default => '#166534',
                        };
                    @endphp

                    <div class="p-4 rounded-lg" style="border:2px solid #ea580c; background:#fed7aa;">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-bold" style="color:#7c2d12;">
                                    {{ $k['kategori'] }}
                                </div>
                                <div class="text-xs mt-1" style="color:#7c2d12;">
                                    {{ $k['jumlah_soal'] }} soal • Rata-rata tingkat benar: <b>{{ $k['avg_p'] }}%</b>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-bold"
                                  style="background:{{ $healthBg }}; color:{{ $healthText }};">
                                {{ $healthLabel }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <div class="p-3 rounded" style="background:#bbf7d0; border:2px solid #16a34a;">
                                <div class="text-[11px] font-bold" style="color:#166534;">Mudah</div>
                                <div class="text-xl font-bold" style="color:#166534;">{{ $k['mudah'] }}</div>
                            </div>
                            <div class="p-3 rounded" style="background:#fde68a; border:2px solid #ca8a04;">
                                <div class="text-[11px] font-bold" style="color:#854d0e;">Sedang</div>
                                <div class="text-xl font-bold" style="color:#854d0e;">{{ $k['sedang'] }}</div>
                            </div>
                            <div class="p-3 rounded" style="background:#fecaca; border:2px solid #dc2626;">
                                <div class="text-[11px] font-bold" style="color:#7f1d1d;">Sulit</div>
                                <div class="text-xl font-bold" style="color:#7f1d1d;">{{ $k['sulit'] }}</div>
                            </div>
                        </div>

                        @if (!empty($k['flag_counts']))
                            <div class="mt-3 text-xs" style="color:#7c2d12;">
                                <b>Catatan:</b>
                                @foreach ($k['flag_counts'] as $flag => $cnt)
                                    <span class="inline-block mr-2">• {{ $flag }} ({{ $cnt }})</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm" style="color:#7c2d12;">
                Belum ada data jawaban untuk dianalisis per kategori.
            </div>
        @endif
    </div>

    {{-- ✅ TAMBAHAN: Distribusi Jawaban (Top soal bermasalah) --}}
    <div
        class="rounded-xl shadow-sm p-6"
        style="background-color:#fff7ed; border:2px solid #ea580c;"
    >
        <h3 class="text-lg font-bold mb-4" style="color:#7c2d12;">
            Distribusi Jawaban (Soal Paling Bermasalah)
        </h3>

        @if (!empty($distribusiJawaban))
            <div class="space-y-6">
                @foreach ($distribusiJawaban as $d)
                    <div class="p-4 rounded-lg" style="background:#fed7aa; border:2px solid #ea580c;">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-bold" style="color:#7c2d12;">
                                    Kategori: {{ $d['kategori'] }} • Tingkat benar: {{ $d['p_index'] }}% • Total jawaban: {{ $d['total_jawaban'] }}
                                </div>
                                <div class="font-medium mt-2" style="color:#7c2d12;">
                                    “{{ \Illuminate\Support\Str::limit($d['teks'], 140) }}”
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @foreach ($d['opsi'] as $idx => $o)
                                @php
                                    $barBg = $o['is_kunci'] ? '#16a34a' : '#9ca3af';
                                    $rowBg = $o['is_kunci'] ? '#dcfce7' : '#f3f4f6';
                                    $rowBorder = $o['is_kunci'] ? '#16a34a' : '#d1d5db';
                                @endphp
                                <div class="p-3 rounded" style="background:{{ $rowBg }}; border:2px solid {{ $rowBorder }};">
                                    <div class="flex justify-between text-xs mb-1" style="color:#7c2d12;">
                                        <span>
                                            {{ chr(65 + $idx) }}.
                                            {!! $o['is_kunci'] ? '<b>(Kunci)</b>' : '' !!}
                                            {{ \Illuminate\Support\Str::limit(strip_tags($o['teks']), 120) }}
                                        </span>
                                        <span class="font-bold">{{ $o['percent'] }}%</span>
                                    </div>
                                    <div class="w-full rounded h-2" style="background:#e5e7eb;">
                                        <div class="h-2 rounded" style="width: {{ $o['percent'] }}%; background:{{ $barBg }};"></div>
                                    </div>
                                    <div class="text-[11px] mt-1" style="color:#7c2d12;">
                                        Dipilih: {{ $o['count'] }} orang
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm" style="color:#7c2d12;">
                Belum ada distribusi jawaban (minimal harus ada jawaban peserta).
            </div>
        @endif
    </div>
</div>

    {{-- CONTENT 3: LIHAT NILAI --}}
<div id="content-nilai" class="tab-content mt-6">
    <div class="rounded-xl shadow-sm overflow-hidden" style="background-color:#fed7aa; border:2px solid #ea580c;">
        <div class="p-6 flex items-center justify-between gap-4" style="border-bottom:2px solid #ea580c;">
            <h3 class="text-lg font-bold" style="color:#7c2d12;">Rekapitulasi Nilai Siswa</h3>

            <a
                href="{{ route('tes.rekap.download', $this->record) }}"
                class="text-sm hover:underline whitespace-nowrap"
                style="color:#ea580c;"
            >
                <x-heroicon-o-arrow-down-tray class="w-4 h-4 inline mr-1" />
                Download Excel
            </a>
        </div>

        {{-- wrapper biar table mentok, ga ada ruang kosong, dan aman kalau layar kecil --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full table-fixed border-collapse" style="background-color:#fdba74;">
                <thead style="background-color:#fb923c;">
                    <tr>
                        {{-- Nama: ambil ruang terbesar --}}
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase"
                            style="color:#7c2d12; width:55%;">
                            Nama Siswa
                        </th>

                        {{-- Nilai: fixed kecil, angka rata kanan --}}
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase"
                            style="color:#7c2d12; width:15%;">
                            Nilai Akhir
                        </th>

                        {{-- Status: fixed sedang, center --}}
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase"
                            style="color:#7c2d12; width:15%;">
                            Status
                        </th>

                        {{-- Waktu: fixed kecil, kanan --}}
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase"
                            style="color:#7c2d12; width:15%;">
                            Waktu
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rekapNilai as $row)
                        <tr style="border-top:2px solid #ea580c;">
                            {{-- Nama: jangan bikin kolom lain melebar --}}
                            <td class="px-4 py-3 font-medium truncate" style="color:#7c2d12;">
                                {{ $row['nama'] }}
                            </td>

                            {{-- Nilai: tabular-nums + right --}}
                            <td class="px-4 py-3 text-right font-bold tabular-nums"
                                style="color: {{ ($row['skor'] ?? 0) >= 75 ? '#166534' : '#b45309' }};">
                                {{ is_null($row['skor']) ? '-' : number_format((float) $row['skor'], 2) }}
                            </td>

                            {{-- Status: center, pill rapih --}}
                            <td class="px-4 py-3 text-center">
                                @if (!is_null($row['skor']) && $row['lulus'])
                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold"
                                          style="background:#bbf7d0; color:#166534;">
                                        Lulus
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold"
                                          style="background:#fee2e2; color:#b91c1c;">
                                        Remedial
                                    </span>
                                @endif
                            </td>

                            {{-- Durasi: right + tabular-nums --}}
                            <td class="px-4 py-3 text-right tabular-nums" style="color:#7c2d12;">
                                @if (!is_null($row['durasi']))
                                    {{ (int) $row['durasi'] }} Menit
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center" style="color:#7c2d12;">
                                Belum ada data pengerjaan tes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.getElementById('content-' + tabId).classList.add('active');

            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            const activeBtn = document.getElementById('tab-' + tabId);
            if (activeBtn) activeBtn.classList.add('active');
        }
    </script>
</x-filament-panels::page>
