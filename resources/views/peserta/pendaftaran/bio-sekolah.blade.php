@extends('peserta.pendaftaran.layout.main', ['currentStep' => 2])

@section('title', 'Biodata Sekolah')

@section('content')
<!-- Form Container - Full Width dengan Responsive Design -->
<div class="w-full max-w-none">
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-blue-200">
        <div class="mb-6 lg:mb-8">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-900 mb-2">Biodata Sekolah</h2>
            <p class="text-xs sm:text-sm lg:text-base text-blue-600">Lengkapi data sekolah Anda dengan benar</p>
        </div>

        <form id="biodataSekolahForm" action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-4 sm:space-y-6" novalidate>
            @csrf
            <input type="hidden" name="current_step" value="{{ $currentStep }}">

            <!-- Asal Instansi & Alamat -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="asal_instansi" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Asal Lembaga Instansi</label>
                    <div class="relative">
                        <input type="text" id="asal_instansi" name="asal_instansi"
                            placeholder="Masukkan Asal Lembaga"
                            value="{{ old('asal_instansi', $formData['asal_instansi'] ?? '') }}"
                            required
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 placeholder-blue-400 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('asal_instansi') border-red-500 focus:ring-red-500 @enderror">
                        <div id="asal_instansiError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('asal_instansi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat_instansi" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Alamat Instansi</label>
                    <div class="relative">
                        <input type="text" id="alamat_instansi" name="alamat_instansi"
                            placeholder="Masukkan Alamat Instansi"
                            value="{{ old('alamat_instansi', $formData['alamat_instansi'] ?? '') }}"
                            required
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 placeholder-blue-400 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('alamat_instansi') border-red-500 focus:ring-red-500 @enderror">
                        <div id="alamat_instansiError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('alamat_instansi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kompetensi & Kelas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="kompetensi_keahlian" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Kompetensi Keahlian</label>
                    <div class="relative">
                        <select id="kompetensi_keahlian" name="kompetensi_keahlian" required
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('kompetensi_keahlian') border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Pilih Kompetensi</option>
                            @foreach ($kompetensi as $b)
                                <option value="{{ $b->id }}" @selected(old('kompetensi_keahlian', $formData['kompetensi_keahlian'] ?? '') == $b->id)>
                                    {{ $b->nama_kompetensi }}
                                </option>
                            @endforeach
                        </select>
                        <div id="kompetensi_keahlianError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('kompetensi_keahlian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelas" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Kelas</label>
                    <div class="relative">
                        <select id="kelas" name="kelas" required
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('kelas') border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Pilih Kelas</option>
                            <option value="X" @selected(old('kelas', $formData['kelas'] ?? '') == 'X')>Kelas X</option>
                            <option value="XI" @selected(old('kelas', $formData['kelas'] ?? '') == 'XI')>Kelas XI</option>
                            <option value="XII" @selected(old('kelas', $formData['kelas'] ?? '') == 'XII')>Kelas XII</option>
                        </select>
                        <div id="kelasError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pelatihan -->
            <fieldset>
                <legend class="block mb-3 text-sm lg:text-base font-semibold text-blue-900">Pelatihan yang ingin diikuti</legend>
                <div class="flex flex-wrap gap-3">
                    @foreach ($pelatihan as $p)
                        <label class="flex-1 min-w-[250px] max-w-[300px] flex items-center gap-3 border border-blue-200 rounded-lg p-3 cursor-pointer hover:bg-blue-50 transition">
                            <input type="radio" id="pelatihan_{{ $p->id }}" name="pelatihan_id" value="{{ $p->id }}"
                                required
                                @checked(old('pelatihan_id', $formData['pelatihan_id'] ?? '') == $p->id)
                                class="text-blue-600 focus:ring-blue-500 flex-shrink-0">
                            <span class="text-sm lg:text-base text-blue-900 font-medium leading-relaxed">{{ $p->nama_pelatihan }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="pelatihan_idError" class="error-popup mt-1 p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="error-message-text"></span>
                </div>
                @error('pelatihan_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </fieldset>

            <!-- Kota & Cabang Dinas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="kota" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Kota / Kabupaten</label>
                    <div class="relative">
                        <input type="text" id="kota" name="kota" placeholder="Ketik nama kota atau kabupaten..."
                            required autocomplete="off"
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 placeholder-blue-400 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('kota') border-red-500 focus:ring-red-500 @enderror">
                        <div id="kotaSuggestions" class="absolute z-10 w-full bg-white border border-blue-200 rounded-b-lg mt-1 max-h-60 overflow-y-auto shadow-lg hidden"></div>
                        <input type="hidden" name="kota_id" id="kota_id">
                        <div id="kotaError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('kota')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cabangDinas_id" class="block text-sm lg:text-base font-semibold text-blue-900 mb-2">Cabang Dinas Wilayah</label>
                    <div class="relative">
                        <select id="cabangDinas_id" name="cabangDinas_id" required
                            class="w-full border-2 border-blue-200 rounded-lg px-3 py-2 sm:px-4 sm:py-3 text-sm lg:text-base text-blue-900 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('cabangDinas_id') border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Pilih Cabang Dinas</option>
                            @foreach ($cabangDinas as $cb)
                                <option value="{{ $cb->id }}" @selected(old('cabangDinas_id', $formData['cabangDinas_id'] ?? '') == $cb->id)>
                                    {{ $cb->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div id="cabangDinas_idError" class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-xs rounded-md shadow-lg flex items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('cabangDinas_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Navigasi -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-blue-100">
                <!-- Tombol Kembali -->
                <a href="{{ route('pendaftaran.create', ['step' => 1]) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center bg-white border-2 border-blue-600 text-blue-600 font-semibold px-4 py-2 sm:px-6 sm:py-2 lg:px-8 lg:py-3 rounded-lg shadow-sm hover:bg-blue-50 hover:border-blue-700 hover:text-blue-700 transition transform hover:-translate-y-0.5 text-sm lg:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>

                <!-- Tombol Selanjutnya -->
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 sm:px-8 sm:py-3 lg:px-10 lg:py-4 rounded-lg shadow-md transition transform hover:-translate-y-1 text-sm lg:text-base">
                    Selanjutnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<style>
    .error-popup {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
        transform: translateY(10px);
        z-index: 10;
    }

    .error-popup.visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .error-popup {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
    }

    /* Tablet specific improvements */
    @media (min-width: 641px) and (max-width: 1023px) {
        .grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<script defer>
    document.addEventListener("DOMContentLoaded", async () => {
        // ====== Elemen dasar ======
        const kotaInput = document.getElementById("kota");
        const suggestionsContainer = document.getElementById("kotaSuggestions");
        const errorPopup = document.getElementById("kotaError");
        const errorText = errorPopup?.querySelector(".error-message-text");

        // Buat hidden input kota_id jika belum ada (seharusnya sudah ada di HTML)
        let kotaIdHidden = document.getElementById("kota_id");
        if (!kotaIdHidden) {
            kotaIdHidden = document.createElement("input");
            kotaIdHidden.type = "hidden";
            kotaIdHidden.id = "kota_id";
            kotaIdHidden.name = "kota_id";
            kotaInput.parentElement.appendChild(kotaIdHidden);
        }

        // Dapatkan elemen form (untuk validasi submit)
        const form = kotaInput.closest("form");

        // ====== State ======
        let allRegencies = [];
        let filtered = [];
        let activeIndex = -1; // untuk navigasi keyboard
        let selectedItem = null; // { id, name } terakhir yang dipilih dari daftar

        // ====== Util ======
        const normalize = (s) => (s || "").toString().trim().toLowerCase().replace(/\s+/g, " ");

        const showSuggestions = () => {
            suggestionsContainer.classList.remove("hidden");
        };
        const hideSuggestions = () => {
            suggestionsContainer.classList.add("hidden");
            activeIndex = -1;
        };

        const clearSuggestions = () => {
            suggestionsContainer.innerHTML = "";
            activeIndex = -1;
        };

        const renderSuggestions = (items) => {
            clearSuggestions();
            items.forEach((item, idx) => {
                const div = document.createElement("div");
                div.textContent = item.name;
                div.className =
                    "p-2 cursor-pointer hover:bg-gray-100 select-none text-sm lg:text-base " +
                    (idx === activeIndex ? "bg-gray-100" : "");
                // mousedown agar tidak kalah oleh event blur pada input
                div.addEventListener("mousedown", (e) => {
                    e.preventDefault();
                    chooseItem(item);
                });
                suggestionsContainer.appendChild(div);
            });
            if (items.length > 0) showSuggestions();
            else hideSuggestions();
        };

        const chooseItem = (item) => {
            kotaInput.value = item.name;
            kotaIdHidden.value = item.id;
            selectedItem = {
                id: item.id,
                name: item.name
            };
            hideSuggestions();
            hideError();
        };

        const showError = (msg) => {
            if (!errorPopup) return;
            errorText.textContent = msg || "Harus pilih kota/kabupaten dari daftar.";
            errorPopup.classList.remove("hidden");
            errorPopup.classList.add("visible");
        };
        const hideError = () => {
            if (!errorPopup) return;
            errorPopup.classList.add("hidden");
            errorPopup.classList.remove("visible");
        };

        const debounce = (fn, delay = 150) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };
        };

        // ====== Ambil data API (provinsi 35 / Jawa Timur) ======
        try {
            const res = await fetch(
                "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/35.json", {
                    cache: "no-store"
                }
            );
            if (!res.ok) throw new Error("Gagal mengambil data kota/kabupaten.");
            allRegencies = await res.json();
        } catch (err) {
            console.error(err);
            showError("Gagal memuat daftar kota/kabupaten. Coba muat ulang halaman.");
            return;
        }

        // ====== Event: user mengetik ======
        const onInput = () => {
            hideError();
            // reset pilihan jika user mulai ketik lagi
            kotaIdHidden.value = "";
            selectedItem = null;

            const q = normalize(kotaInput.value);
            if (!q) {
                clearSuggestions();
                hideSuggestions();
                return;
            }

            filtered = allRegencies.filter((r) => normalize(r.name).includes(q));
            renderSuggestions(filtered);
        };

        kotaInput.addEventListener("input", debounce(onInput, 120));

        // ====== Event: keyboard navigation ======
        kotaInput.addEventListener("keydown", (e) => {
            const visible = !suggestionsContainer.classList.contains("hidden");
            if (!visible && (e.key === "ArrowDown" || e.key === "ArrowUp")) {
                if ((filtered?.length || 0) > 0) showSuggestions();
            }

            switch (e.key) {
                case "ArrowDown":
                    if (filtered.length === 0) return;
                    e.preventDefault();
                    activeIndex = (activeIndex + 1) % filtered.length;
                    renderSuggestions(filtered);
                    ensureActiveVisible();
                    break;
                case "ArrowUp":
                    if (filtered.length === 0) return;
                    e.preventDefault();
                    activeIndex = (activeIndex - 1 + filtered.length) % filtered.length;
                    renderSuggestions(filtered);
                    ensureActiveVisible();
                    break;
                case "Enter":
                    if (visible && activeIndex >= 0 && filtered[activeIndex]) {
                        e.preventDefault();
                        chooseItem(filtered[activeIndex]);
                    }
                    break;
                case "Escape":
                    hideSuggestions();
                    break;
            }
        });

        const ensureActiveVisible = () => {
            const children = suggestionsContainer.children;
            if (activeIndex < 0 || activeIndex >= children.length) return;
            const el = children[activeIndex];
            const cTop = suggestionsContainer.scrollTop;
            const cBottom = cTop + suggestionsContainer.clientHeight;
            const eTop = el.offsetTop;
            const eBottom = eTop + el.offsetHeight;
            if (eTop < cTop) suggestionsContainer.scrollTop = eTop;
            else if (eBottom > cBottom)
                suggestionsContainer.scrollTop = eBottom - suggestionsContainer.clientHeight;
        };

        // ====== Klik di luar → sembunyikan dropdown ======
        document.addEventListener("click", (e) => {
            if (!kotaInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                hideSuggestions();
            }
        });

        // ====== Validasi saat submit ======
        if (form) {
            form.addEventListener("submit", (e) => {
                // Sembunyikan semua error popup dulu
                form.querySelectorAll('.error-popup').forEach(el => {
                    el.classList.add('hidden');
                    el.classList.remove('visible');
                });
                // Hapus border merah dari semua input/select
                form.querySelectorAll('input, select').forEach(el => {
                    el.classList.remove('border-red-500', 'focus:ring-red-500');
                });

                let firstErrorElement = null;

                // Validasi required fields
                form.querySelectorAll('input[required], select[required]').forEach(element => {
                    if (!element.value || (element.type === 'radio' && !form.querySelector(`input[name="${element.name}"]:checked`))) {
                        e.preventDefault();
                        const errorPopup = document.getElementById(element.id + 'Error');
                        if (errorPopup) {
                            errorPopup.querySelector('.error-message-text').textContent = 'Kolom ini wajib diisi.';
                            errorPopup.classList.remove('hidden');
                            errorPopup.classList.add('visible');
                        }
                        element.classList.add('border-red-500', 'focus:ring-red-500');
                        if (!firstErrorElement) firstErrorElement = element;
                    }
                });

                // Validasi khusus kota/kabupaten
                if (!kotaIdHidden.value) {
                    e.preventDefault();
                    showError("Harus pilih kota/kabupaten dari daftar.");
                    kotaInput.classList.add('border-red-500', 'focus:ring-red-500');
                    if (!firstErrorElement) firstErrorElement = kotaInput;
                } else if (!selectedItem || normalize(kotaInput.value) !== normalize(selectedItem.name)) {
                    e.preventDefault();
                    showError("Nama kota/kabupaten tidak valid. Pilih dari daftar.");
                    kotaInput.classList.add('border-red-500', 'focus:ring-red-500');
                    if (!firstErrorElement) firstErrorElement = kotaInput;
                }

                if (firstErrorElement) {
                    firstErrorElement.focus();
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            // Hilangkan error popup saat user input/ubah nilai
            form.querySelectorAll('input[required], select[required]').forEach(element => {
                element.addEventListener('input', () => {
                    const errorPopup = document.getElementById(element.id + 'Error');
                    if (errorPopup) {
                        errorPopup.classList.add('hidden');
                        errorPopup.classList.remove('visible');
                    }
                    element.classList.remove('border-red-500', 'focus:ring-red-500');
                });
                element.addEventListener('change', () => {
                    const errorPopup = document.getElementById(element.id + 'Error');
                    if (errorPopup) {
                        errorPopup.classList.add('hidden');
                        errorPopup.classList.remove('visible');
                    }
                    element.classList.remove('border-red-500', 'focus:ring-red-500');
                });
            });
        }

        // ====== Radio button pelatihan bisa uncheckable ======
        const radioButtons = document.querySelectorAll('input[type="radio"][name="pelatihan_id"]');
        let lastChecked = null;
        radioButtons.forEach(radio => {
            radio.addEventListener('click', function() {
                if (this === lastChecked) {
                    this.checked = false;
                    lastChecked = null;
                } else {
                    lastChecked = this;
                }
            });
        });
    });
</script>
