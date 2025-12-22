
--
-- Table structure for table `jawaban_peserta`
--

CREATE TABLE `jawaban_peserta` (
  `id` int NOT NULL,
  `id_pendaftaran` int NOT NULL,
  `id_tes` int NOT NULL,
  `id_pertanyaan` int NOT NULL,
  `id_opsi_jawaban` int DEFAULT NULL,
  `jawaban_teks` text,
  `skor_didapat` int DEFAULT '0',
  `waktu_simpan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_opsi`
-- digunakan untuk mengelompokkan opsi jawaban
-- data ini yang diambil
--

CREATE TABLE `kelompok_opsi` (
  `id` int NOT NULL,
  `nama_kelompok` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kompetensi`
--

CREATE TABLE `kompetensi` (
  `id` int NOT NULL,
  `kode_kompetensi` varchar(50) NOT NULL,
  `nama_kompetensi` varchar(255) NOT NULL,
  `jam_pelajaran` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opsi_jawaban`
--

CREATE TABLE `opsi_jawaban` (
  `id` int NOT NULL,
  `id_kelompok` int NOT NULL,
  `teks_opsi` varchar(255) NOT NULL,
  `nilai_poin` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelatihan`
--

CREATE TABLE `pelatihan` (
  `id` int NOT NULL,
  `nama_pelatihan` varchar(255) NOT NULL,
  `tahun_angkatan` year DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelatihan_kompetensi`
--

CREATE TABLE `pelatihan_kompetensi` (
  `id_pelatihan` int NOT NULL,
  `id_kompetensi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_pelatihan`
-- dirubah menjadi peserta_pelatihan, agar tidak bingung
--

CREATE TABLE `pendaftaran_pelatihan` (
  `id` int NOT NULL,
  `id_peserta` int NOT NULL,
  `id_pelatihan` int NOT NULL,
  `tgl_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status_pelatihan` enum('PROSES','LULUS','TIDAK_LULUS','DROP_OUT') DEFAULT 'PROSES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan`
-- bank soal
--

CREATE TABLE `pertanyaan` (
  `id` int NOT NULL,
  `id_kelompok_opsi` int DEFAULT NULL,
  `teks_pertanyaan` text NOT NULL,
  `tipe_soal` enum('PILIHAN_GANDA','ESAI') DEFAULT 'PILIHAN_GANDA',
  `kategori` varchar(100) DEFAULT NULL,
  `id_opsi_benar` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan_tes`
-- digunakan untuk menghubungkan pertanyaan dengan tes
--

CREATE TABLE `pertanyaan_tes` (
  `id_tes` int NOT NULL,
  `id_pertanyaan` int NOT NULL,
  `no_urut` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id` int NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tes`
--

CREATE TABLE `tes` (
  `id` int NOT NULL,
  `id_pelatihan` int DEFAULT NULL,
  `id_kompetensi` int DEFAULT NULL,
  `tipe_tes` enum('SURVEI','PRETEST','POSTTEST') NOT NULL,
  `judul_tes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`),
  ADD KEY `id_tes` (`id_tes`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`),
  ADD KEY `id_opsi_jawaban` (`id_opsi_jawaban`);

--
-- Indexes for table `kelompok_opsi`
--
ALTER TABLE `kelompok_opsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kompetensi`
--
ALTER TABLE `kompetensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kompetensi` (`kode_kompetensi`);

--
-- Indexes for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelompok` (`id_kelompok`);

--
-- Indexes for table `pelatihan`
--
ALTER TABLE `pelatihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelatihan_kompetensi`
--
ALTER TABLE `pelatihan_kompetensi`
  ADD PRIMARY KEY (`id_pelatihan`,`id_kompetensi`),
  ADD KEY `id_kompetensi` (`id_kompetensi`);

--
-- Indexes for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_peserta` (`id_peserta`),
  ADD KEY `id_pelatihan` (`id_pelatihan`);

--
-- Indexes for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelompok_opsi` (`id_kelompok_opsi`),
  ADD KEY `id_opsi_benar` (`id_opsi_benar`);

--
-- Indexes for table `pertanyaan_tes`
--
ALTER TABLE `pertanyaan_tes`
  ADD PRIMARY KEY (`id_tes`,`id_pertanyaan`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `tes`
--
ALTER TABLE `tes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelatihan` (`id_pelatihan`),
  ADD KEY `id_kompetensi` (`id_kompetensi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelompok_opsi`
--
ALTER TABLE `kelompok_opsi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kompetensi`
--
ALTER TABLE `kompetensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelatihan`
--
ALTER TABLE `pelatihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tes`
--
ALTER TABLE `tes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  ADD CONSTRAINT `jawaban_peserta_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran_pelatihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_peserta_ibfk_2` FOREIGN KEY (`id_tes`) REFERENCES `tes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_peserta_ibfk_3` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_peserta_ibfk_4` FOREIGN KEY (`id_opsi_jawaban`) REFERENCES `opsi_jawaban` (`id`);

--
-- Constraints for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD CONSTRAINT `opsi_jawaban_ibfk_1` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok_opsi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelatihan_kompetensi`
--
ALTER TABLE `pelatihan_kompetensi`
  ADD CONSTRAINT `pelatihan_kompetensi_ibfk_1` FOREIGN KEY (`id_pelatihan`) REFERENCES `pelatihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pelatihan_kompetensi_ibfk_2` FOREIGN KEY (`id_kompetensi`) REFERENCES `kompetensi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  ADD CONSTRAINT `pendaftaran_pelatihan_ibfk_1` FOREIGN KEY (`id_peserta`) REFERENCES `peserta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_pelatihan_ibfk_2` FOREIGN KEY (`id_pelatihan`) REFERENCES `pelatihan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`id_kelompok_opsi`) REFERENCES `kelompok_opsi` (`id`),
  ADD CONSTRAINT `pertanyaan_ibfk_2` FOREIGN KEY (`id_opsi_benar`) REFERENCES `opsi_jawaban` (`id`);

--
-- Constraints for table `pertanyaan_tes`
--
ALTER TABLE `pertanyaan_tes`
  ADD CONSTRAINT `pertanyaan_tes_ibfk_1` FOREIGN KEY (`id_tes`) REFERENCES `tes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pertanyaan_tes_ibfk_2` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tes`
--
ALTER TABLE `tes`
  ADD CONSTRAINT `tes_ibfk_1` FOREIGN KEY (`id_pelatihan`) REFERENCES `pelatihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tes_ibfk_2` FOREIGN KEY (`id_kompetensi`) REFERENCES `kompetensi` (`id`) ON DELETE CASCADE;
COMMIT;