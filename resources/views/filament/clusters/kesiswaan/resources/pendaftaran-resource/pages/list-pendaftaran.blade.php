<x-filament-panels::page>
    <div class="fi-main-bg bg-gray-50 dark:bg-gray-950 text-gray-950 dark:text-white font-sans h-full flex flex-col">
        <!-- Custom Styles from HTML -->
        <style>
            /* Custom scrollbar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }

            /* Modal Animation */
            .modal-enter {
                animation: scaleIn 0.2s ease-out forwards;
            }

            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        </style>

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pendaftaran</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola seluruh data calon peserta dari semua program pelatihan
                    yang aktif.</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="exportExcel"
                    class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-white/10 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-file-export text-green-600 dark:text-green-500"></i> Export Excel
                </button>
                <button x-data x-on:click="$dispatch('open-modal', { id: 'modalAddParticipant' })"
                    class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-500 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-plus"></i> Input Manual
                </button>
            </div>
        </div>

        <!-- SUMMARY STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg"><i class="fa-solid fa-users text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total'] }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 rounded-lg"><i
                        class="fa-solid fa-hourglass-half text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Menunggu Seleksi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['pending'] }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 rounded-lg"><i
                        class="fa-solid fa-check-circle text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Diterima (Batch Ini)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['accepted'] }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg"><i class="fa-solid fa-ban text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Ditolak / Gugur</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <!-- FILTER & SEARCH BAR -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Peserta</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            placeholder="Nama, Email, atau NIK..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
                </div>

                <!-- Filter Pelatihan -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Program Pelatihan</label>
                    <select wire:model.live="filterProgram"
                        class="w-full py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Semua Pelatihan</option>
                        @foreach($this->programs as $program)
                            <option value="{{ $program }}">{{ $program }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kompetensi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kompetensi Keahlian</label>
                    <select wire:model.live="filterCompetency"
                        class="w-full py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Semua Kompetensi</option>
                        @foreach($this->competencies as $competency)
                            <option value="{{ $competency }}">{{ $competency }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pendaftaran</label>
                    <select wire:model.live="filterStatus"
                        class="w-full py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Seleksi">Menunggu Seleksi</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Tidak Lolos">Tidak Lolos</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="md:col-span-1">
                    <button wire:click="resetFilters"
                        class="w-full py-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700"
                        title="Reset Filter">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- MAIN DATA TABLE -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Info Peserta</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Target Pelatihan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kompetensi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Daftar</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-white/10 text-sm">
                        @forelse($this->pendaftarans as $p)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">

                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $p->peserta->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $p->peserta->user->email ?? '-' }}</div>
                                            <div class="text-[10px] text-gray-400 dark:text-gray-500">{{ $p->peserta->no_hp ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-normal max-w-xs">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium leading-snug">{{ $p->pelatihan->nama_pelatihan ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $p->pelatihan->jenis_pelatihan ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColor = 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200';
                                        $comp = $p->kompetensi->nama_kompetensi ?? $p->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-';
                                        if (str_contains($comp, 'SEO')) $badgeColor = 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-500/20';
                                        elseif (str_contains($comp, 'Ads')) $badgeColor = 'bg-pink-50 dark:bg-pink-500/10 text-pink-700 dark:text-pink-400 border-pink-100 dark:border-pink-500/20';
                                        elseif (str_contains($comp, 'Backend')) $badgeColor = 'bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 border-purple-100 dark:border-purple-500/20';
                                        elseif (str_contains($comp, 'Data')) $badgeColor = 'bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-400 border-teal-100 dark:border-teal-500/20';
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border {{ $badgeColor }}">
                                        {{ $comp }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $p->tanggal_pendaftaran ? $p->tanggal_pendaftaran->format('d M Y') : '-' }} <br>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $p->tanggal_pendaftaran ? $p->tanggal_pendaftaran->format('H:i') . ' WIB' : '' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($p->status_pendaftaran === 'Menunggu Seleksi' || $p->status_pendaftaran === 'Pending' || $p->status_pendaftaran === 'Verifikasi')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-500/10 text-yellow-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20">Menunggu Seleksi</span>
                                    @elseif($p->status_pendaftaran === 'Diterima')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-500/10 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-500/20">Diterima</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-500/10 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-500/20">{{ $p->status_pendaftaran }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($p->status_pendaftaran === 'Menunggu Seleksi' || $p->status_pendaftaran === 'Pending' || $p->status_pendaftaran === 'Verifikasi')
                                        <button wire:click="openDetail({{ $p->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3" title="Detail"><i class="fa-solid fa-eye"></i></button>
                                        <button wire:click="updateStatus({{ $p->id }}, 'Diterima')" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3" title="Terima"><i class="fa-solid fa-check"></i></button>
                                        <button wire:click="updateStatus({{ $p->id }}, 'Ditolak')" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Tolak"><i class="fa-solid fa-xmark"></i></button>
                                    @elseif($p->status_pendaftaran === 'Diterima')
                                        <button wire:click="openDetail({{ $p->id }})" class="text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400" title="Lihat Profil"><i class="fa-solid fa-ellipsis-vertical px-2"></i></button>
                                    @else
                                        <button onclick="alert('Alasan Penolakan: Tidak memenuhi kualifikasi administrasi.')" class="text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400" title="Lihat Alasan"><i class="fa-solid fa-circle-info px-2"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="bg-white dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-white/10 sm:px-6">
                {{ $this->pendaftarans->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL: INPUT MANUAL -->
    <x-filament::modal id="modalAddParticipant" width="md">
        <x-slot name="heading">
            Input Peserta Manual
        </x-slot>

        <form wire:submit="createParticipant">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" wire:model="newParticipant.name" required
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" wire:model="newParticipant.email" required
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telepon</label>
                    <input type="tel" wire:model="newParticipant.phone"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program Pelatihan</label>
                    <select wire:model="newParticipant.program_id" required
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Pilih Program</option>
                        @foreach($this->allPrograms as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kompetensi</label>
                    <select wire:model="newParticipant.competency_id" required
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Pilih Kompetensi</option>
                        @foreach($this->allCompetencies as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="$dispatch('close-modal', { id: 'modalAddParticipant' })"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-500 text-sm shadow-sm">Simpan
                    Data</button>
            </div>
        </form>
    </x-filament::modal>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</x-filament-panels::page>
