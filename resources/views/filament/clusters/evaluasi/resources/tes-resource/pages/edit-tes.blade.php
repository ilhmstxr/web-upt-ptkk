<x-filament-panels::page>
    <!-- Custom Styles from HTML -->
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

        .modal-enter {
            animation: scaleIn 0.2s ease-out forwards;
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Custom Radio */
        .correct-radio:checked+div {
            border-color: #10B981;
            background-color: #ECFDF5;
        }

        .correct-radio:checked+div .check-icon {
            display: block;
        }
    </style>

    <!-- TABS NAVIGATION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8">
                <button onclick="switchTab('setup')" id="tab-setup"
                    class="tab-btn active border-primary-600 text-primary-600 py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors whitespace-nowrap">
                    <x-heroicon-o-list-bullet class="w-5 h-5 mr-2" /> Setup Soal
                </button>
                <button onclick="switchTab('analisis')" id="tab-analisis"
                    class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                    <x-heroicon-o-chart-pie class="w-5 h-5 mr-2" /> Analisis Hasil
                </button>
                <button onclick="switchTab('nilai')" id="tab-nilai"
                    class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                    <x-heroicon-o-users class="w-5 h-5 mr-2" /> Lihat Nilai
                </button>
            </nav>
        </div>
    </div>

    <!-- CONTENT 1: SETUP SOAL -->
    <div id="content-setup" class="tab-content active">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- LEFT COLUMN: SETTINGS -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
                    <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Pengaturan Dasar</h3>
                    {{ $this->form }}
                    
                    <div class="mt-6 bg-blue-50 rounded-xl border border-blue-100 p-4">
                        <h4 class="font-bold text-blue-800 text-sm mb-2">
                            <x-heroicon-o-light-bulb class="w-4 h-4 inline mr-1" /> Tips
                        </h4>
                        <p class="text-xs text-blue-700 leading-relaxed mb-3">Import soal dari bank soal untuk menghemat waktu.</p>
                        <button type="button" class="w-full py-2 bg-white border border-blue-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100">
                            Buka Bank Soal
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: QUESTION BUILDER (Placeholder for now, keeping it simple) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Daftar Pertanyaan</h3>
                    <button type="button" class="text-sm text-primary-600 hover:underline font-medium">
                        <x-heroicon-o-eye class="w-4 h-4 inline mr-1" /> Preview Ujian
                    </button>
                </div>
                
                <!-- We can render the repeater here if we want, or custom JS builder -->
                <!-- For now, let's use the form's repeater which is rendered in $this->form above, 
                     but typically $this->form renders the WHOLE form. 
                     If we want to split it, we need to use $this->form->getComponent('section_name') or similar, 
                     or just rely on the standard form for now but wrapped in this layout.
                -->
                <!-- Actually, since we put {{ $this->form }} in the left column, it renders EVERYTHING there.
                     We should probably split the form schema in the Resource to have two sections, 
                     and render them separately here if possible, OR just use the standard form for the "Settings"
                     and build a custom UI for questions.
                -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 text-center text-gray-500">
                    <p>Form builder is rendered in the left column for now. Custom JS builder implementation requires more complex Livewire integration.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT 2: ANALISIS HASIL -->
    <div id="content-analisis" class="tab-content">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Analisis Butir Soal</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <p class="text-xs text-green-700 font-bold uppercase">Soal Mudah</p>
                    <p class="text-2xl font-bold text-green-800 mt-1">12 <span class="text-sm font-normal text-gray-500">Butir</span></p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                    <p class="text-xs text-yellow-700 font-bold uppercase">Soal Sedang</p>
                    <p class="text-2xl font-bold text-yellow-800 mt-1">5 <span class="text-sm font-normal text-gray-500">Butir</span></p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <p class="text-xs text-red-700 font-bold uppercase">Soal Sulit</p>
                    <p class="text-2xl font-bold text-red-800 mt-1">3 <span class="text-sm font-normal text-gray-500">Butir</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT 3: LIHAT NILAI -->
    <div id="content-nilai" class="tab-content">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Rekapitulasi Nilai Siswa</h3>
                <button class="text-sm text-blue-600 hover:underline">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 inline mr-1" /> Download Excel
                </button>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Waktu Pengerjaan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">Andi Dermawan</td>
                        <td class="px-6 py-4 text-center font-bold text-green-600">85</td>
                        <td class="px-6 py-4 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Lulus</span></td>
                        <td class="px-6 py-4 text-right text-gray-500">45 Menit</td>
                    </tr>
                    <!-- More rows... -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.getElementById('content-' + tabId).classList.add('active');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-primary-600', 'text-primary-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
            activeBtn.classList.add('active', 'border-primary-600', 'text-primary-600');
        }
    </script>
</x-filament-panels::page>
