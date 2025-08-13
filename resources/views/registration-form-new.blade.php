<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .step-inactive {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-4">
                <img src="https://placehold.co/40x40/5c76c1/ffffff?text=Logo" alt="Logo" class="rounded-full">
                <h1 class="text-xl font-bold text-gray-800">UPT PTKK</h1>
            </a>
            <nav>
                <a href="/pendaftaran" class="px-4 py-2 bg-[#5c76c1] text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-transform transform hover:scale-105">Daftar Sekarang</a>
            </nav>
        </div>
    </header>

    <!-- Main Form Container -->
    <div class="flex justify-center items-center py-8">
        <div class="bg-white rounded-3xl shadow-xl w-11/12 max-w-6xl p-8 lg:p-12 animate-fadeIn">
            <div class="flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-12">
                <!-- Sidebar / Step Indicator -->
                <div class="lg:w-1/4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Pendaftaran Pelatihan</h2>
                    <div class="flex lg:flex-col space-x-4 lg:space-x-0 lg:space-y-4">
                        <!-- Step 1 Indicator -->
                        <div class="flex items-center space-x-4">
                            <div id="step-indicator-1" class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 bg-[#5c76c1] text-white">
                                <span class="font-semibold">1</span>
                            </div>
                            <div id="step-label-1" class="font-semibold text-gray-800">
                                Biodata diri
                            </div>
                        </div>
                        <!-- Step 2 Indicator -->
                        <div class="flex items-center space-x-4">
                            <div id="step-indicator-2" class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 bg-gray-200 text-gray-500">
                                <span class="font-semibold">2</span>
                            </div>
                            <div id="step-label-2" class="text-gray-500">
                                Biodata Sekolah
                            </div>
                        </div>
                        <!-- Step 3 Indicator -->
                        <div class="flex items-center space-x-4">
                            <div id="step-indicator-3" class="relative w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 bg-gray-200 text-gray-500">
                                <span class="font-semibold">3</span>
                            </div>
                            <div id="step-label-3" class="text-gray-500">
                                Lampiran
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Form Content -->
                <div class="lg:w-3/4">
                    <form action="{{ route('pendaftaran.submit') }}" method="POST">
                        @csrf
                        <!-- Step 1: Biodata Diri -->
                        <div id="step-1" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                            <div class="col-span-2">
                                <h3 class="text-lg font-semibold text-gray-800">Biodata diri</h3>
                                <p class="text-sm text-gray-500">Mohon isi data diri Anda dengan lengkap dan benar.</p>
                            </div>
                            <!-- Form fields for step 1 -->
                            <div>
                                <label for="name" class="text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="nik" class="text-sm font-medium text-gray-700 mb-1">NIK</label>
                                <input type="text" id="nik" name="nik" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <!-- ... all other fields for step 1 ... -->
                            <div>
                                <label for="birth_place" class="text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                <input type="text" id="birth_place" name="birth_place" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="birth_date" class="text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" id="birth_date" name="birth_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="gender" class="text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select id="gender" name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="religion" class="text-sm font-medium text-gray-700 mb-1">Agama</label>
                                <select id="religion" name="religion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label for="address" class="text-sm font-medium text-gray-700 mb-1">Alamat Tempat Tinggal</label>
                                <textarea id="address" name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]"></textarea>
                            </div>
                            <div>
                                <label for="phone" class="text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="email" class="text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>

                        </div>

                        <!-- Step 2: Biodata Sekolah -->
                        <div id="step-2" class="step-inactive grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                            <div class="col-span-2">
                                <h3 class="text-lg font-semibold text-gray-800">Biodata Sekolah</h3>
                                <p class="text-sm text-gray-500">Mohon isi data sekolah asal Anda.</p>
                            </div>
                            <!-- Form fields for step 2 -->
                            <div>
                                <label for="school_name" class="text-sm font-medium text-gray-700 mb-1">Asal Lembaga Sekolah</label>
                                <input type="text" id="school_name" name="school_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="school_address" class="text-sm font-medium text-gray-700 mb-1">Alamat Sekolah</label>
                                <input type="text" id="school_address" name="school_address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div>
                                <label for="competence" class="text-sm font-medium text-gray-700 mb-1">Kompetensi/Bidang Keahlian</label>
                                <select id="competence" name="competence" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                    <option value="">Pilih Kompetensi</option>
                                    <option value="Tata Boga">Tata Boga</option>
                                    <option value="Tata Busana">Tata Busana</option>
                                    <option value="Tata Kecantikan">Tata Kecantikan</option>
                                    <option value="Teknik Pendingin dan Tata Udara">Teknik Pendingin dan Tata Udara</option>
                                </select>
                            </div>
                            <div>
                                <label for="class" class="text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <input type="text" id="class" name="class" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                            <div class="col-span-2">
                                <label for="dinas_branch" class="text-sm font-medium text-gray-700 mb-1">Cabang Dinas Wilayah</label>
                                <select id="dinas_branch" name="dinas_branch" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                                    <option value="">Pilih Wilayah</option>
                                    <option value="Wilayah I">Wilayah I</option>
                                    <option value="Wilayah II">Wilayah II</option>
                                    <option value="Wilayah III">Wilayah III</option>
                                    <option value="Wilayah IV">Wilayah IV</option>
                                    <option value="Wilayah V">Wilayah V</option>
                                </select>
                            </div>
                        </div>

                        <!-- Step 3: Lampiran -->
                        <div id="step-3" class="step-inactive grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                            <div class="col-span-2">
                                <h3 class="text-lg font-semibold text-gray-800">Lampiran</h3>
                                <p class="text-sm text-gray-500">Mohon unggah dokumen yang dibutuhkan.</p>
                            </div>
                            <!-- Form fields for step 3 -->
                            <div>
                                <label for="ktp_path" class="text-sm font-medium text-gray-700 mb-1">Fotocopy KTP</label>
                                <input type="file" id="ktp_path" name="ktp_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label for="ijazah_path" class="text-sm font-medium text-gray-700 mb-1">Fotocopy Ijazah Terakhir</label>
                                <input type="file" id="ijazah_path" name="ijazah_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label for="surat_tugas_path" class="text-sm font-medium text-gray-700 mb-1">Fotocopy Surat Tugas</label>
                                <input type="file" id="surat_tugas_path" name="surat_tugas_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label for="surat_sehat_path" class="text-sm font-medium text-gray-700 mb-1">Surat Keterangan Sehat</label>
                                <input type="file" id="surat_sehat_path" name="surat_sehat_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label for="pas_foto_path" class="text-sm font-medium text-gray-700 mb-1">Pas Foto</label>
                                <input type="file" id="pas_foto_path" name="pas_foto_path" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#5c76c1] hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">Pas Foto Formal Background Merah ukuran 3x4</p>
                            </div>
                            <div>
                                <label for="surat_tugas_nomor" class="text-sm font-medium text-gray-700 mb-1">Nomor Surat Tugas</label>
                                <input type="text" id="surat_tugas_nomor" name="surat_tugas_nomor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5c76c1]">
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="mt-8 flex justify-end space-x-4 self-end w-full">
                            <button type="button" id="back-button" onclick="changeStep(-1)" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-transform transform hover:scale-105" style="display:none;">
                                Kembali
                            </button>
                            <button type="button" id="next-button" onclick="changeStep(1)" class="px-6 py-3 bg-[#5c76c1] text-white font-semibold rounded-lg hover:bg-blue-600 transition-transform transform hover:scale-105">
                                Selanjutnya
                            </button>
                            <button type="submit" id="submit-button" class="px-6 py-3 bg-[#5c76c1] text-white font-semibold rounded-lg hover:bg-blue-600 transition-transform transform hover:scale-105" style="display:none;">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;

        const steps = [
            { id: 'step-1', indicator: 'step-indicator-1', label: 'step-label-1' },
            { id: 'step-2', indicator: 'step-indicator-2', label: 'step-label-2' },
            { id: 'step-3', indicator: 'step-indicator-3', label: 'step-label-3' }
        ];

        const nextButton = document.getElementById('next-button');
        const backButton = document.getElementById('back-button');
        const submitButton = document.getElementById('submit-button');

        function updateFormVisibility() {
            steps.forEach((step, index) => {
                const stepElement = document.getElementById(step.id);
                const indicatorElement = document.getElementById(step.indicator);
                const labelElement = document.getElementById(step.label);

                if (index + 1 === currentStep) {
                    stepElement.classList.remove('step-inactive');
                    indicatorElement.classList.remove('bg-gray-200', 'text-gray-500');
                    indicatorElement.classList.add('bg-[#5c76c1]', 'text-white');
                    labelElement.classList.remove('text-gray-500');
                    labelElement.classList.add('font-semibold', 'text-gray-800');
                } else {
                    stepElement.classList.add('step-inactive');
                    indicatorElement.classList.add('bg-gray-200', 'text-gray-500');
                    indicatorElement.classList.remove('bg-[#5c76c1]', 'text-white');
                    labelElement.classList.add('text-gray-500');
                    labelElement.classList.remove('font-semibold', 'text-gray-800');
                }

                if (index + 1 < currentStep) {
                    indicatorElement.innerHTML = '<svg class="w-8 h-8 text-white absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>';
                } else {
                    indicatorElement.innerHTML = `<span class="font-semibold">${index + 1}</span>`;
                }
            });

            // Update button visibility
            if (currentStep === 1) {
                backButton.style.display = 'none';
                nextButton.style.display = 'block';
                submitButton.style.display = 'none';
            } else if (currentStep === steps.length) {
                backButton.style.display = 'block';
                nextButton.style.display = 'none';
                submitButton.style.display = 'block';
            } else {
                backButton.style.display = 'block';
                nextButton.style.display = 'block';
                submitButton.style.display = 'none';
            }
        }

        function changeStep(direction) {
            currentStep += direction;
            if (currentStep < 1) currentStep = 1;
            if (currentStep > steps.length) currentStep = steps.length;
            updateFormVisibility();
        }

        // Initial setup
        document.addEventListener('DOMContentLoaded', () => {
            updateFormVisibility();
        });
    </script>
</body>
</html>
