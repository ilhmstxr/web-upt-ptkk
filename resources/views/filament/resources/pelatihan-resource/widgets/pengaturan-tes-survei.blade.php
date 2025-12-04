<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-semibold">Pengaturan Tes & Survei Pelatihan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
            <!-- Card Pre-Test -->
            <div class="p-4 bg-white/5 rounded-xl">
                <h3 class="font-bold">PRE-TEST</h3>
                <p>Jumlah Soal: {{ $this->jumlahSoalPreTest }}</p>
                <p>Status: <span class="font-semibold text-orange-400">BELUM ISI</span></p>
                <div class="mt-2">
                    <x-filament::button
                        tag="a"
                        href="#"
                        size="sm">
                        Atur Pertanyaan
                    </x-filament::button>
                </div>
            </div>

            <!-- Card Post-Test -->
            <div class="p-4 bg-white/5 rounded-xl">
                <h3 class="font-bold">POST-TEST</h3>
                <p>Jumlah Soal: {{ $this->jumlahSoalPostTest }}</p>
                <p>Status: <span class="font-semibold text-green-400">DIBUKA</span></p>
                 <div class="mt-2">
                    <x-filament::button
                        tag="a"
                        href="#"
                        size="sm">
                        Kelola Soal
                    </x-filament::button>
                </div>
            </div>

            <!-- Card Survei -->
            <div class="p-4 bg-white/5 rounded-xl">
                <h3 class="font-bold">SURVEI</h3>
                <p>Jumlah Pertanyaan: {{ $this->jumlahPertanyaanSurvei }}</p>
                <p>Status: <span class="font-semibold text-red-400">DITUTUP</span></p>
                 <div class="mt-2">
                    <x-filament::button
                        tag="a"
                        href="#"
                        size="sm">
                        Kelola Survei
                    </x-filament::button>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>