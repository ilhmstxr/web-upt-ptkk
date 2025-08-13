<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Biodata Diri — Pendaftaran Pelatihan | UPT PTKK Jatim</title>

    <!-- META -->
    <meta name="description" content="Form Biodata Diri untuk pendaftaran pelatihan UPT PTKK Dinas Pendidikan Jawa Timur.">
    <meta name="csrf-token" content="your_csrf_token_here">

    <!-- Desain Persis Sesuai Asli, Ditambah Sedikit Perbaikan UX -->
    <style>
        /* Reset sederhana */
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color:#f8fafc;
            color:#111827;
            min-height:100vh;
        }

        /* Container utama */
        .container {
            max-width:1200px;
            margin:0 auto;
            padding:20px;
        }

        /* Header */
        .header {
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:30px;
            background:#fff;
            padding:20px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,.05);
        }
        .close-btn {
            font-size:28px;
            font-weight:bold;
            color:#ef4444;
            text-decoration:none;
            margin-right:10px;
            background:#fee2e2;
            border-radius:50%;
            width:36px;
            height:36px;
            display:flex;
            align-items:center;
            justify-content:center;
            transition: background .2s ease, transform .2s ease;
        }
        .close-btn:hover { background:#fecaca; transform:rotate(90deg); }
        .logo {
            width:60px; height:60px; border-radius:100px;
            display:flex; align-items:center; justify-content:center;
            overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1);
        }
        .logo img { width:100%; height:100%; object-fit:contain; }
        .logo-fallback{
            width:100%; height:100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius:12px; display:flex; align-items:center; justify-content:center;
            color:#fff; font-weight:bold; font-size:14px; text-align:center; line-height:1.2;
        }
        .header-title { font-size:24px; font-weight:600; color:#1f2937; }

        /* Layout 2 kolom: sidebar + form */
        .main-content {
            display:grid;
            grid-template-columns:300px 1fr;
            gap:30px;
        }

        /* Sidebar Stepper */
        .sidebar {
            background:#e0f2fe;
            border-radius:12px;
            padding:25px;
            height:fit-content;
            position:sticky;
            top:16px;
        }
        .sidebar-title { font-size:20px; font-weight:600; color:#0f172a; margin-bottom:20px; }
        .step {
            display:flex;
            align-items:center;
            gap:15px;
            padding:15px 0;
            border-bottom:1px solid #bae6fd;
        }
        .step:last-child { border-bottom:none; }
        .step-number {
            width:35px; height:35px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-weight:600; font-size:14px;
        }
        .step.active .step-number { background:#3b82f6; color:#fff; }
        .step:not(.active) .step-number { background:#cbd5e1; color:#64748b; }
        .step-text { font-size:16px; font-weight:500; }
        .step.active .step-text { color:#0f172a; }
        .step:not(.active) .step-text { color:#64748b; }

        /* Kartu form */
        .form-container{
            background:#fff; border-radius:12px; padding:30px;
            box-shadow:0 2px 8px rgba(0,0,0,.05);
        }
        .form-title{
            font-size:22px; font-weight:700; color:#0f172a; margin-bottom:8px;
        }
        .form-subtitle{
            font-size:14px; color:#64748b; margin-bottom:24px;
        }

        /* Grid field */
        .form-row {
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-bottom:20px;
        }
        .form-group { display:flex; flex-direction:column; }
        .form-group.full-width { grid-column:1 / -1; }

        /* Label & Input */
        label { font-size:14px; font-weight:600; color:#374151; margin-bottom:8px; }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="tel"],
        select,
        textarea{
            padding:12px 16px;
            border:2px solid #e5e7eb;
            border-radius:8px; font-size:14px;
            color:#374151; background:#fff;
            transition:all .2s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus{
            outline:none; border-color:#3b82f6;
            box-shadow:0 0 0 3px rgba(59,130,246,.1);
        }
        input::placeholder, textarea::placeholder { color:#9ca3af; }
        textarea{ resize:vertical; min-height:100px; font-family:inherit; }
        select{
            cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position:right 12px center; background-repeat:no-repeat; background-size:16px; padding-right:40px;
        }
        /* Custom class for invalid fields */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        /* Error + help */
        .error-text{ color:#ef4444; font-size:12px; margin-top:6px; }
        .help-text{ color:#6b7280; font-size:12px; margin-top:6px; }

        /* Tombol */
        .actions{
            display:flex; align-items:center; justify-content:flex-end;
            gap:12px; margin-top:8px;
        }
        .btn{
            display:inline-flex; align-items:center; justify-content:center;
            font-weight:600; border:none; border-radius:8px; cursor:pointer;
            transition:all .2s ease; padding:12px 18px; font-size:14px;
        }
        .btn-secondary{
            background:#f3f4f6; color:#111827; border:2px solid #e5e7eb;
        }
        .btn-secondary:hover{ background:#e5e7eb; }
        .btn-primary{
            background:#3b82f6; color:#fff;
        }
        .btn-primary:hover{ background:#2563eb; transform:translateY(-1px); box-shadow:0 4px 12px rgba(59,130,246,.3); }
        .btn[disabled]{
            opacity:.7; cursor:not-allowed;
        }

        /* Alert sukses/error flash */
        .flash {
            border-radius:10px; padding:14px 16px; margin-bottom:16px; font-size:14px;
        }
        .flash-success{ background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46; }
        .flash-error{ background:#fef2f2; border:1px solid #fecaca; color:#7f1d1d; }

        /* Progress (optional) */
        .progress-wrap{ margin-bottom:16px; }
        .progress-bar{
            width:100%; height:10px; border-radius:8px; background:#e5e7eb; overflow:hidden;
        }
        .progress-fill{
            height:100%; width:0%; background:#3b82f6; transition:width .3s ease;
        }
        .progress-text{ margin-top:8px; font-size:12px; color:#6b7280; text-align:right; }

        /* Footer kecil */
        .footer-note{
            margin-top:16px; font-size:12px; color:#6b7280; text-align:center;
        }

        /* Modal Kustom */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: slideIn 0.3s ease-out;
        }
        .modal-content h3 {
            margin-bottom: 15px;
            color: #1f2937;
        }
        .modal-content p {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .modal-close-btn {
            background-color: #3b82f6;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsif */
        @media (max-width: 768px){
            .main-content{ grid-template-columns:1fr; gap:20px; }
            .form-row{ grid-template-columns:1fr; gap:15px; }
            .container{ padding:15px; }
            .header-title{ font-size:18px; }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <header class="header">
        <!-- Kembali ke Landing -->
        <a href="#" class="close-btn" aria-label="Kembali ke beranda">×</a>

        <div class="logo">
            <img src="https://placehold.co/60x60/3b82f6/ffffff?text=LOGO" alt="Logo UPT PTKK"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback" style="display:none;">UPT<br>PTKK</div>
        </div>

        <h1 class="header-title">UPT PTKK Dinas Pendidikan Jawa Timur</h1>
    </header>

    <!-- MAIN -->
    <div class="main-content">
        <!-- SIDEBAR (Stepper) -->
        <aside class="sidebar" aria-label="Langkah Pendaftaran">
            <h2 class="sidebar-title">Pendaftaran Pelatihan</h2>

            <div class="step active" aria-current="step">
                <div class="step-number">1</div>
                <div class="step-text">Biodata Diri</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-text">Biodata Sekolah</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">Lampiran</div>
            </div>
        </aside>

        <!-- FORM -->
        <main class="form-container">
            <!-- Flash message (client-side) -->
            <div id="flash-message" style="display:none;" class="flash"></div>

            <!-- Progress bar sederhana (diukur client-side) -->
            <div class="progress-wrap" aria-hidden="true">
                <div class="progress-bar"><div id="progressFill" class="progress-fill"></div></div>
                <div id="progressText" class="progress-text">0% lengkap</div>
            </div>

            <h2 class="form-title">Biodata Diri</h2>
            <p class="form-subtitle">Isi data sesuai identitas Anda. Pastikan NIK dan tanggal lahir benar.</p>

            <form id="registrationForm" method="POST" action="/submit-biodata" novalidate autocomplete="on">
                <input type="hidden" name="program" value="example-program">

                <!-- Nama & NIK -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            inputmode="text"
                            placeholder="Masukkan Nama Lengkap"
                            required
                            aria-required="true"
                        />
                        <div class="help-text">Sesuai KTP/Ijazah.</div>
                        <div id="name-error" class="error-text" style="display:none;">Nama lengkap wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK (16 Digit)</label>
                        <input
                            type="text"
                            id="nik"
                            name="nik"
                            placeholder="Masukkan NIK 16 digit"
                            maxlength="16"
                            inputmode="numeric"
                            pattern="[0-9]{16}"
                            required
                            aria-required="true"
                        />
                        <div class="help-text">Hanya angka, persis 16 digit.</div>
                        <div id="nik-error" class="error-text" style="display:none;">NIK harus berisi 16 digit angka.</div>
                    </div>
                </div>

                <!-- Tempat/Tanggal Lahir -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_place">Tempat Lahir</label>
                        <input
                            type="text"
                            id="birth_place"
                            name="birth_place"
                            placeholder="Contoh: Surabaya"
                            required
                            aria-required="true"
                        />
                        <div id="birth_place-error" class="error-text" style="display:none;">Tempat lahir wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Tanggal Lahir</label>
                        <input
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            placeholder="Masukkan Tanggal Lahir"
                            required
                            aria-required="true"
                        />
                        <div id="birth_date-error" class="error-text" style="display:none;">Tanggal lahir tidak valid.</div>
                    </div>
                </div>

                <!-- Jenis Kelamin & Agama -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select id="gender" name="gender" required aria-required="true">
                            <option value="">Masukkan Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <div id="gender-error" class="error-text" style="display:none;">Jenis kelamin wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label for="religion">Agama</label>
                        <select id="religion" name="religion" required aria-required="true">
                            <option value="">Masukkan Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        <div id="religion-error" class="error-text" style="display:none;">Agama wajib diisi.</div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="address">Alamat Tempat Tinggal</label>
                        <textarea
                            id="address"
                            name="address"
                            placeholder="Masukkan alamat lengkap tempat tinggal"
                            required
                            aria-required="true"
                        ></textarea>
                        <div id="address-error" class="error-text" style="display:none;">Alamat wajib diisi.</div>
                    </div>
                </div>

                <!-- HP & Email -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="phone">Nomor Handphone</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="Format Angka (08)"
                            maxlength="15"
                            inputmode="numeric"
                            pattern="[0-9]{10,15}"
                            title="Nomor HP hanya boleh angka dan 10–15 digit"
                            required
                            aria-required="true"
                        />
                        <div class="help-text">Contoh: 081234567890</div>
                        <div id="phone-error" class="error-text" style="display:none;">Nomor HP tidak valid.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan Email aktif"
                            required
                            aria-required="true"
                            autocomplete="email"
                        />
                        <div id="email-error" class="error-text" style="display:none;">Email tidak valid.</div>
                    </div>
                </div>

                <!-- Catatan kecil -->
                <p class="help-text">Dengan menekan “Selanjutnya”, Anda menyetujui pemrosesan data sesuai kebijakan privasi UPT PTKK.</p>

                <!-- Tombol Aksi -->
                <div class="actions">
                    <a class="btn btn-secondary" href="#">Batal</a>
                    <button id="nextBtn" type="submit" class="btn btn-primary">Selanjutnya</button>
                </div>
            </form>

            <div class="footer-note">© 2025 UPT PTKK Dinas Pendidikan Jawa Timur</div>
        </main>
    </div>
</div>

<!-- Modal Kustom -->
<div id="customModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modalTitle"></h3>
        <p id="modalMessage"></p>
        <button id="modalCloseBtn" class="modal-close-btn">Tutup</button>
    </div>
</div>

<!-- SCRIPT: Validasi dan fungsionalitas tambahan -->
<script>
(function () {
    // Helper untuk mengambil elemen
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    const form = $('#registrationForm');
    const nextBtn = $('#nextBtn');
    const progressFill = $('#progressFill');
    const progressText = $('#progressText');
    const flashMessage = $('#flash-message');

    // Elemen Modal
    const customModal = $('#customModal');
    const modalTitle = $('#modalTitle');
    const modalMessage = $('#modalMessage');
    const modalCloseBtn = $('#modalCloseBtn');

    // Tampilkan modal kustom
    function showModal(title, message) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        customModal.style.display = 'flex';
    }

    // Sembunyikan modal kustom
    function hideModal() {
        customModal.style.display = 'none';
    }

    modalCloseBtn.addEventListener('click', hideModal);
    customModal.addEventListener('click', (e) => {
        if (e.target === customModal) {
            hideModal();
        }
    });

    // Set max birth date = hari ini
    const birthDateInput = $('#birth_date');
    const today = new Date().toISOString().split('T')[0];
    birthDateInput.setAttribute('max', today);

    // Kumpulan field wajib
    const requiredFields = [
        'name', 'nik', 'birth_place', 'birth_date', 'gender', 'religion', 'address', 'phone', 'email'
    ];

    // Fungsi untuk menandai input sebagai invalid
    function markInvalid(el, errorMessageId) {
        el.classList.add('is-invalid');
        el.setAttribute('aria-invalid', 'true');
        const errorTextEl = $(`#${errorMessageId}`);
        if (errorTextEl) errorTextEl.style.display = 'block';
    }

    // Fungsi untuk menandai input sebagai valid
    function markValid(el, errorMessageId) {
        el.classList.remove('is-invalid');
        el.setAttribute('aria-invalid', 'false');
        const errorTextEl = $(`#${errorMessageId}`);
        if (errorTextEl) errorTextEl.style.display = 'none';
    }

    // Update progress bar
    function updateProgress() {
        let filledCount = 0;
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            if (el && el.value.trim() !== '') {
                filledCount++;
            }
        });
        const percentage = Math.round((filledCount / requiredFields.length) * 100);
        if (progressFill) progressFill.style.width = percentage + '%';
        if (progressText) progressText.textContent = percentage + '% lengkap';
    }

    // Persist data ke localStorage
    const LS_KEY = 'biodataFormStep1';
    function persistToLocalStorage() {
        const data = {};
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            if (el) data[id] = el.value;
        });
        localStorage.setItem(LS_KEY, JSON.stringify(data));
    }

    // Restore data dari localStorage
    function restoreFromLocalStorage() {
        try {
            const raw = localStorage.getItem(LS_KEY);
            if (!raw) return;
            const data = JSON.parse(raw);
            if (typeof data !== 'object' || !data) return;

            requiredFields.forEach(id => {
                const el = document.getElementById(id);
                if (el && data[id] !== undefined && data[id] !== null && String(data[id]).length) {
                    el.value = data[id];
                }
            });
        } catch(e) {
            console.error('Failed to restore from local storage:', e);
        }
    }

    // Bersihkan semua error
    function clearErrors() {
        $$('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
            el.setAttribute('aria-invalid', 'false');
        });
        $$('.error-text').forEach(el => el.style.display = 'none');
    }

    // Validasi form saat submit
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            const value = el.value.trim();
            if (!value) {
                markInvalid(el, `${id}-error`);
                isValid = false;
            }
        });

        // Validasi NIK 16 digit
        const nikInput = $('#nik');
        if (nikInput.value.replace(/\D/g, '').length !== 16) {
            markInvalid(nikInput, 'nik-error');
            showModal('Validasi Gagal', 'NIK harus berisi 16 digit angka.');
            isValid = false;
        }

        // Validasi tanggal lahir tidak di masa depan
        const bd = new Date(birthDateInput.value);
        if (birthDateInput.value && bd > new Date(today)) {
            markInvalid(birthDateInput, 'birth_date-error');
            showModal('Validasi Gagal', 'Tanggal lahir tidak boleh di masa depan.');
            isValid = false;
        }

        // Validasi email sederhana
        const emailInput = $('#email');
        if (emailInput.value && !/^\S+@\S+\.\S+$/.test(emailInput.value)) {
            markInvalid(emailInput, 'email-error');
            showModal('Validasi Gagal', 'Alamat email tidak valid.');
            isValid = false;
        }

        if (isValid) {
            // Disable button saat submit (hindari double click)
            nextBtn.setAttribute('disabled', 'disabled');
            nextBtn.textContent = 'Menyimpan...';

            // Kirim data ke server (contoh menggunakan Fetch API)
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Simulasi pengiriman data
            console.log('Mengirim data:', data);
            setTimeout(() => {
                // Tampilkan pesan sukses setelah 1.5 detik
                const successMessage = 'Data berhasil disimpan! Anda akan diarahkan ke langkah berikutnya.';
                flashMessage.innerHTML = successMessage;
                flashMessage.className = 'flash flash-success';
                flashMessage.style.display = 'block';

                // Lanjutkan ke langkah berikutnya
                // window.location.href = '/next-step';

                // Re-enable button untuk demo
                nextBtn.removeAttribute('disabled');
                nextBtn.textContent = 'Selanjutnya';
                
            }, 1500);

            // Jika form submission tradisional, bisa langsung e.target.submit();
        } else {
            const firstInvalid = $('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });

    // Event listener untuk update progress dan local storage
    $$('input, select, textarea').forEach(el => {
        el.addEventListener('input', () => {
            markValid(el, `${el.id}-error`);
            updateProgress();
            persistToLocalStorage();
        });
        el.addEventListener('change', () => {
            updateProgress();
            persistToLocalStorage();
        });
    });

    // Muat ulang data dari local storage dan update progress saat halaman dimuat
    window.onload = function() {
        restoreFromLocalStorage();
        updateProgress();
    };

})();
</script>

</body>
</html>