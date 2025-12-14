-- =================================================================================================
-- SCRIPT TEMPLATE: INJECT DATA DARI STRUKTUR EXCEL/CSV KE SQL DATABASE (KOMPLEKS)
-- =================================================================================================
-- Script ini dibuat untuk membantu Anda memindahkan data dari format "Flat Excel" (Baris)
-- ke dalam struktur database relasional yang ternormalisasi (Users -> Peserta -> Pendaftaran ...).
--
-- KARENA DATA SUMBER TIDAK LENGKAP (Tidak ada NIK, Email, Password, dll),
-- script ini menggunakan nilai DUMMY untuk memenuhi constraint database (NOT NULL).
--
-- PANDUAN:
-- 1. Copy data dari Excel Anda.
-- 2. Ganti placeholder `[VAL_...]` dengan nilai sebenarnya.
-- 3. Script ini dibagi per "Baris Data" (Satu peserta = Satu blok transaksi).
-- =================================================================================================

START TRANSACTION;

-- =================================================================================================
-- [BLOK 1]: CONTOH UNTUK DATA TIPE "PRETEST"
-- Format Sumber: bidang_pelatihan, skor, nama, email, instansi, bidang_pelatihan_id, tes_id, jawaban_1...
-- =================================================================================================

-- 1. INSERT USER (Cek dulu jika email belum ada)
-- Kita pakai ON DUPLICATE KEY UPDATE agar tidak error jika email sudah ada.
-- Password Default: 'password123' (Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT INTO users (name, email, password, created_at, updated_at) 
VALUES (
    '[VAL_NAMA]',              -- Ambil dari kolom 'nama'
    '[VAL_EMAIL]',             -- Ambil dari kolom 'email' (Jika kosong, ganti: 'nama_tanpa_spasi@dummy.test')
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    NOW(), NOW()
) ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Simpan ID User ke Variabel
SET @user_id = (SELECT id FROM users WHERE email = '[VAL_EMAIL]' LIMIT 1);

-- 2. INSERT PESERTA
-- Kekurangan Data: NIK, TTL, Alamat, HP (Diisi Dummy)
INSERT INTO peserta (
    user_id, instansi_id, nama, nik, 
    tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_hp, 
    created_at, updated_at
)
VALUES (
    @user_id, 
    1,                       -- [VAL_INSTANSI_ID] (Perlu dipastikan ID Instansi ada)
    '[VAL_NAMA]',
    FLOOR(RAND() * 9000000000000000) + 1000000000000000, -- NIK Random (Karena Wajib Unique)
    'Jakarta',               -- Dummy
    '1990-01-01',            -- Dummy
    'Laki-laki',             -- Dummy
    'Islam',                 -- Dummy
    'Jl. Data Tidak Lengkap',-- Dummy
    '081200000000',          -- Dummy
    NOW(), NOW()
) ON DUPLICATE KEY UPDATE nama = VALUES(nama); -- Hindari error jika NIK duplikat (tapi karena random, kecil kemungkinan)

-- Simpan ID Peserta ke Variabel
SET @peserta_id = (SELECT id FROM peserta WHERE user_id = @user_id LIMIT 1);

-- 3. INSERT PENDAFTARAN PELATIHAN
-- Kekurangan Data: No Registrasi (Diisi Dummy)
INSERT INTO pendaftaran_pelatihan (
    peserta_id, pelatihan_id, nomor_registrasi, 
    tanggal_pendaftaran, nilai_pre_test, status, created_at, updated_at
)
VALUES (
    @peserta_id, 
    1,                        -- [VAL_PELATIHAN_ID] (Ambil dari 'bidang_pelatihan_id' atau mapping manual)
    CONCAT('REG-', UNIX_TIMESTAMP(), '-', @peserta_id), -- Generate No Reg Unik
    NOW(), 
    [VAL_SKOR],               -- Ambil dari kolom 'skor'
    'Peserta',                -- Default Status
    NOW(), NOW()
);

-- 4. INSERT PERCOBAAN (Log Tes)
INSERT INTO percobaan (
    tes_id, peserta_id, waktu_mulai, waktu_selesai, skor, created_at, updated_at
)
VALUES (
    [VAL_TES_ID],             -- Ambil dari kolom 'tes_id'
    @peserta_id,
    NOW(), NOW(),
    [VAL_SKOR],               -- Ambil dari kolom 'skor'
    NOW(), NOW()
);

-- Simpan ID Percobaan ke Variabel
SET @percobaan_id = LAST_INSERT_ID();

-- 5. INSERT JAWABAN USER (Looping kolom jawaban_1 s/d jawaban_25)
-- Anda harus mengulang query ini untuk SETIAP kolom jawaban (1-25).
-- Contoh untuk Jawaban 1:
INSERT INTO jawaban_user (
    percobaan_id, pertanyaan_id, opsi_jawaban_id, jawaban_teks, nilai_jawaban, created_at, updated_at
)
VALUES (
    @percobaan_id,
    101,                       -- [VAL_PERTANYAAN_ID_NO_1] (Anda harus tahu ID Pertanyaan untuk soal no 1)
    NULL,                      -- Jika pilihan ganda, isi ID Opsi. Jika Essay, NULL.
    '[VAL_JAWABAN_1]',         -- Ambil dari kolom 'jawaban_1'
    0,                         -- Nilai per soal (bisa dihitung nanti atau input manual)
    NOW(), NOW()
);
-- ... Ulangi INSERT INTO jawaban_user untuk jawaban_2, jawaban_3, dst ...


-- =================================================================================================
-- [BLOK 2]: CONTOH UNTUK DATA TIPE "POSTTEST"
-- Format Sumber: bidang_pelatihan, skor, nama, email, instansi, tes_id, jawaban_1...
-- =================================================================================================
-- Logikanya SAMA dengan PRETEST, bedanya di tabel 'pendaftaran_pelatihan' Anda update kolom 'nilai_post_test'.

-- (INSERT USER & PESERTA SAMA SEPERTI DI ATAS - COPY PASTE LOGIKA NYA)
-- ...
-- UPDATE Pendaftaran (Karena asumsinya peserta sudah mendaftar pas pretest)
-- Jika belum ada pendaftaran, lakukan INSERT seperti di atas.
UPDATE pendaftaran_pelatihan 
SET nilai_post_test = [VAL_SKOR], updated_at = NOW()
WHERE peserta_id = @peserta_id AND pelatihan_id = 1; -- [VAL_PELATIHAN_ID]

-- INSERT Percobaan (Posttest) - Idem seperti Pretest
INSERT INTO percobaan (
    tes_id, peserta_id, waktu_mulai, waktu_selesai, skor, created_at, updated_at
)
VALUES (
    [VAL_TES_ID], 
    @peserta_id, 
    NOW(), NOW(), 
    [VAL_SKOR], 
    NOW(), NOW()
);
SET @percobaan_post_id = LAST_INSERT_ID();

-- INSERT Jawaban User (Posttest) - Idem seperti Pretest
-- ...

-- =================================================================================================
-- [BLOK 3]: CONTOH UNTUK DATA TIPE "MONEV"
-- Format Sumber: bidang_pelatihan, email, kompetensi, tes_id, jawaban_1...
-- =================================================================================================
-- Logika mirip, tapi insert ke kolom 'nilai_survei' di pendaftaran (jika ada) atau hanya masuk tabel Percobaan.

COMMIT;

-- =================================================================================================
-- CARA PENGGUNAAN MASSAL (DI EXCEL):
-- 1. Buat formula Excel untuk men-generate string SQL ini per baris data.
--    Contoh Formula: =CONCATENATE("INSERT INTO users ... VALUES ('", A2, "', '", B2, "'...);")
-- 2. Copy hasil formula seluruh baris ke file .sql baru.
-- 3. Jalankan file .sql tersebut.
-- =================================================================================================
