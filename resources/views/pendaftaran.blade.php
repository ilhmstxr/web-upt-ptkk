<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Pelatihan - UPT PTKK Jatim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-group.has-error input,
        .form-group.has-error select,
        .form-group.has-error textarea {
            border-color: #ef4444; /* Red-500 */
        }
        .form-group .error-message {
            color: #ef4444;
            font-size: 0.875rem; /* 14px */
            margin-top: 0.5rem; /* 8px */
        }
    </style>
</head>
<body class="bg-slate-100">
    <div class="container mx-auto max-w-6xl p-4 md:p-8">
        <header class="flex items-center gap-4 mb-8 bg-white p-5 rounded-xl shadow-sm">
            <div class="logo w-16 h-16 rounded-full overflow-hidden flex-shrink-0">
                <img src="https://i.ibb.co/tZc3Bv1/logo-upt-ptkk.png" alt="Logo UPT PTKK" class="w-full h-full object-contain" />
            </div>
            <h1 class="text-xl md:text-2xl font-semibold text-slate-800">
                Pendaftaran Pelatihan<br>UPT Pengembangan dan Pendidikan Kejuruan
            </h1>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <aside class="lg:col-span-4 xl:col-span-3">
                <div class="bg-white p-6 rounded-xl shadow-sm sticky top-8">
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Langkah Pendaftaran</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm {{ $currentStep == 1 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500' }}">1</div>
                            <span class="font-medium {{ $currentStep == 1 ? 'text-slate-800' : 'text-slate-500' }}">Biodata Diri</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm {{ $currentStep == 2 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500' }}">2</div>
                            <span class="font-medium {{ $currentStep == 2 ? 'text-slate-800' : 'text-slate-500' }}">Data Instansi</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm {{ $currentStep == 3 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500' }}">3</div>
                            <span class="font-medium {{ $currentStep == 3 ? 'text-slate-800' : 'text-slate-500' }}">Lampiran Berkas</span>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="lg:col-span-8 xl:col-span-9">
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm">
                    <form method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="current_step" value="{{ $currentStep }}">

                        {{-- STEP 1: BIODATA DIRI --}}
                        @if ($currentStep == 1)
                            <h2 class="text-2xl font-bold text-slate-800 mb-6">Langkah 1: Biodata Diri</h2>
                            <div class="space-y-4">
                                <div class="form-group @error('pelatihan_id') has-error @enderror">
                                    <label for="pelatihan_id" class="block text-sm font-medium text-slate-700">Pilih Pelatihan</label>
                                    <select id="pelatihan_id" name="pelatihan_id" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Silakan Pilih --</option>
                                        @foreach ($pelatihans as $pelatihan)
                                            <option value="{{ $pelatihan->id }}" {{ old('pelatihan_id', $formData['pelatihan_id'] ?? '') == $pelatihan->id ? 'selected' : '' }}>{{ $pelatihan->nama_pelatihan }}</option>
                                        @endforeach
                                    </select>
                                    @error('pelatihan_id')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('nama') has-error @enderror">
                                        <label for="nama" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" value="{{ old('nama', $formData['nama'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('nama')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('nik') has-error @enderror">
                                        <label for="nik" class="block text-sm font-medium text-slate-700">NIK</label>
                                        <input type="text" name="nik" id="nik" value="{{ old('nik', $formData['nik'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('nik')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <!-- Tambahkan field biodata diri lainnya di sini -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('tempat_lahir') has-error @enderror">
                                        <label for="tempat_lahir" class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $formData['tempat_lahir'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('tempat_lahir')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('tanggal_lahir') has-error @enderror">
                                        <label for="tanggal_lahir" class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $formData['tanggal_lahir'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('tanggal_lahir')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('jenis_kelamin') has-error @enderror">
                                        <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                            <option value="Laki-laki" {{ old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('agama') has-error @enderror">
                                        <label for="agama" class="block text-sm font-medium text-slate-700">Agama</label>
                                        <input type="text" name="agama" id="agama" value="{{ old('agama', $formData['agama'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('agama')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="form-group @error('alamat') has-error @enderror">
                                    <label for="alamat" class="block text-sm font-medium text-slate-700">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="3" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">{{ old('alamat', $formData['alamat'] ?? '') }}</textarea>
                                    @error('alamat')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     <div class="form-group @error('no_hp') has-error @enderror">
                                        <label for="no_hp" class="block text-sm font-medium text-slate-700">No. HP</label>
                                        <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp', $formData['no_hp'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('no_hp')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('email') has-error @enderror">
                                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $formData['email'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('email')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- STEP 2: DATA INSTANSI --}}
                        @if ($currentStep == 2)
                            <h2 class="text-2xl font-bold text-slate-800 mb-6">Langkah 2: Data Instansi</h2>
                            <div class="space-y-4">
                                <div class="form-group @error('asal_instansi') has-error @enderror">
                                    <label for="asal_instansi" class="block text-sm font-medium text-slate-700">Asal Sekolah / Instansi</label>
                                    <input type="text" name="asal_instansi" id="asal_instansi" value="{{ old('asal_instansi', $formData['asal_instansi'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                    @error('asal_instansi')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group @error('alamat_instansi') has-error @enderror">
                                    <label for="alamat_instansi" class="block text-sm font-medium text-slate-700">Alamat Instansi</label>
                                    <textarea name="alamat_instansi" id="alamat_instansi" rows="3" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">{{ old('alamat_instansi', $formData['alamat_instansi'] ?? '') }}</textarea>
                                    @error('alamat_instansi')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('bidang_keahlian') has-error @enderror">
                                        <label for="bidang_keahlian" class="block text-sm font-medium text-slate-700">Bidang Keahlian</label>
                                        <input type="text" name="bidang_keahlian" id="bidang_keahlian" value="{{ old('bidang_keahlian', $formData['bidang_keahlian'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('bidang_keahlian')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('kelas') has-error @enderror">
                                        <label for="kelas" class="block text-sm font-medium text-slate-700">Kelas</label>
                                        <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $formData['kelas'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('kelas')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                 <div class="form-group @error('cabang_dinas_wilayah') has-error @enderror">
                                    <label for="cabang_dinas_wilayah" class="block text-sm font-medium text-slate-700">Cabang Dinas Wilayah</label>
                                    <input type="text" name="cabang_dinas_wilayah" id="cabang_dinas_wilayah" value="{{ old('cabang_dinas_wilayah', $formData['cabang_dinas_wilayah'] ?? '') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                    @error('cabang_dinas_wilayah')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        @endif

                        {{-- STEP 3: LAMPIRAN --}}
                        @if ($currentStep == 3)
                            <h2 class="text-2xl font-bold text-slate-800 mb-6">Langkah 3: Lampiran Berkas</h2>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('no_ktp') has-error @enderror">
                                        <label for="no_ktp" class="block text-sm font-medium text-slate-700">No. KTP</label>
                                        <input type="text" name="no_ktp" id="no_ktp" value="{{ old('no_ktp') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('no_ktp')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('no_surat_tugas') has-error @enderror">
                                        <label for="no_surat_tugas" class="block text-sm font-medium text-slate-700">No. Surat Tugas</label>
                                        <input type="text" name="no_surat_tugas" id="no_surat_tugas" value="{{ old('no_surat_tugas') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                        @error('no_surat_tugas')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group @error('pas_foto') has-error @enderror">
                                        <label for="pas_foto" class="block text-sm font-medium text-slate-700">Pas Foto (3x4)</label>
                                        <input type="file" name="pas_foto" id="pas_foto" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('pas_foto')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('fc_ktp') has-error @enderror">
                                        <label for="fc_ktp" class="block text-sm font-medium text-slate-700">Scan KTP</label>
                                        <input type="file" name="fc_ktp" id="fc_ktp" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('fc_ktp')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('fc_ijazah') has-error @enderror">
                                        <label for="fc_ijazah" class="block text-sm font-medium text-slate-700">Scan Ijazah</label>
                                        <input type="file" name="fc_ijazah" id="fc_ijazah" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('fc_ijazah')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('fc_surat_tugas') has-error @enderror">
                                        <label for="fc_surat_tugas" class="block text-sm font-medium text-slate-700">Scan Surat Tugas</label>
                                        <input type="file" name="fc_surat_tugas" id="fc_surat_tugas" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('fc_surat_tugas')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="form-group @error('fc_surat_sehat') has-error @enderror">
                                        <label for="fc_surat_sehat" class="block text-sm font-medium text-slate-700">Scan Surat Sehat</label>
                                        <input type="file" name="fc_surat_sehat" id="fc_surat_sehat" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('fc_surat_sehat')<p class="error-message">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 flex justify-end">
                            @if ($currentStep < 3)
                                <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Selanjutnya</button>
                            @else
                                <button type="submit" class="rounded-md bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">Kirim Pendaftaran</button>
                            @endif
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
