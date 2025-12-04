<x-filament-panels::page>
    <div class="fi-main-bg bg-gray-50 dark:bg-gray-950 text-gray-950 dark:text-white font-sans h-full flex flex-col">
        <!-- Custom Styles -->
        <style>
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
            .modal-enter {
                animation: scaleIn 0.2s ease-out forwards;
            }
            @keyframes scaleIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
        </style>

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Direktori Instansi Mitra</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data kementerian, lembaga, pemda, atau perusahaan mitra pelatihan.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="alert('Fitur Export belum tersedia')"
                    class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-white/10 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-file-export text-green-600 dark:text-green-500"></i> Export Excel
                </button>
                <button wire:click="openModal"
                    class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-500 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-plus"></i> Tambah Instansi
                </button>
            </div>
        </div>

        <!-- SUMMARY STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg"><i class="fa-solid fa-building text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Total Instansi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total'] }}</p>
                </div>
            </div>
            <!-- Removed Status-based stats -->
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-lg"><i class="fa-solid fa-users-viewfinder text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total_peserta'] }}</p>
                </div>
            </div>
        </div>

        <!-- FILTER & SEARCH BAR -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-11">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Instansi</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            placeholder="Nama instansi, alamat, atau email..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                        <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Instansi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat & Kontak</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-white/10 text-sm">
                        @forelse($this->instansis as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-center">
                                    {{ $this->instansis->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold text-xs uppercase border border-gray-300 dark:border-gray-600">
                                            {{ substr($item->asal_instansi, 0, 2) }}
                                        </div>
                                        {{ $item->asal_instansi }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white line-clamp-1" title="{{ $item->alamat_instansi }}">{{ $item->alamat_instansi }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex gap-3 mt-1">
                                        <span title="Email"><i class="fa-solid fa-envelope mr-1"></i> {{ $item->email ?? '-' }}</span>
                                        <span title="Telepon"><i class="fa-solid fa-phone mr-1"></i> {{ $item->no_telepon ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editInstansi({{ $item->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button wire:click="deleteInstansi({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus data instansi ini?"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data instansi yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="bg-white dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-white/10 sm:px-6">
                {{ $this->instansis->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH/EDIT INSTANSI -->
    <x-filament::modal id="modalAddInstansi" width="md">
        <x-slot name="heading">
            {{ $isEditing ? 'Edit Data Instansi' : 'Tambah Instansi Baru' }}
        </x-slot>

        <form wire:submit="saveInstansi">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Instansi</label>
                    <input type="text" wire:model="newInstansi.name" required
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white"
                        placeholder="Contoh: Dinas Kominfo Jawa Barat">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="newInstansi.address" rows="2"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white"
                        placeholder="Jl. ..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Resmi</label>
                        <input type="email" wire:model="newInstansi.email"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telepon</label>
                        <input type="tel" wire:model="newInstansi.phone"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-primary-600 focus:border-primary-600 bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="$dispatch('close-modal', { id: 'modalAddInstansi' })"
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
