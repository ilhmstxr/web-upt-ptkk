<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pendaftaran Pelatihan | UPT PTKK Jatim</title>

    <!-- META -->
    <meta name="description" content="Formulir pendaftaran pelatihan UPT PTKK Dinas Pendidikan Jawa Timur.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            cursor:pointer; /* Mengubah kursor untuk indikasi interaktif */
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
        input[type="file"],
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
        input[type="file"]:focus,
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
        .btn-primary:active{ transform:translateY(0); box-shadow:none; }
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
        /* Tambahan untuk multi-step form */
        .step-form {
            display: none; /* Defaultnya semua form disembunyikan */
        }
        .step-form.active {
            display: block; /* Form yang aktif akan ditampilkan */
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <header class="header">
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
            <div class="step" data-step="1">
                <div class="step-number">1</div>
                <div class="step-text">Biodata Diri</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-text">Biodata Sekolah</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-text">Lampiran</div>
            </div>
        </aside>

        <!-- FORM CONTAINER -->
        <main class="form-container">
            <!-- Flash message (client-side) -->
            <div id="flash-message" style="display:none;" class="flash"></div>

            <!-- Progress bar sederhana (diukur client-side) -->
            <div class="progress-wrap" aria-hidden="true">
                <div class="progress-bar"><div id="progressFill" class="progress-fill"></div></div>
                <div id="progressText" class="progress-text">0% lengkap</div>
            </div>

            <!-- Perubahan: tambahkan enctype dan CSRF agar sesuai Controller -->
            <form id="registrationForm" method="POST" action="/submit-biodata" enctype="multipart/form-data" novalidate autocomplete="on">
                @csrf
                <input type="hidden" name="program" value="example-program">

                <!-- STEP 1: Biodata Diri -->
                <div id="step-1" class="step-form active">
                    <h2 class="form-title">Biodata Diri</h2>
                    <p class="form-subtitle">Isi data sesuai identitas Anda. Pastikan NIK dan tanggal lahir benar.</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" inputmode="text" placeholder="Masukkan Nama Lengkap (tulis gelar jika ada, cth: S.Kom, S.Pd)" required aria-required="true"/>
                            <div class="help-text">Sesuai KTP/Ijazah.</div>
                            <div id="name-error" class="error-text" style="display:none;">Nama lengkap wajib diisi.</div>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK (16 Digit)</label>
                            <input type="text" id="nik" name="nik" placeholder="Masukkan NIK 16 digit" maxlength="16" inputmode="numeric" pattern="[0-9]{16}" required aria-required="true"/>
                            <div class="help-text">Hanya angka, persis 16 digit.</div>
                            <div id="nik-error" class="error-text" style="display:none;">NIK harus berisi 16 digit angka.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="birth_place">Tempat Lahir</label>
                            <input type="text" id="birth_place" name="birth_place" placeholder="Contoh: Surabaya" required aria-required="true"/>
                            <div id="birth_place-error" class="error-text" style="display:none;">Tempat lahir wajib diisi.</div>
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date" placeholder="Masukkan Tanggal Lahir" required aria-required="true"/>
                            <div id="birth_date-error" class="error-text" style="display:none;">Tanggal lahir tidak valid.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required aria-required="true">
                                <option value="">Masukkan Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div id="jenis_kelamin-error" class="error-text" style="display:none;">Jenis kelamin wajib diisi.</div>
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

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="address">Alamat Tempat Tinggal</label>
                            <textarea id="address" name="address" placeholder="Masukkan alamat lengkap tempat tinggal" required aria-required="true"></textarea>
                            <div id="address-error" class="error-text" style="display:none;">Alamat wajib diisi.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="phone">Nomor Handphone</label>
                            <input type="tel" id="phone" name="phone" placeholder="Format Angka (08)" maxlength="15" inputmode="numeric" pattern="[0-9]{10,15}" title="Nomor HP hanya boleh angka dan 10–15 digit" required aria-required="true"/>
                            <div class="help-text">Contoh: 081234567890</div>
                            <div id="phone-error" class="error-text" style="display:none;">Nomor HP tidak valid.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Masukkan Email aktif" required aria-required="true" autocomplete="email"/>
                            <div id="email-error" class="error-text" style="display:none;">Email tidak valid.</div>
                        </div>
                    </div>

                    <p class="help-text">Dengan menekan “Selanjutnya”, Anda menyetujui pemrosesan data sesuai kebijakan privasi UPT PTKK.</p>

                    <div class="actions">
                        <button type="button" class="btn btn-secondary cancel-btn">Batal</button>
                        <button type="button" class="btn btn-primary" id="next-step-1">Selanjutnya</button>
                    </div>
                </div>

                <!-- STEP 2: Biodata Sekolah -->
                <div id="step-2" class="step-form">
                    <h2 class="form-title">Biodata Sekolah</h2>
                    <p class="form-subtitle">Lengkapi informasi sekolah/institusi Anda saat ini.</p>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="npsn">NPSN (Nomor Pokok Sekolah Nasional)</label>
                            <input type="text" id="npsn" name="npsn" placeholder="Masukkan NPSN (contoh: 20512345)" required/>
                            <div id="npsn-error" class="error-text" style="display:none;">NPSN wajib diisi.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="school_name">Nama Sekolah/Institusi</label>
                            <input type="text" id="school_name" name="school_name" placeholder="Masukkan Nama Sekolah/Institusi" required/>
                            <div id="school_name-error" class="error-text" style="display:none;">Nama sekolah wajib diisi.</div>
                        </div>
                        <div class="form-group">
                            <label for="competence">Kompetensi / Kompetensi Keahlian</label>
                            <select id="competence" name="competence" required>
                                <option value="">Masukkan Kompetensi</option>
                                <option value="teknik-informatika">Teknik Informatika</option>
                                <option value="teknik-mesin">Teknik Mesin</option>
                                <option value="teknik-elektro">Teknik Elektro</option>
                                <option value="teknik-sipil">Teknik Sipil</option>
                                <option value="akuntansi">Akuntansi</option>
                                <option value="administrasi-perkantoran">Administrasi Perkantoran</option>
                                <option value="pemasaran">Pemasaran</option>
                                <option value="multimedia">Multimedia</option>
                                <option value="tata-boga">Tata Boga</option>
                                <option value="tata-busana">Tata Busana</option>
                                <option value="tata-kecantikan">Tata Kecantikan</option>
                                <option value="perhotelan">Perhotelan</option>
                            </select>
                            <div id="competence-error" class="error-text" style="display:none;">Kompetensi wajib diisi.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="class">Kelas</label>
                            <select id="class" name="class" required>
                                <option value="">Masukkan Kelas</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                            <div id="class-error" class="error-text" style="display:none;">Kelas wajib diisi.</div>
                        </div>
                        <div class="form-group">
                            <label for="dinas_branch">Cabang Dinas Wilayah</label>
                            <select id="dinas_branch" name="dinas_branch" required>
                                <option value="">Masukkan Dinas Wilayah</option>
                                <option value="surabaya">Cabang Dinas Wilayah Surabaya</option>
                                <option value="malang">Cabang Dinas Wilayah Malang</option>
                                <option value="kediri">Cabang Dinas Wilayah Kediri</option>
                                <option value="madiun">Cabang Dinas Wilayah Madiun</option>
                                <option value="bojonegoro">Cabang Dinas Wilayah Bojonegoro</option>
                                <option value="pamekasan">Cabang Dinas Wilayah Pamekasan</option>
                                <option value="jember">Cabang Dinas Wilayah Jember</option>
                            </select>
                            <div id="dinas_branch-error" class="error-text" style="display:none;">Cabang Dinas wajib diisi.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="school_address">Alamat Sekolah</label>
                            <textarea id="school_address" name="school_address" placeholder="Masukkan alamat lengkap sekolah" required></textarea>
                            <div id="school_address-error" class="error-text" style="display:none;">Alamat sekolah wajib diisi.</div>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-secondary" id="prev-step-2">Sebelumnya</button>
                        <button type="button" class="btn btn-primary" id="next-step-2">Selanjutnya</button>
                    </div>
                </div>

                <!-- STEP 3: Lampiran -->
                <div id="step-3" class="step-form">
                    <h2 class="form-title">Lampiran</h2>
                    <p class="form-subtitle">Unggah dokumen yang diperlukan dalam format PDF atau JPG.</p>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="file_ktp">Scan KTP</label>
                            <!-- name disesuaikan ke ktp_path, id tetap file_ktp supaya JS validasi tetap jalan -->
                            <input type="file" id="file_ktp" name="ktp_path" accept=".pdf,.jpg,.jpeg" required/>
                            <div class="help-text">Unggah file KTP Anda (PDF/JPG, max 2MB).</div>
                            <div id="file_ktp-error" class="error-text" style="display:none;">Scan KTP wajib diunggah.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="file_ijazah">Scan Ijazah Terakhir</label>
                            <!-- name disesuaikan ke ijazah_path -->
                            <input type="file" id="file_ijazah" name="ijazah_path" accept=".pdf,.jpg,.jpeg" required/>
                            <div class="help-text">Unggah file ijazah Anda (PDF/JPG, max 2MB).</div>
                            <div id="file_ijazah-error" class="error-text" style="display:none;">Scan Ijazah wajib diunggah.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="file_surat_tugas">Scan Surat Tugas</label>
                            <input type="file" id="file_surat_tugas" name="surat_tugas_path" accept=".pdf,.jpg,.jpeg" />
                            <div class="help-text">Jika ada: unggah Surat Tugas (PDF/JPG, max 2MB).</div>
                            <div id="file_surat_tugas-error" class="error-text" style="display:none;">Surat Tugas wajib diunggah.</div>
                        </div>
                        <div class="form-group">
                            <label for="surat_tugas_nomor">Nomor Surat Tugas</label>
                            <input type="text" id="surat_tugas_nomor" name="surat_tugas_nomor" placeholder="Masukkan Nomor Surat Tugas (jika ada)"/>
                            <div id="surat_tugas_nomor-error" class="error-text" style="display:none;">Nomor Surat Tugas tidak valid.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="file_surat_sehat">Scan Surat Sehat</label>
                            <input type="file" id="file_surat_sehat" name="surat_sehat_path" accept=".pdf,.jpg,.jpeg" />
                            <div class="help-text">Jika ada: unggah Surat Sehat (PDF/JPG, max 2MB).</div>
                            <div id="file_surat_sehat-error" class="error-text" style="display:none;">Surat Sehat wajib diunggah.</div>
                        </div>
                        <div class="form-group">
                            <label for="file_photo">Pas Foto Formal</label>
                            <!-- name disesuaikan ke pas_foto_path -->
                            <input type="file" id="file_photo" name="pas_foto_path" accept=".jpg,.jpeg" required/>
                            <div class="help-text">Unggah pas foto formal Anda (JPG, max 1MB).</div>
                            <div id="file_photo-error" class="error-text" style="display:none;">Pas Foto wajib diunggah.</div>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-secondary" id="prev-step-3">Sebelumnya</button>
                        <button type="submit" class="btn btn-primary" id="submit-form">Selesai & Kirim</button>
                    </div>
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

<!-- SCRIPT: Logika untuk multi-step form -->
<script>
(function () {
    // Helper untuk mengambil elemen
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    const form = $('#registrationForm');
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
    if (birthDateInput) birthDateInput.setAttribute('max', today);

    // Kumpulan field wajib (update: menambahkan field sekolah dan lampiran sesuai controller)
    const requiredFields = {
        'step-1': ['name', 'nik', 'birth_place', 'birth_date', 'jenis_kelamin', 'religion', 'address', 'phone', 'email'],
        'step-2': ['npsn', 'school_name', 'school_address', 'competence', 'class', 'dinas_branch'],
        'step-3': ['file_ktp', 'file_ijazah', 'file_photo'] // surat tugas & surat sehat bersifat opsional di client (sesuaikan jika perlu)
    };

    // Fungsi untuk menandai input sebagai invalid
    function markInvalid(el, errorMessageId) {
        if (!el) return;
        el.classList.add('is-invalid');
        el.setAttribute('aria-invalid', 'true');
        const errorTextEl = $(`#${errorMessageId}`);
        if (errorTextEl) errorTextEl.style.display = 'block';
    }

    // Fungsi untuk menandai input sebagai valid
    function markValid(el, errorMessageId) {
        if (!el) return;
        el.classList.remove('is-invalid');
        el.setAttribute('aria-invalid', 'false');
        const errorTextEl = $(`#${errorMessageId}`);
        if (errorTextEl) errorTextEl.style.display = 'none';
    }

    // Update progress bar
    function updateProgress() {
        let totalFields = 0;
        let filledCount = 0;
        
        for (const step in requiredFields) {
            totalFields += requiredFields[step].length;
            requiredFields[step].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    if (el.type === 'file') {
                        if (el.files.length > 0) {
                            filledCount++;
                        }
                    } else if (el.value && el.value.trim() !== '') {
                        filledCount++;
                    }
                }
            });
        }

        const percentage = totalFields > 0 ? Math.round((filledCount / totalFields) * 100) : 0;
        if (progressFill) progressFill.style.width = percentage + '%';
        if (progressText) progressText.textContent = percentage + '% lengkap';
    }

    // Navigasi langkah
    let currentStep = 1;
    function showStep(stepNumber) {
        $$('.step-form').forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        const stepEl = $(`#step-${stepNumber}`);
        if (stepEl) stepEl.classList.add('active');

        $$('.sidebar .step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        const side = $(`.sidebar .step[data-step="${stepNumber}"]`);
        if (side) side.classList.add('active');

        currentStep = stepNumber;
        updateProgress();
    }
    
    // Tambahkan event listener untuk mengklik sidebar step
    $$('.sidebar .step').forEach(stepEl => {
        stepEl.addEventListener('click', () => {
            const stepToGo = parseInt(stepEl.dataset.step);
            // Hanya izinkan navigasi ke langkah sebelumnya
            if (stepToGo < currentStep) {
                showStep(stepToGo);
            } else {
                // Untuk navigasi maju, gunakan tombol "Selanjutnya" agar validasi terpicu
                showModal('Navigasi Terbatas', 'Silakan gunakan tombol "Selanjutnya" untuk melanjutkan ke langkah berikutnya setelah mengisi data.');
            }
        });
    });

    // Validasi langkah
    function validateStep(stepId) {
        let isValid = true;
        clearErrors();

        const fields = requiredFields[stepId] || [];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                // Khusus untuk file input
                if (el.type === 'file') {
                    if (el.files.length === 0) {
                        markInvalid(el, `${id}-error`);
                        isValid = false;
                    }
                }
                // Untuk input lain
                else if (!el.value || el.value.trim() === '') {
                    markInvalid(el, `${id}-error`);
                    isValid = false;
                }
            }
        });

        // Validasi khusus untuk langkah 1
        if (stepId === 'step-1' && isValid) {
            const nikInput = $('#nik');
            // Regex untuk memastikan hanya angka dan 16 digit
            if (nikInput && !/^\d{16}$/.test(nikInput.value)) {
                markInvalid(nikInput, 'nik-error');
                showModal('Validasi Gagal', 'NIK harus berisi 16 digit angka.');
                isValid = false;
            }
            const birthDateInput = $('#birth_date');
            const bd = new Date(birthDateInput.value);
            const todayDate = new Date(today);
            if (birthDateInput && birthDateInput.value && bd > todayDate) {
                markInvalid(birthDateInput, 'birth_date-error');
                showModal('Validasi Gagal', 'Tanggal lahir tidak boleh di masa depan.');
                isValid = false;
            }
            const emailInput = $('#email');
            // Regex untuk validasi format email dasar
            if (emailInput && emailInput.value && !/^\S+@\S+\.\S+$/.test(emailInput.value)) {
                markInvalid(emailInput, 'email-error');
                showModal('Validasi Gagal', 'Alamat email tidak valid.');
                isValid = false;
            }
        }
        
        return isValid;
    }

    // Bersihkan semua error
    function clearErrors() {
        $$('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
            el.setAttribute('aria-invalid', 'false');
        });
        $$('.error-text').forEach(el => el.style.display = 'none');
    }

    // Event listeners untuk navigasi
    const next1 = $('#next-step-1');
    if (next1) next1.addEventListener('click', () => {
        if (validateStep('step-1')) {
            showStep(2);
        } else {
            showModal('Validasi Gagal', 'Harap lengkapi semua data wajib pada formulir biodata diri.');
            const firstInvalid = $('#step-1 .is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });

    const prev2 = $('#prev-step-2');
    if (prev2) prev2.addEventListener('click', () => {
        showStep(1);
    });

    const next2 = $('#next-step-2');
    if (next2) next2.addEventListener('click', () => {
        if (validateStep('step-2')) {
            showStep(3);
        } else {
            showModal('Validasi Gagal', 'Harap lengkapi semua data wajib pada formulir biodata sekolah.');
            const firstInvalid = $('#step-2 .is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });

    const prev3 = $('#prev-step-3');
    if (prev3) prev3.addEventListener('click', () => {
        showStep(2);
    });

    // Event listener untuk tombol Batal
    const cancelBtn = $('.cancel-btn');
    if (cancelBtn) cancelBtn.addEventListener('click', () => {
        // Logika untuk tombol batal, bisa reload halaman atau clear form
        form.reset(); // Mengatur ulang semua field form
        showStep(1); // Kembali ke langkah 1
        clearErrors(); // Membersihkan pesan error
        updateProgress(); // Mengatur ulang progress
        showModal('Formulir Dibatalkan', 'Semua data yang Anda masukkan telah dihapus. Anda dapat memulai kembali.');
    });

    // Final submit
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // cegah default sementara supaya kita bisa validasi client-side
        if (validateStep('step-3')) {
             // Disable button saat submit (hindari double click)
            const submitBtn = $('#submit-form');
            if (submitBtn) {
                submitBtn.setAttribute('disabled', 'disabled');
                submitBtn.textContent = 'Mengirim...';
            }

            // KIRIM DENGAN SUBMIT BIASA sehingga Laravel dapat melakukan redirect()
            // form.submit() memicu pengiriman tanpa memicu kembali event 'submit' handler
            form.submit();

            // NOTE: tidak perlu re-enable tombol di sini karena akan pindah halaman.
        } else {
            showModal('Validasi Gagal', 'Harap lengkapi semua lampiran yang diperlukan.');
            const firstInvalid = $('#step-3 .is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });
    
    // Function to convert text to uppercase in real-time, excluding email and password fields
    function applyUppercaseToFields() {
        // Get all input and textarea elements, but exclude email, password, date, number, tel, and hidden inputs
        const textInputs = [
            ...document.querySelectorAll('input[type="text"]'),
            ...document.querySelectorAll('textarea')
        ].filter(input => {
            // Don't apply to email, password, date, number, tel, or hidden inputs
            return input.type !== 'email' &&
                   input.type !== 'password' &&
                   input.type !== 'date' &&
                   input.type !== 'number' &&
                   input.type !== 'tel' &&
                   input.type !== 'hidden';
        });

        textInputs.forEach(input => {
            // Convert to uppercase as user types
            input.addEventListener('input', function() {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.toUpperCase();
                // Maintain cursor position
                this.setSelectionRange(start, end);
            });

            // Convert to uppercase when pasting content
            input.addEventListener('paste', function(e) {
                setTimeout(() => {
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    this.value = this.value.toUpperCase();
                    this.setSelectionRange(start, end);
                }, 10);
            });
        });
    }

    // Apply uppercase to text fields when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        applyUppercaseToFields();
    });

    // Listeners untuk update progress dan validasi saat input berubah
    $$('input, select, textarea').forEach(el => {
        el.addEventListener('input', () => {
            markValid(el, `${el.id}-error`);
            updateProgress();
        });
        el.addEventListener('change', () => {
            markValid(el, `${el.id}-error`);
            updateProgress();
        });
    });

    // Muat ulang data dari local storage dan update progress saat halaman dimuat
    window.onload = function() {
        showStep(1); // Mulai dari langkah pertama
    };

})();
</script>

</body>
</html>
