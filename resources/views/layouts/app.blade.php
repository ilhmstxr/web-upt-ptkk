<div class="flex justify-center items-center py-8">
    <div class="bg-white rounded-3xl shadow-xl w-11/12 max-w-6xl p-8 lg:p-12 animate-fadeIn">
        <div class="flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-12">
            <!-- Sidebar / Step Indicator -->
            <div class="lg:w-1/4">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Pendaftaran Pelatihan</h2>
                <div class="flex lg:flex-col space-x-4 lg:space-x-0 lg:space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 {{ $currentStep == 1 ? 'bg-[#5c76c1] text-white' : ($currentStep > 1 ? 'bg-[#5c76c1]' : 'bg-gray-200 text-gray-500') }}">
                            @if($currentStep > 1)
                                <svg class="w-8 h-8 text-white absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            @else
                                <span class="font-semibold">1</span>
                            @endif
                        </div>
                        <div class="transition-all duration-300 {{ $currentStep >= 1 ? 'font-semibold text-gray-800' : 'text-gray-500' }}">
                            Biodata diri
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 {{ $currentStep == 2 ? 'bg-[#5c76c1] text-white' : ($currentStep > 2 ? 'bg-[#5c76c1]' : 'bg-gray-200 text-gray-500') }}">
                            @if($currentStep > 2)
                                <svg class="w-8 h-8 text-white absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            @else
                                <span class="font-semibold">2</span>
                            @endif
                        </div>
                        <div class="transition-all duration-300 {{ $currentStep >= 2 ? 'font-semibold text-gray-800' : 'text-gray-500' }}">
                            Biodata Sekolah
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 {{ $currentStep == 3 ? 'bg-[#5c76c1] text-white' : 'bg-gray-200 text-gray-500' }}">
                            <span class="font-semibold">3</span>
                        </div>
                        <div class="transition-all duration-300 {{ $currentStep >= 3 ? 'font-semibold text-gray-800' : 'text-gray-500' }}">
                            Lampiran
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Form Content -->
            <div class="lg:w-3/4">
                <form wire:submit.prevent="submit" class="flex flex-col h-full">
                    <!-- Step 1: Biodata Diri -->
                    @if ($currentStep == 1)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                        <div class="col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800">Biodata diri</h3>
                            <p class="text-sm text-gray-500">Mohon isi data diri Anda dengan lengkap dan benar.</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text" wire:model="nik" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('nik') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" wire:model="birth_place" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('birth_place') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" wire:model="birth_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('birth_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select wire:model="jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Agama</label>
                            <select wire:model="religion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('religion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-700 mb-1">Alamat Tempat Tinggal</label>
                            <textarea wire:model="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]"></textarea>
                            @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                            <input type="tel" wire:model="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Step 2: Biodata Sekolah -->
                    @if ($currentStep == 2)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                        <div class="col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800">Biodata Sekolah</h3>
                            <p class="text-sm text-gray-500">Mohon isi data sekolah asal Anda.</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Asal Lembaga Sekolah</label>
                            <input type="text" wire:model="school_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('school_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Alamat Sekolah</label>
                            <input type="text" wire:model="school_address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('school_address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Kompetensi/Kompetensi Keahlian</label>
                            <select wire:model="competence" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                <option value="">Pilih Kompetensi</option>
                                <option value="Tata Boga">Tata Boga</option>
                                <option value="Tata Busana">Tata Busana</option>
                                <option value="Tata Kecantikan">Tata Kecantikan</option>
                                <option value="Teknik Pendingin dan Tata Udara">Teknik Pendingin dan Tata Udara</option>
                            </select>
                            @error('competence') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <input type="text" wire:model="class" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('class') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-700 mb-1">Cabang Dinas Wilayah</label>
                            <select wire:model="dinas_branch" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                <option value="">Pilih Wilayah</option>
                                <option value="Wilayah I">Wilayah I</option>
                                <option value="Wilayah II">Wilayah II</option>
                                <option value="Wilayah III">Wilayah III</option>
                                <option value="Wilayah IV">Wilayah IV</option>
                                <option value="Wilayah V">Wilayah V</option>
                            </select>
                            @error('dinas_branch') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Step 3: Lampiran -->
                    @if ($currentStep == 3)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                        <div class="col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800">Lampiran</h3>
                            <p class="text-sm text-gray-500">Mohon unggah dokumen yang dibutuhkan.</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Fotocopy KTP</label>
                            <input type="file" wire:model="ktp_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            @error('ktp_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Fotocopy Ijazah Terakhir</label>
                            <input type="file" wire:model="ijazah_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            @error('ijazah_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Fotocopy Surat Tugas</label>
                            <input type="file" wire:model="surat_tugas_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            @error('surat_tugas_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Surat Keterangan Sehat</label>
                            <input type="file" wire:model="surat_sehat_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            @error('surat_sehat_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Pas Foto</label>
                            <input type="file" wire:model="pas_foto_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Pas Foto Formal Background Merah ukuran 3x4</p>
                            @error('pas_foto_path') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1">Nomor Surat Tugas</label>
                            <input type="text" wire:model="surat_tugas_nomor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            @error('surat_tugas_nomor') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-end space-x-4 self-end w-full">
                        @if ($currentStep > 1)
                            <button type="button" wire:click="previousStep" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-transform transform hover:scale-105">
                                Kembali
                            </button>
                        @endif
                        @if ($currentStep < 3)
                            <button type="button" wire:click="nextStep" class="px-6 py-3 bg-[#5c76c1] text-white font-semibold rounded-lg hover:bg-blue-600 transition-transform transform hover:scale-105">
                                Selanjutnya
                            </button>
                        @endif
                        @if ($currentStep == 3)
                            <button type="submit" class="px-6 py-3 bg-[#5c76c1] text-white font-semibold rounded-lg hover:bg-blue-600 transition-transform transform hover:scale-105">
                                Kirim
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
