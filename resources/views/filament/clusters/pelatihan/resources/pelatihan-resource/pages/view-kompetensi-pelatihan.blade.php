<x-filament-panels::page>
    <div x-data="{ activeTab: 'hasil' }" class="space-y-6">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    @click="activeTab = 'hasil'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'hasil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'hasil' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-chart-bar class="w-5 h-5 mr-2" :class="{ 'text-primary-500': activeTab === 'hasil', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'hasil' }" />
                    Hasil Tes & Survei
                </button>

                <button
                    @click="activeTab = 'peserta'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'peserta', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'peserta' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-users class="w-5 h-5 mr-2" :class="{ 'text-primary-500': activeTab === 'peserta', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'peserta' }" />
                    Peserta Kelas
                </button>

                <button
                    @click="activeTab = 'kurikulum'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'kurikulum', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'kurikulum' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-book-open class="w-5 h-5 mr-2" :class="{ 'text-primary-500': activeTab === 'kurikulum', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'kurikulum' }" />
                    Jadwal & Kurikulum
                </button>

            </nav>
        </div>

        <!-- TAB CONTENT: HASIL TES & SURVEI -->
        <div x-show="activeTab === 'hasil'" class="space-y-6">
            <!-- A. Statistik Utama (Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 1. PRETEST STATS -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Pre-Test</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Awal Peserta</p>
                        </div>
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">Selesai</span>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center border-4 border-blue-100 dark:border-blue-800">
                            <div class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-200">
                                <span class="text-2xl font-bold">{{ $this->statistik['pretest']['avg'] }}</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Tertinggi</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['pretest']['max'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Terendah</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['pretest']['min'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['pretest']['count'] ?? 0 }} Org</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. POSTTEST STATS -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Post-Test</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Akhir & Kelulusan</p>
                        </div>
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">Final</span>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-16 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center border-4 border-green-100 dark:border-green-800">
                            <div class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-200">
                                <span class="text-2xl font-bold">{{ $this->statistik['posttest']['avg'] }}</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Lulus</span>
                                <span class="font-bold text-green-600 dark:text-green-400">{{ $this->statistik['posttest']['lulus'] ?? 0 }} Org</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Remedial</span>
                                <span class="font-bold text-orange-600 dark:text-orange-400">{{ $this->statistik['posttest']['remedial'] ?? 0 }} Org</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-800 dark:text-white">
                                    {{ $this->statistik['posttest']['count'] ?? 0 }}/{{ $this->statistik['monev']['total_peserta'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. MONEV STATS (SURVEI) - Restored Simple Card -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Survei Monev</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Analisis Kepuasan</p>
                        </div>
                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-16 rounded-full bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center border-4 border-purple-100 dark:border-purple-800">
                            <!-- Simple Star Icon instead of value if 0 -->
                            <x-heroicon-o-star class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Total Responden</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['monev']['responden'] }} Org</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 mt-2">
                                <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $this->statistik['monev']['percentage'] }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 text-right">{{ number_format($this->statistik['monev']['percentage'], 0) }}% Partisipasi</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Daftar Tes & Kuesioner --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">Daftar Tes & Kuesioner</h3>

                    <a
                        href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('create', [
                            'pelatihan_id' => $record->id,
                            'kompetensi_id' => $kompetensiPelatihan->kompetensi_id,
                        ]) }}"
                        class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-colors flex items-center"
                    >
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" /> Buat Kuesioner Baru
                    </a>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($this->tes as $tes)
                        <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center gap-4 w-full md:w-1/3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $tes->judul }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($tes->created_at)->format('d M Y') }}
                                        • {{ $tes->pertanyaan_count ?? 0 }} Soal
                                        • {{ ucfirst($tes->tipe) }}
                                    </p>
                                </div>
                            </div>

                            <div class="w-full md:w-1/3 flex flex-col items-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Durasi</span>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">
                                    {{ $tes->durasi_menit }} Menit
                                </span>
                            </div>

                            <div class="w-full md:w-auto flex gap-2">
                                <a
                                    href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('edit', ['record' => $tes->id]) }}"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500"
                                >
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-gray-500 dark:text-gray-400">
                            Belum ada tes atau kuesioner yang dibuat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- =========================================================
            TAB: PESERTA (FULL FIX)
            - tombol tambah peserta pakai Action Filament
            - table: jika widget Filament -> pakai x-filament-widgets::widget
        ========================================================= --}}
        <div x-show="activeTab === 'peserta'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Daftar Peserta Kelas</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola data siswa yang terdaftar khusus di kelas ini.</p>
                </div>

                <div class="flex gap-2 w-full md:w-auto items-center">
                    <div class="relative w-full md:w-48">
                        <input
                            type="text"
                            placeholder="Cari nama..."
                            class="pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 w-full bg-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                        >
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" />
                    </div>

                    {{ $this->addParticipantAction }}
                </div>
            </div>

            {{-- ✅ FIX: jika ini Filament TableWidget --}}
            <x-filament-widgets::widget
                :widget="\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable::class"
                :record="$record"
                :data="['kompetensiPelatihanId' => $kompetensiPelatihan->id]"
            />
        </div>

        {{-- =========================================================
            TAB: KURIKULUM (placeholder kamu, tetap dipertahankan)
        ========================================================= --}}
        <div x-show="activeTab === 'kurikulum'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Jadwal Sesi Pembelajaran</h3>
                <button class="bg-primary-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-primary-700 flex items-center">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" />Tambah Sesi
                </button>
            </div>

            <div class="space-y-4">
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg w-16 h-16 shrink-0">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">SESI</span>
                        <span class="text-xl font-bold text-gray-800 dark:text-white">01</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 dark:text-white">Pengenalan Fundamental SEO</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                            <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                            10 Oktober 2024 • 09:00 - 12:00
                        </p>
                        <div class="flex gap-3 mt-2">
                            <span class="text-xs bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded border border-green-100 dark:border-green-800 flex items-center">
                                <x-heroicon-o-check class="w-3 h-3 mr-1" />Selesai
                            </span>
                            <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                <x-heroicon-o-document-text class="w-3 h-3 mr-1" />Materi.pdf
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
            TAB TAMBAHAN (agar fitur lama tidak hilang):
            - kompetensi: list semua sesi pelatihan (link FIX route)
        ========================================================= --}}
        <div x-show="activeTab === 'kompetensi'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($record->kompetensiPelatihan as $kp)
                    <a
                        href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-kompetensi', [
                            'record' => $record,
                            'kompetensi_pelatihan_id' => $kp->id,
                        ]) }}"
                        class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $kp->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Mentor: {{ $kp->instrukturs->pluck('nama')->join(', ') ?: 'Belum ditentukan' }}
                                </p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700">
                                {{ $kp->pendaftaranPelatihan()->count() }} Peserta
                            </span>
                        </div>

                        <div class="mt-4 text-right text-xs text-primary-600">
                            Lihat Detail →
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- =========================================================
            TAB: admin (instruktur global)
        ========================================================= --}}
        <div x-show="activeTab === 'admin'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Instruktur</h3>
                {{ $this->addInstructorAction }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($record->kompetensiPelatihan as $kp)
                    @foreach($kp->instrukturs as $instruktur)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $instruktur->nama }}</div>
                            <div class="text-xs text-blue-600 mt-1">
                                {{ $kp->kompetensi->nama_kompetensi ?? 'Kompetensi' }}
                            </div>
                            @if($instruktur->user)
                                <div class="text-xs mt-2 text-gray-500 dark:text-gray-400">
                                    {{ $instruktur->user->email }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- =========================================================
            TAB: materi (TableWidget FIX)
        ========================================================= --}}
        <div x-show="activeTab === 'materi'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <x-filament-widgets::widget
                :widget="\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\MateriPelatihanTable::class"
                :record="$record"
                :data="['kompetensiPelatihanId' => $kompetensiPelatihan->id]"
            />
        </div>

        {{-- =========================================================
            TAB: asrama (TableWidget FIX)
        ========================================================= --}}
        <div x-show="activeTab === 'asrama'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <x-filament-widgets::widget
                :widget="\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\AsramaPelatihanTable::class"
                :record="$record"
                :data="['kompetensiPelatihanId' => $kompetensiPelatihan->id]"
            />
        </div>

        {{-- =========================================================
            TAB: hasil (biarkan logika lama di ViewPelatihan)
            - kalau halaman ini memang ViewKompetensiPelatihan,
              bagian ini bisa kamu isi dengan link ke detail monev
        ========================================================= --}}
        <div x-show="activeTab === 'hasil'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">Ringkasan Hasil</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Ringkasan evaluasi & survei untuk kompetensi ini.
                    </p>
                </div>

                <a
                    class="text-sm bg-primary-600 text-white px-3 py-2 rounded-lg hover:bg-primary-700"
                    href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-monev-detail', [
                        'record' => $record,
                        'kompetensi_pelatihan_id' => $kompetensiPelatihan->id,
                    ]) }}"
                >
                    Lihat Detail Monev
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Avg Pretest</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->statistik['pretest']['avg'] ?? 0 }}</div>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Avg Posttest</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->statistik['posttest']['avg'] ?? 0 }}</div>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Avg Monev (0-5)</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->statistik['monev']['avg'] ?? 0 }}</div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
