<x-filament-panels::page>
    <style>
        /* ====== FIX FULL WIDTH FILAMENT PAGE ====== */
        /* Container utama page Filament biasanya max-w-7xl / max-w-screen-xl */
        .fi-page,
        .fi-page-content,
        .fi-section,
        .fi-section-content {
            max-width: none !important;
            width: 100% !important;
        }

        /* Animations */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .tab-content.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Radio highlight (oranye penuh) */
        .correct-radio:checked + div {
            border-color: #ea580c;
            background-color: #fed7aa;
        }
        .correct-radio:checked + div .check-icon { display: block; }

        /* Tab button */
        .tab-btn { border-color: transparent; color: #7c2d12; }
        .tab-btn.active { border-color: #ea580c; color: #ea580c; }
    </style>

    <!-- TABS NAVIGATION -->
    <div
        class="rounded-xl shadow-sm border overflow-hidden w-full"
        style="background-color:#fdba74; border-color:#ea580c;"
    >
        <div class="px-6 overflow-x-auto" style="border-bottom:2px solid #ea580c;">
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
    <div id="content-setup" class="tab-content active mt-6 w-full">
        @php
            $submitAction = method_exists($this, 'create') ? 'create' : 'save';
        @endphp

        <x-filament-panels::form wire:submit="{{ $submitAction }}" class="space-y-6 w-full">
            {{ $this->form }}

            <x-filament-panels::form.actions :actions="$this->getFormActions()" />
        </x-filament-panels::form>
    </div>

    {{-- CONTENT 2: ANALISIS HASIL --}}
    <div id="content-analisis" class="tab-content mt-6 w-full">
        <div
            class="rounded-xl shadow-sm p-6 mb-6 w-full"
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
    </div>

    {{-- CONTENT 3: LIHAT NILAI --}}
    <div id="content-nilai" class="tab-content mt-6 w-full">
        <div
            class="rounded-xl shadow-sm overflow-hidden w-full"
            style="background-color:#fed7aa; border:2px solid #ea580c;"
        >
            <div class="p-6 flex justify-between items-center" style="border-bottom:2px solid #ea580c;">
                <h3 class="text-lg font-bold" style="color:#7c2d12;">
                    Rekapitulasi Nilai Siswa
                </h3>

                <a href="{{ route('tes.rekap.download', $this->record) }}"
                   class="text-sm hover:underline"
                   style="color:#ea580c;">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 inline mr-1" />
                    Download Excel
                </a>
            </div>

            {{-- WRAPPER TABLE FULL + SCROLL KALO SEMPIT --}}
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-max" style="background-color:#fdba74;">
                    <thead style="background-color:#fb923c;">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium uppercase text-left" style="color:#7c2d12;">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-medium uppercase text-center" style="color:#7c2d12;">Nilai Akhir</th>
                            <th class="px-6 py-3 text-xs font-medium uppercase text-center" style="color:#7c2d12;">Status</th>
                            <th class="px-6 py-3 text-xs font-medium uppercase text-right" style="color:#7c2d12;">Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rekapNilai as $row)
                            <tr style="border-top:2px solid #ea580c;">
                                <td class="px-6 py-4 font-medium" style="color:#7c2d12;">
                                    {{ $row['nama'] }}
                                </td>

                                <td class="px-6 py-4 text-center font-bold"
                                    style="color: {{ $row['skor'] >= 75 ? '#166534' : '#b45309' }};">
                                    {{ $row['skor'] }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($row['lulus'])
                                        <span style="background:#bbf7d0; color:#166534; padding:4px 8px; border-radius:9999px; font-size:12px;">
                                            Lulus
                                        </span>
                                    @else
                                        <span style="background:#fee2e2; color:#b91c1c; padding:4px 8px; border-radius:9999px; font-size:12px;">
                                            Remedial
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right" style="color:#7c2d12;">
                                    {{ !is_null($row['durasi']) ? $row['durasi'].' Menit' : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center" style="color:#7c2d12;">
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
