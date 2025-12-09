-- Pastikan Anda sudah memiliki data di tabel `kompetensi` dan `pelatihan` sebelum menjalankan ini.

INSERT INTO `tes` (`id`, `judul`, `deskripsi`, `tipe`, `sub_tipe`, `kompetensi_id`, `pelatihan_id`, `durasi_menit`, `created_at`, `updated_at`) VALUES
(1, 'Post-Test Teknik Pendingin', 'Tes akhir untuk mengukur pemahaman materi Teknik Pendingin dan Tata Udara.', 'tes', 'post-test', 4, 1, 30, NOW(), NOW()),
(2, 'Post-Test Kecantikan', 'Tes akhir untuk mengukur pemahaman materi Kecantikan (Perawatan Rambut).', 'tes', 'post-test', 3, 1, 30, NOW(), NOW()),
(3, 'Post-Test Tata Boga', 'Tes akhir untuk mengukur pemahaman materi Produk Bakery dan Pastry.', 'tes', 'post-test', 1, 1, 30, NOW(), NOW()),
(4, 'Post-Test Tata Busana', 'Tes akhir untuk mengukur pemahaman materi Menjahit dan Pola.', 'tes', 'post-test', 2, 1, 30, NOW(), NOW()),
(5, 'Survei Kepuasan Pelatihan', 'Survei untuk mengumpulkan umpan balik mengenai penyelenggaraan pelatihan.', 'survei', NULL, 1, 1, NULL, NOW(), NOW()),



-- PERTANYAAN

-- TPTU
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(1, 1, 'Mesin 3R adalah mesin yang digunakan untuk melakukan proses:', 'pilihan_ganda'),
(1, 2, 'Mesin 3R dapat melakukan tiga fungsi tersebut secara', 'pilihan_ganda'),
(1, 3, 'Recovery pada mesin pendingin yaitu proses menampung', 'pilihan_ganda'),
(1, 4, 'Unit AC wajib di recovery bila dalam keadaan :', 'pilihan_ganda'),
(1, 5, 'Alat yang dibutuhkan di saat proses Recovery yaitu :', 'pilihan_ganda'),
(1, 6, 'Alat dan bahan di bawah ini adalah digunakan disaat proses ….', 'pilihan_ganda'),
(1, 7, 'Gambar di bawah ini adalah proses Recovery Refigerant yaitu :', 'pilihan_ganda'),
(1, 8, 'Proses langkah kerja mesin 3 R di bawah ini adalah proses :', 'pilihan_ganda'),
(1, 9, 'Gambar di bawah ini adalah proses :', 'pilihan_ganda'),
(1, 10, 'Proses kerja atau langkah kerja berikut adalah', 'pilihan_ganda'),
(1, 11, 'Factor penyebab AC kotor adalah :', 'pilihan_ganda'),
(1, 12, 'Gambar di bawah ini bertujuan agar unit AC bekerja dengan baik pengkondisian udara bertujuan', 'pilihan_ganda'),
(1, 13, 'Pemeliharaan AC dibagi berapa proses agar unit AC sesuai proses memperpanjang usia pakai :', 'pilihan_ganda'),
(1, 14, 'Dari gambar di bawah proses yang masih harus dilakukan pada point A.1 adalah :', 'pilihan_ganda'),
(1, 15, 'Proses yang harus di lakukan yang sesuai gambar kerja di bawah adalah :', 'pilihan_ganda'),
(1, 16, 'Pemeriksaan proses perawatan AC dalam proses gambar di bawah ini adalah :', 'pilihan_ganda'),
(1, 17, 'Kebutuhan alat dan bahan yang di bawah ini ada beberapa yang harus disiapkan agar proses perawtan berjalan dengan SOP, adapaun perlatan yang masih belum terakomodir adalah :', 'pilihan_ganda'),
(1, 18, 'Gambar di bawah ini adalah langkah persiapan alat dalam langkah proses perawatan AC, ada beberapa alat yang masih belum terakomodir pada gambar di bawah ini, adapun alat yang kurang adalah :', 'pilihan_ganda'),
(1, 19, 'Part pada indoor yang harus dibersihkan sesuai SOP no 1 s.d 6 wajib di lakukan oleh seorang calon teknisi dan teknisi, adapaun no 3 dan 4 adalah :', 'pilihan_ganda'),
(1, 20, 'Pada gambar di bawah ini Part yang harus dibersihkan pada system outdoor adalah yang tertera pada nomor 1 s.d 3, adapaun untuk persyaratan SOP nya untuk no 2 dan 3 adalah :', 'pilihan_ganda');

-- KECANTIKAN

INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(2, 21, 'Fungsi diagnosa kulit kepala dan rambut adalah untuk….', 'pilihan_ganda'),
(2, 22, 'Shampo yang memiliki kandungan asam nitrat yang dapat melarutkan lemak atau minyak pada kulit kepala dan rambut. adalah…', 'pilihan_ganda'),
(2, 23, 'Syarat utama pada air yang digunakan untuk mencuci rambut adalah….', 'pilihan_ganda'),
(2, 24, 'Pengurutan kulit kepala saat melakukan penyampoan harus dilakukan dengan gerakan yang benar,berikut merupakan gerakan pada pengurutan kulit kepala dan rambut yaitu:….', 'pilihan_ganda'),
(2, 25, 'Kosmetika yang berfungsi sebagai pengkondisi setelah penyampoan adalah ….', 'pilihan_ganda'),
(2, 26, 'Untuk menghasilkan rambut lurus mengembang dan rapi, tehnik blowdry yang tepat adalah …', 'pilihan_ganda'),
(2, 27, 'Arah hair dryer yang tepat pada saat mengeringkan rambut adalah ….', 'pilihan_ganda'),
(2, 28, 'Untuk menjaga keselamatan kerja pada waktu mengeringkan rambut,sebaiknya digunakan hair dryer dengan jarak kurang lebih ….', 'pilihan_ganda'),
(2, 29, 'Berikut ini adalah fungsi pengurutan / massage kulit kepala ,kecuali…', 'pilihan_ganda'),
(2, 30, 'Alat listrik yang digunakan untuk penguapan pada creambath disebut….', 'pilihan_ganda'),
(2, 31, 'Jenis air yang baik digunakan untuk pencucian rambut adalah air yang . . . .', 'pilihan_ganda'),
(2, 32, 'Shampo telur baik digunakan untuk rambut . . . .', 'pilihan_ganda'),
(2, 33, 'Kosmetik yang digunakan untuk mengkilatkan rambut kusam adalah . . . .', 'pilihan_ganda'),
(2, 34, 'Tujuan perawatan rambut dan kulit kepala adalah untuk . . . .', 'pilihan_ganda'),
(2, 35, 'Alat-alat yang dapat menunjang keefektifan creambath adalah bukan . . .', 'pilihan_ganda'),
(2, 36, 'Seperangkat lahiriah yang terbawa sejak lahir bagi diri seseorang disebut juga .', 'pilihan_ganda'),
(2, 37, 'Untuk pembentukan tulang dan gigi diperlukan . . . .', 'pilihan_ganda'),
(2, 38, 'Kosmetika yang tidak digunakan untuk pencucian rambut normal adalah . . . .', 'pilihan_ganda'),
(2, 39, 'Untuk rambut yang kering sekali digunakan shampo..', 'pilihan_ganda'),
(2, 40, 'Effleurage adalah gerakan pengurutan secara . . .', 'pilihan_ganda');


-- TATA BOGA
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(3, 41, 'Berikut ini yang merupakan bahan utama dalam membuat produk bakery dan pastry kecuali …..', 'pilihan_ganda'),
(3, 42, 'Untuk membuat adonan roti diperlukan tepung terigu dengan kadar protein …..', 'pilihan_ganda'),
(3, 43, 'Bahan pengembang untuk roti adalah …..', 'pilihan_ganda'),
(3, 44, 'Jenis gula yang butiran nya lebh halus dari gula pasir adalah …..', 'pilihan_ganda'),
(3, 45, 'Penggunaan garam pada pembuatan produk roti adalah …..', 'pilihan_ganda'),
(3, 46, 'Fungsi garam dalam pembuatan roti adalah …..', 'pilihan_ganda'),
(3, 47, 'Margarin merupakan mentega sintetis yang terbuat dari …..', 'pilihan_ganda'),
(3, 48, 'Gula memiliki sifat higrokopis artinya …..', 'pilihan_ganda'),
(3, 49, 'Pencampuran gula yang tidak merata dan terlalu banyak pada adonan roti menyebabkan …..', 'pilihan_ganda'),
(3, 50, 'Margarin yang dipergunakan untuk membuat adonan puff disebut …..', 'pilihan_ganda'),
(3, 51, 'Pada proses pembuatan produk roti perlu dilakukan proofing, tujuan nya adalah untuk …..', 'pilihan_ganda'),
(3, 52, 'Suhu pada saat proofing adalah …..', 'pilihan_ganda'),
(3, 53, 'Lama proses proofing adalah …..', 'pilihan_ganda'),
(3, 54, 'Kisaran suhu pada saat memanggang roti tawar adalah …..', 'pilihan_ganda'),
(3, 55, 'Tujuan roti dilepaskan dari cetakan saat masih panas adalah', 'pilihan_ganda'),
(3, 56, 'Dibawah ini merupakan produk pastry kecuali …..', 'pilihan_ganda'),
(3, 57, 'Kisaran suhu pada saat memanggang adonan puff pastry adalah …..', 'pilihan_ganda'),
(3, 58, 'Karakteristik hasil puff pastry kecuali …..', 'pilihan_ganda'),
(3, 59, 'Produk puff pastry sebelum dipanggang harus diistirahatkan selama …..', 'pilihan_ganda'),
(3, 60, 'Danish pastry adalah …..', 'pilihan_ganda');

-- TATA BUSANA
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(4, 61, 'Seorang penjahit selain terampil menyelesaikan pakaian sesuai dengan model pesanan, juga harus bisa...', 'pilihan_ganda'),
(4, 62, 'Tujuan merendam bahan sebelum digunting adalah...', 'pilihan_ganda'),
(4, 63, 'Gaun yang mempunyai model punggung yang terlihat disebut model ...', 'pilihan_ganda'),
(4, 64, 'Pemakaian gaun dengan garis dada yang rendah dan tanpa lengan sebaiknya dipakai oleh orang yang berperawakan ...', 'pilihan_ganda'),
(4, 65, 'Pola yang dipergunakan untuk menjahit dengan sistim konveksi adalah pola...', 'pilihan_ganda'),
(4, 66, 'Sebelum mengelim rok lingkar sebaiknya rok digantung terlebih dahulu supaya…', 'pilihan_ganda'),
(4, 67, 'Pola kerah yang digambar dengan mempergunakan pola dasar muka dan belakang adalah kerah ...', 'pilihan_ganda'),
(4, 68, 'Wool, beludru dan drill merupakan bahan dengan tekstur.…', 'pilihan_ganda'),
(4, 69, 'Tujuan merancang bahan adalah ...', 'pilihan_ganda'),
(4, 70, 'Untuk merubah model pakaian diperlukan pola ...', 'pilihan_ganda'),
(4, 71, 'Yang termasuk warna primer adalah.…', 'pilihan_ganda'),
(4, 72, 'Segala sesuatu yang berhubungan dengan cara berpakaian disebut …', 'pilihan_ganda'),
(4, 73, 'Yang harus diletakkan lebih dahulu pada waktu merancang bahan adalah pola', 'pilihan_ganda'),
(4, 74, 'Rok yang memakai beberapa lipit bertumpukan pada satu garis adalah ...', 'pilihan_ganda'),
(4, 75, 'Krah berdiri tanpa penegak, apabila ditutup menjadi krah tegak adalah ...', 'pilihan_ganda'),
(4, 76, 'Untuk menggambar pola pertama yang harus diperhatikan adalah ...', 'pilihan_ganda'),
(4, 77, 'Tujuan mengikat lingkaran pinggang sebelum mengambil ukuran adalah ...', 'pilihan_ganda'),
(4, 78, 'Pada pakaian mahal, kumai serong dapat dipakai untuk ...', 'pilihan_ganda'),
(4, 79, 'Etika jabatan meliputi ...', 'pilihan_ganda'),
(4, 80, 'Kerja secara professional harus dilaksanakan karena merupakan ...', 'pilihan_ganda');

-- JAWABANS

-- TPTU
INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Jawaban untuk Pertanyaan ID 1 (Kunci: A)
(1, 'Recovery, recycling dan recharging pada mesin pendingin.', true),
(1, 'Recycling dan recharging pada mesin pendingin', false),
(1, 'Reciever, Recovery, Recharge pada mesin pendingin', false),
(1, 'Recharge pada mesin pendingin', false),
(1, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 2 (Kunci: D)
(2, 'Tidak bersamaan dalam satu waktu', false),
(2, 'Bergantian dalam proses', false),
(2, 'Bergantian dan berjedah dalam satu proses', false),
(2, 'Bersamaan dalam satu waktu', true),
(2, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 3 (Kunci: A)
(3, 'Recovery pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.', true),
(3, 'Recovery pada refrigerasi yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.', false),
(3, 'Recharging pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.', false),
(3, 'Pump Down pada system bagian katup SSV dan DSV pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.', false),
(3, 'Semua Jawaban Benar', false),

-- Jawaban untuk Pertanyaan ID 4 (Kunci: E)
(4, 'Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan.', false),
(4, 'Refrigerant tidak dapat di pump down.', false),
(4, 'Refrigerant tidak lancer saat di pump down.', false),
(4, 'Unit mengalami kerusakan', false),
(4, 'Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan dan Refrigerant tidak dapat di pump down.', true),

-- Jawaban untuk Pertanyaan ID 5 (Kunci: B)
(5, 'Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank', false),
(5, 'Mesin Recovery, Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.', true),
(5, 'Mesin Recovery, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.', false),
(5, 'Mesin Recovery, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.', false),
(5, 'Pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.', false),

-- Jawaban untuk Pertanyaan ID 6 (Kunci: D)
(6, 'Proses pump down', false),
(6, 'Proses Recharging', false),
(6, 'Proses penggantian part', false),
(6, 'Proses Recovery Freon', true),
(6, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 7 (Kunci: A)
(7, 'Proses Recovery Refigerant', true),
(7, 'Proses Recovery Refigerant', false),
(7, 'Proses Recovery Refigerant', false),
(7, 'Proses Recovery Refigerant', false),
(7, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 8 (Kunci: B)
(8, 'Pengisian zat pendingin', false),
(8, 'Evakuasi Udara dan kotoran', true),
(8, 'Evakuasi Olie pada mesin pendingin', false),
(8, 'Evakuasi system pendingin', false),
(8, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 9 (Kunci: E)
(9, 'Vacuum', false),
(9, 'Charging proses', false),
(9, 'Vacuum dan Inlet sistem proses', false),
(9, 'Vacuum dan Oultet Charging proses', false),
(9, 'Vacuum dan Charging proses', true),

-- Jawaban untuk Pertanyaan ID 10 (Kunci: A)
(10, 'Vaccum dan Charging Proses', true),
(10, 'Charging Proses', false),
(10, 'Vaccum', false),
(10, 'Proses Reciever', false),
(10, 'Semua jawaban benar', false),

-- Jawaban untuk Pertanyaan ID 11 (Kunci: E)
(11, 'gambar 1', false),
(11, 'gambar 2', false),
(11, 'gambar 3', false),
(11, 'gambar 4', false),
(11, 'gambar 5', true),

-- Jawaban untuk Pertanyaan ID 12 (Kunci: D)
(12, 'gambar 1', false),
(12, 'gambar 2', false),
(12, 'gambar 3', false),
(12, 'gambar 4', true),
(12, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 13 (Kunci: D)
(13, 'gambar 1', false),
(13, 'gambar 2', false),
(13, 'gambar 3', false),
(13, 'gambar 4', true),
(13, 'Semua Jawaban Benar', false),

-- Jawaban untuk Pertanyaan ID 14 (Kunci: D)
(14, '4. Cuci filter dengan sabun (max PH 7)', false),
(14, '4. Kemudian bilas dengan menggunakan air bersih (T = max 40oC (40 derajat celcius))', false),
(14, '4. Cuci filter dengan sabun kemudian bilas dengan menggunakan air bersih', false),
(14, '4. Cuci filter dengan sabun (max PH 7); kemudian bilas dengan menggunakan air bersih (T = max 40oC (40 derajat celcius))', true),
(14, 'Cuci filter dengan sabun kemudian bilas dengan menggunakan air bersih', false),

-- Jawaban untuk Pertanyaan ID 15 (Kunci: A)
(15, 'Proses yang harus dilakukan adalah hidupkan unit AC minimal 15 menit sebelum pengambilan data; pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max; ambil data yang dibutuhkan', true),
(15, 'Pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max; ambil data yang dibutuhkan', false),
(15, 'Ambil data yang dibutuhkan', false),
(15, 'Hidupkan unit AC minimal 15 menit sebelum pengambilan data; pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max', false),
(15, 'Semua jawaban salah', false),

-- Jawaban untuk Pertanyaan ID 16 (Kunci: D)
(16, 'Skala sedang', false),
(16, 'Skala berkala bulanan', false),
(16, 'Skala kecil', false),
(16, 'Skala Pemeliharaan bulanan', true),
(16, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 17 (Kunci: C)
(17, '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply; #Kanebo', false),
(17, '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply', false),
(17, '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply; #Kanebo; #Sabut/Busa; #Sabun', true),
(17, '#Sabut/Busa; #Sabun', false),
(17, 'Semua Jawaban Salah', false),

-- Jawaban untuk Pertanyaan ID 18 (Kunci: E)
(18, 'gambar 1', false),
(18, 'gambar 2', false),
(18, 'gambar 3', false),
(18, 'gambar 4', false),
(18, 'gambar 5', true),

-- Jawaban untuk Pertanyaan ID 19 (Kunci: A)
(19, 'Cross Flow Fan dan selang pembuangan air', true),
(19, 'Cross Flow Fan', false),
(19, 'Selang pembuangan air', false),
(19, 'Cross Flow Fan dan selang pembuangan air out door', false),
(19, 'Fan dan selang pembuangan air', false),

-- Jawaban untuk Pertanyaan ID 20 (Kunci: D)
(20, 'Fan dan Cabinet', false),
(20, 'Cabinet', false),
(20, 'Propeller Fan', false),
(20, 'Propeller Fan dan Cabinet outdoor', true),
(20, 'Propeller dan Cabinet outbow', false),

-- KECANTIKANINSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Jawaban untuk Pertanyaan ID 21 (Kunci: A)
(21, 'Menentukan jenis rambut dan kulit kepala sehingga dapat menentukan perawatan kosmetika yang sesuai', true),
(21, 'Menentukan warna pigmen rambut sehingga dapat menentukan jenis perawatan rambut', false),
(21, 'Meningkatkan peredaran darah dan mengaktifkan syaraf-syaraf pada kulit kepala', false),
(21, 'Menentukan teknik penataan yang sesua jens rambut', false),

-- Jawaban untuk Pertanyaan ID 22 (Kunci: B)
(22, 'egg shampoo', false),
(22, 'lemon shampoo', true),
(22, 'medical shampoo', false),
(22, 'anti hair fall shampoo', false),

-- Jawaban untuk Pertanyaan ID 23 (Kunci: A)
(23, 'bersih dan tak berbau.', true),
(23, 'dingin dan tak berbau', false),
(23, 'berwarna dan tak berbau', false),
(23, 'hangat dan tak berbau', false),

-- Jawaban untuk Pertanyaan ID 24 (Kunci: A)
(24, 'effleurage,tapotage,friction,petrissage, vibration', true),
(24, 'effleurage, rubbing,petrisage,friction', false),
(24, 'effleurage,kneading,vibration,tapotage,petrissage', false),
(24, 'effleurage, scrubing, kneading,petrissage', false),

-- Jawaban untuk Pertanyaan ID 25 (Kunci: D)
(25, 'shampoo', false),
(25, 'solution', false),
(25, 'setting lotion', false),
(25, 'conditioner', true),

-- Jawaban untuk Pertanyaan ID 26 (Kunci: A)
(26, 'blow in', true),
(26, 'blow out', false),
(26, 'blow round', false),
(26, 'blow horizontal', false),

-- Jawaban untuk Pertanyaan ID 27 (Kunci: C)
(27, 'tengah ke ujung rambut', false),
(27, 'pangkal ke bagian tengah', false),
(27, 'pangkal ke ujung rambut', true),
(27, 'tengah ke pangkal rambut', false),

-- Jawaban untuk Pertanyaan ID 28 (Kunci: C)
(28, '5 cm', false),
(28, '15 cm', false),
(28, '30 cm', true),
(28, '20 cm', false),

-- Jawaban untuk Pertanyaan ID 29 (Kunci: D)
(29, 'memperlancar peredaran darah', false),
(29, 'menenangkan urat syaraf', false),
(29, 'meningkatkan dan mempercepat sirkulasi darah', false),
(29, 'mencegah kebotakan', true),

-- Jawaban untuk Pertanyaan ID 30 (Kunci: D)
(30, 'hair dryer', false),
(30, 'hair curler', false),
(30, 'hair clip', false),
(30, 'hair steamer', true),

-- Jawaban untuk Pertanyaan ID 31 (Kunci: B)
(31, 'Sadah', false),
(31, 'Lunak', true),
(31, 'Garam', false),
(31, 'Keras', false),

-- Jawaban untuk Pertanyaan ID 32 (Kunci: B)
(32, 'Berminyak', false),
(32, 'Kering', true),
(32, 'Normal', false),
(32, 'Berketombe', false),

-- Jawaban untuk Pertanyaan ID 33 (Kunci: B)
(33, 'Hairspray', false),
(33, 'Hairshine', true),
(33, 'Hairdryer', false),
(33, 'Haircream', false),

-- Jawaban untuk Pertanyaan ID 34 (Kunci: C)
(34, 'Mencegah timbulnya uban', false),
(34, 'Merawat ketombe', false),
(34, 'Memperlancar peredaran darah', true),
(34, 'Mempersingkat fase anogen', false),

-- Jawaban untuk Pertanyaan ID 35 (Kunci: D)
(35, 'Steamer', false),
(35, 'Accelerator', false),
(35, 'Frekuensi tinggi', false),
(35, 'Droogkap', true),

-- Jawaban untuk Pertanyaan ID 36 (Kunci: B)
(36, 'Personalia', false),
(36, 'Personality', true),
(36, 'Persona', false),
(36, 'Professional ethics', false),

-- Jawaban untuk Pertanyaan ID 37 (Kunci: D)
(37, 'Zat kapur', false),
(37, 'Tembaga', false),
(37, 'Zat besi', false),
(37, 'Fosfor', true),

-- Jawaban untuk Pertanyaan ID 38 (Kunci: C)
(38, 'Two in one', false),
(38, 'Beauty', false),
(38, 'Antiseptik', true),
(38, 'Egg', false),

-- Jawaban untuk Pertanyaan ID 39 (Kunci: A)
(39, 'Egg', true),
(39, 'Dry', false),
(39, 'Cream', false),
(39, 'Beauty', false),

-- Jawaban untuk Pertanyaan ID 40 (Kunci: C)
(40, 'Mencubit', false),
(40, 'Mengetuk', false),
(40, 'Mengusap', true),
(40, 'Meremas', false),

-- TATA BOGA
-- INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Jawaban untuk Pertanyaan ID 41 (Kunci: C)
(41, 'Tepung terigu', false),
(41, 'Telur', false),
(41, 'Vanili', true),
(41, 'Gula', false),

-- Jawaban untuk Pertanyaan ID 42 (Kunci: B)
(42, 'Rendah', false),
(42, 'Tinggi', true),
(42, 'Sedang', false),
(42, 'Cukup', false),

-- Jawaban untuk Pertanyaan ID 43 (Kunci: A)
(43, 'Yeast', true),
(43, 'Telur', false),
(43, 'Gula', false),
(43, 'Margarin', false),

-- Jawaban untuk Pertanyaan ID 44 (Kunci: A)
(44, 'Gula kastor', true),
(44, 'Gula halus', false),
(44, 'Gula palem', false),
(44, 'Gula jawa', false),

-- Jawaban untuk Pertanyaan ID 45 (Kunci: B)
(45, '5 %', false),
(45, '1-2 %', true),
(45, '3 %', false),
(45, '10%', false),

-- Jawaban untuk Pertanyaan ID 46 (Kunci: C)
(46, 'Perasa', false),
(46, 'Membuat warna roti', false),
(46, 'Stabilitator fermentation', true),
(46, 'Fermentasi', false),

-- Jawaban untuk Pertanyaan ID 47 (Kunci: B)
(47, 'Lemak hewani', false),
(47, 'Lemak nabati', true),
(47, 'Perasa mentega', false),
(47, 'Susu', false),

-- Jawaban untuk Pertanyaan ID 48 (Kunci: B)
(48, 'Berasa manis', false),
(48, 'Menahan air', true),
(48, 'Menahan lemak', false),
(48, 'Menahan protein', false),

-- Jawaban untuk Pertanyaan ID 49 (Kunci: A)
(49, 'Bintik hitam pada kulit roti dan berlubang', true),
(49, 'Proses fermentasi lama', false),
(49, 'Roti jadi tidak mengembang', false),
(49, 'Roti jadi terlalu manis', false),

-- Jawaban untuk Pertanyaan ID 50 (Kunci: C)
(50, 'Shortening', false),
(50, 'Mentega putih', false),
(50, 'Korsvet', true),
(50, 'Mentega cair', false),

-- Jawaban untuk Pertanyaan ID 51 (Kunci: B)
(51, 'Mengistirahatkan adonan', false),
(51, 'Mengembangkan adonan', true),
(51, 'Menghilangkan gas pada adonan', false),
(51, 'Membuat adonan licin', false),

-- Jawaban untuk Pertanyaan ID 52 (Kunci: C)
(52, '20ºC', false),
(52, '30ºC', false),
(52, '35ºC', true),
(52, '50ºC', false),

-- Jawaban untuk Pertanyaan ID 53 (Kunci: B)
(53, '1 jam', false),
(53, '35 menit', true),
(53, '2 jam', false),
(53, '1,5 jam', false),

-- Jawaban untuk Pertanyaan ID 54 (Kunci: A)
(54, '200 – 220 ºC', true),
(54, '180 – 190 ºC', false),
(54, '160 – 170 ºC', false),
(54, '140 – 150 ºC', false),

-- Jawaban untuk Pertanyaan ID 55 (Kunci: B)
(55, 'Supaya mudah dilepas', false),
(55, 'Mengakhiri proses pemasakan', true),
(55, 'Supaya warna nya tidak berubah', false),
(55, 'Supaya bentuknya utuh', false),

-- Jawaban untuk Pertanyaan ID 56 (Kunci: D)
(56, 'Choux paste', false),
(56, 'Puff pastry', false),
(56, 'Short paste', false),
(56, 'Roti tawar', true),

-- Jawaban untuk Pertanyaan ID 57 (Kunci: A)
(57, '200 – 220 ºC', true),
(57, '180 – 190 ºC', false),
(57, '160 – 170 ºC', false),
(57, '140 – 150 ºC', false),

-- Jawaban untuk Pertanyaan ID 58 (Kunci: D)
(58, 'Harus mengembang', false),
(58, 'Harus renyah', false),
(58, 'Crumb yg lembut', false),
(58, 'Aroma nya segar', true),

-- Jawaban untuk Pertanyaan ID 59 (Kunci: A)
(59, '30 menit', true),
(59, '20 menit', false),
(59, '10 menit', false),
(59, '5 menit', false),

-- Jawaban untuk Pertanyaan ID 60 (Kunci: A)
(60, 'Pastry yang beragi dan berasa manis', true),
(60, 'Pastry yang renyah', false),
(60, 'Pastry yang menggunakan susu', false),
(60, 'Pastry yang digiling', false),

-- TATA BUSANA
-- INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Jawaban untuk Pertanyaan ID 61 (Kunci: D)
(61, 'Berpakaian mewah seperti pakaian pesta', false),
(61, 'Mempunyai modal besar untuk usaha menjahit', false),
(61, 'Memiliki boutique sendiri dengan mewah', false),
(61, 'Memilihkan model yang sesuai untuk pelanggannya', true),

-- Jawaban untuk Pertanyaan ID 62 (Kunci: B)
(62, 'Menguatkan warnanya', false),
(62, 'Mengetahui penyusutan', true),
(62, 'Mengetahui mutu warnanya', false),
(62, 'Menghilangkan kanjinya', false),

-- Jawaban untuk Pertanyaan ID 63 (Kunci: C)
(63, 'Mumu', false),
(63, 'Strapless', false),
(63, 'Backless', true),
(63, 'Delix plece', false),

-- Jawaban untuk Pertanyaan ID 64 (Kunci: D)
(64, 'Pendek', false),
(64, 'Tinggi', false),
(64, 'Gemuk', false),
(64, 'Langsing', true),

-- Jawaban untuk Pertanyaan ID 65 (Kunci: A)
(65, 'Standar', true),
(65, 'Dasar', false),
(65, 'Ukuran perorangan', false),
(65, 'Drapping', false),

-- Jawaban untuk Pertanyaan ID 66 (Kunci: A)
(66, 'Jatuhnya rok bagus', true),
(66, 'Pengerjaannya cepat', false),
(66, 'Kelimnya rapih', false),
(66, 'Lebar kelim merata', false),

-- Jawaban untuk Pertanyaan ID 67 (Kunci: B)
(67, 'Tegak', false),
(67, 'Rebah', true),
(67, 'Setali', false),
(67, 'Kemeja', false),

-- Jawaban untuk Pertanyaan ID 68 (Kunci: A)
(68, 'Lembut', true),
(68, 'Kaku', false),
(68, 'Lentur', false),
(68, 'Tipis', false),

-- Jawaban untuk Pertanyaan ID 69 (Kunci: C)
(69, 'Untuk merubah model', false),
(69, 'Mempercepat cara menggunting', false),
(69, 'Dapat menentukan ukuran bahan', true),
(69, 'Mempersingkat waktu', false),

-- Jawaban untuk Pertanyaan ID 70 (Kunci: C)
(70, 'Kontruksi', false),
(70, 'Standar', false),
(70, 'Dasar', true),
(70, 'Drapping', false),

-- Jawaban untuk Pertanyaan ID 71 (Kunci: A)
(71, 'Merah, kuning dan biru', true),
(71, 'Biru, merah dan ungu', false),
(71, 'Merah, kuning dan hitam', false),
(71, 'Kuning, biru dan putih', false),

-- Jawaban untuk Pertanyaan ID 72 (Kunci: A)
(72, 'Etika berbusana', true),
(72, 'Pelengkap busana', false),
(72, 'Aksesoris busana', false),
(72, 'Berbusana mengikuti model', false),

-- Jawaban untuk Pertanyaan ID 73 (Kunci: D)
(73, 'Yang paling kecil', false),
(73, 'Pola lengan', false),
(73, 'Potongan-potongan', false),
(73, 'Yang paling besar', true),

-- Jawaban untuk Pertanyaan ID 74 (Kunci: C)
(74, 'Rok lipit hadap', false),
(74, 'Rok lipit sungkup', false),
(74, 'Rok lipit kipas', true),
(74, 'Rok lipit pipih', false),

-- Jawaban untuk Pertanyaan ID 75 (Kunci: A)
(75, 'Krah shiller', true),
(75, 'Krah shanghai', false),
(75, 'Krah kemeja', false),
(75, 'Tailor', false),

-- Jawaban untuk Pertanyaan ID 76 (Kunci: B)
(76, 'Bahan yang akan dipergunakan', false),
(76, 'Ukuran menurut model', true),
(76, 'Kertas yang akan dipergunakan', false),
(76, 'Bentuk tubuh model', false),

-- Jawaban untuk Pertanyaan ID 77 (Kunci: B)
(77, 'Mengecilkan pinggang', false),
(77, 'Mendapatkan ukuran yang tepat', true),
(77, 'Mengetahui letak pinggang', false),
(77, 'Menandai lingkar pinggang', false),

-- Jawaban untuk Pertanyaan ID 78 (Kunci: C)
(78, 'Tali pinggang', false),
(78, 'Kerah', false),
(78, 'Merapikan kampuh', true),
(78, 'Pita', false),

-- Jawaban untuk Pertanyaan ID 79 (Kunci: A)
(79, 'Tata krama dalam hubungan kerja', true),
(79, 'Jaminan untuk kedudukan social yang layak', false),
(79, 'Perlindungan bagi keyamanan kerja', false),
(79, 'Peraturan yang menjamin kesehatan', false),

-- Jawaban untuk Pertanyaan ID 80 (Kunci: A)
(80, 'Pelayan prima', true),
(80, 'Promosi usaha', false),
(80, 'Kerja kelompok', false),
(80, 'Bekerja sendiri', false);


INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(5, 81, 'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya.........', 'skala_likert'),
(5, 82, 'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan penyelenggaraan pelatihan di instansi ini.......', 'skala_likert'),
(5, 83, 'Bagaimana pendapat Saudara tentang kedisiplinan petugas / panitia penyelenggara dalam memberikan pelayanan.........', 'skala_likert'),
(5, 84, 'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan..........', 'skala_likert'),
(5, 85, 'Bagaimana pendapat Saudara tentang petugas bengkel dalam memberikan pelayanan........', 'skala_likert'),
(5, 86, 'Bagaimana pendapat Saudara tentang petugas asrama dalam memberikan pelayanan.........', 'skala_likert'),
(5, 87, 'Bagaimana pendapat Saudara tentang petugas konsumsi dalam memberikan pelayanan.......', 'skala_likert'),
(5, 88, 'Bagaimana pendapat Saudara tentang ketersediaan Sarana dan Prasarana di instansi ini.......', 'skala_likert'),
(5, 89, 'Bagaimana pendapat Saudara tentang kebersihan tempat ibadah (mushola) yang ada di instansi ini.......', 'skala_likert'),
(5, 90, 'Bagaimana pendapat Saudara tentang kebersihan asrama/ lingkungan asrama........', 'skala_likert'),
(5, 91, 'Bagaimana pendapat Saudara tentang kebersihan kamar mandi/lingkungan kamar mandi......', 'skala_likert'),
(5, 92, 'Bagaimana pendapat Saudara tentang kebersihan lingkungan taman dan halaman......', 'skala_likert'),
(5, 93, 'Bagaimana pendapat Saudara tentang kebersihan bengkel / kelas /lingkungan kelas......', 'skala_likert'),
(5, 94, 'Bagiamana pendapat Saudara tentang kebersihan ruang makan/ lingkungan ruang makan ......', 'skala_likert'),
(5, 95, 'Bagaimana pendapat Saudara tentang keamanan pelayanan di instansi ini....', 'skala_likert'),
(5, 96, 'Pesan Dan Kesan :', 'teks_bebas'),
(5, 97, 'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan.', 'skala_likert'),
(5, 98, 'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda.', 'skala_likert'),
(5, 99, 'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan', 'skala_likert'),
(5, 100, 'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan.', 'skala_likert'),
(5, 101, 'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan.', 'skala_likert'),
(5, 102, 'Bagimana pendapat Saudara tentang ketersediaan materi pelatihan', 'skala_likert'),
(5, 103, 'Berapa persen materi yang anda serap', 'skala_likert'),
(5, 104, 'Bagaimana menurut anda apakah perlu penambahan materi pelatihan', 'skala_likert'),
(5, 105, 'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan', 'skala_likert'),
(5, 106, 'Apakah materi-materi pelatihan sangat mendukung kompetensi anda', 'skala_likert'),
(5, 107, 'Pesan Dan Kesan :', 'teks_bebas'),
(5, 108, 'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran', 'skala_likert'),
(5, 109, 'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan', 'skala_likert'),
(5, 110, 'Bagaimana pendapat saudara tentang metode mengajar Instruktur', 'skala_likert'),
(5, 111, 'bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran', 'skala_likert'),
(5, 112, 'bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur', 'skala_likert'),
(5, 113, 'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur', 'skala_likert'),
(5, 114, 'bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan', 'skala_likert'),
(5, 115, 'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan', 'skala_likert'),
(5, 116, 'Pesan dan Kesan', 'teks_bebas');
-- ===================================================================
-- LANGKAH 1: ISI TABEL opsi_jawaban HANYA DENGAN SET JAWABAN MASTER
-- ===================================================================

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Set Opsi 1: Skala "Memuaskan" (Master Pertanyaan ID: 81)
(81, 'Tidak Memuaskan', 0),
(81, 'Kurang Memuaskan', 0),
(81, 'Memuaskan', 0),
(81, 'Sangat Memuaskan', 0),

-- Set Opsi 2: Skala "Bermanfaat" (Master Pertanyaan ID: 98)
(98, 'Tidak Bermanfaat', 0),
(98, 'Kurang Bermanfaat', 0),
(98, 'Bermanfaat', 0),
(98, 'Sangat Bermanfaat', 0),

-- Set Opsi 3: Skala "Persentase" (Master Pertanyaan ID: 103)
(103, '25%', 0),
(103, '50%', 0),
(103, '75%', 0),
(103, '100%', 0),

-- Set Opsi 4: Skala "Perlu" (Master Pertanyaan ID: 104)
(104, 'Tidak Perlu', 0),
(104, 'Kurang Perlu', 0),
(104, 'Perlu', 0),
(104, 'Sangat Perlu', 0),

-- Set Opsi 5: Skala "Mendukung" (Master Pertanyaan ID: 106)
(106, 'Tidak Mendukung', 0),
(106, 'Kurang Mendukung', 0),
(106, 'Mendukung', 0),
(106, 'Sangat Mendukung', 0),

-- Set Opsi 6: Skala "Disiplin" (Master Pertanyaan ID: 109)
(109, 'Tidak Disiplin', 0),
(109, 'Kurang Disiplin', 0),
(109, 'Disiplin', 0),
(109, 'Sangat Disiplin', 0),

-- Set Opsi 7: Skala "Rapi" (Master Pertanyaan ID: 112)
(112, 'Tidak rapi', 0),
(112, 'Kurang Rapi', 0),
(112, 'Rapi', 0),
(112, 'Sangat Rapi', 0),

-- Set Opsi 8: Skala "Baik" (Master Pertanyaan ID: 113)
(113, 'Tidak baik', 0),
(113, 'Kurang baik', 0),
(113, 'baik', 0),
(113, 'Sangat baik', 0);


-- ===================================================================
-- LANGKAH 2: ISI TABEL PIVOT pertanyaan_opsi_link
-- ===================================================================

INSERT INTO pivot_jawaban (pertanyaan_id, template_pertanyaan_id) VALUES
-- Pertanyaan yang menggunakan opsi dari master ID 81 (Skala Memuaskan)
(82, 81),
(83, 81),
(84, 81),
(85, 81),
(86, 81),
(87, 81),
(88, 81),
(89, 81),
(90, 81),
(91, 81),
(92, 81),
(93, 81),
(94, 81),
(95, 81),
(97, 81),
(99, 81),
(100, 81),
(101, 81),
(102, 81),
(108, 81),
(110, 81),
(111, 81),
(114, 81),
(115, 81),

-- Pertanyaan yang menggunakan opsi dari master ID 104 (Skala Perlu)
(105, 104);

-- ===================================================================
-- KUNCI JAWABAN BARU 09/09/2025
-- ===================================================================
INSERT INTO `tes` (`id`, `judul`, `deskripsi`, `tipe`, `sub_tipe`, `kompetensi_id`, `pelatihan_id`, `durasi_menit`, `created_at`, `updated_at`) VALUES
(6, 'Post-Test Videografi', 'Tes akhir untuk mengukur pemahaman materi Videografi.', 'tes', 'post-test', 5, 1, 30, NOW(), NOW()),
(7, 'Post-Test PLC', 'Tes akhir untuk mengukur pemahaman materi Programmable Logic Controllers (PLC).', 'tes', 'post-test', 6, 1, 30, NOW(), NOW()),
(8, 'Post-Test Fotografi', 'Tes akhir untuk mengukur pemahaman materi Fotografi Produk.', 'tes', 'post-test', 7, 1, 30, NOW(), NOW()),
(9, 'Survei Kepuasan Pelatihan - MTU', 'Survei untuk mengumpulkan umpan balik mengenai penyelenggaraan pelatihan.', 'survei', NULL, 1, 1, NULL, NOW(), NOW());

-- PERTANYAAN

-- Videografi
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(6, 117, 'Mengapa videografi menjadi semakin penting dalam pendidikan modern ?', 'pilihan_ganda'),
(6, 118, 'Berikut ini adalah manfaat penggunaan video dalam pembelajaran, kecuali...', 'pilihan_ganda'),
(6, 119, 'Istilah yang merujuk pada jumlah gambar diam yang ditampilkan per detik dalam video adalah...', 'pilihan_ganda'),
(6, 120, 'Rasio aspek video widescreen standar yang paling umum digunakan saat ini adalah...', 'pilihan_ganda'),
(6, 121, 'Jenis shot yang menampilkan objek secara keseluruhan dengan latar belakang yang luas, sering digunakan untuk membuka video adalah…', 'pilihan_ganda'),
(6, 122, 'Sudut pengambilan gambar dari bawah objek, yang membuat objek terlihat lebih besar dan dominan disebut...', 'pilihan_ganda'),
(6, 123, 'Pergerakan kamera secara horizontal dari kiri ke kanan atau sebaliknya disebut...', 'pilihan_ganda'),
(6, 124, 'Aturan komposisi visual yang membagi bingkai gambar menjadi sembilan bagian sama besar untuk menempatkan objek penting adalah...', 'pilihan_ganda'),
(6, 125, 'Jenis pencahayaan yang paling baik dan mudah dimanfaatkan untuk videografi adalah...', 'pilihan_ganda'),
(6, 126, 'Istilah untuk cahaya utama dalam three-point lighting yang berfungsi sebagai sumber cahaya terkuat dan membentuk bayangan adalah...', 'pilihan_ganda'),
(6, 127, 'Mikrofon kecil yang biasanya dijepitkan pada pakaian dan ideal untuk merekam suara pembicara adalah...', 'pilihan_ganda'),
(6, 128, 'Mengapa kualitas audio yang baik sangat penting dalam video?', 'pilihan_ganda'),
(6, 129, 'Dalam penyuntingan video dasar, timeline berfungsi untuk...', 'pilihan_ganda'),
(6, 130, 'Teknik penyuntingan video dasar yang paling umum untuk menggabungkan dua klip video secara langsung tanpa transisi adalah...', 'pilihan_ganda'),
(6, 131, 'Langkah awal yang paling penting dalam membuat video pembelajaran yang efektif adalah…', 'pilihan_ganda'),
(6, 132, 'Durasi video pembelajaran yang ideal sebaiknya...', 'pilihan_ganda'),
(6, 133, 'Platform berbagi video online yang paling populer dan banyak digunakan untuk video pembelajaran adalah…', 'pilihan_ganda'),
(6, 134, 'Format file video yang paling umum dan direkomendasikan untuk diunggah secara online adalah...', 'pilihan_ganda'),
(6, 135, 'Etika penting yang harus diperhatikan dalam produksi video pendidikan kecuali...', 'pilihan_ganda'),
(6, 136, 'Dalam teknik pencahayaan tiga titik (three-point lighting), lampu yang berfungsi untuk mengurangi bayangan keras yang dihasilkan oleh lampu utama (key light) dan memberikan detail pada area gelap adalah...', 'pilihan_ganda');

-- PLC
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(7, 137, 'Programmable Logic Controllers (PLC) adalah', 'pilihan_ganda'),
(7, 138, 'Berdasarkan namanya konsep PLC adalah sebagai berikut :Penjelasan di atas digambarkan. Pada gambar dibawah ini :', 'pilihan_ganda'),
(7, 139, 'Kode 1a dan 1b adalah', 'pilihan_ganda'),
(7, 140, 'Input dan Output pada PLC dengan Type CP1E :', 'pilihan_ganda'),
(7, 141, 'Bentuk / Macam Program Kontrol adalah', 'pilihan_ganda'),
(7, 142, 'Sebuah motor listrik di kontrol oleh tombol ……. dan ………', 'pilihan_ganda'),
(7, 143, 'Ledder Diagram yang sudah dibuat dan disimpan disebuah file harus ditransfer (download) kedalam memori PLC untuk bisa di jalankan pada PLC. Syarat dan ketentuan transfer program ke PLCadalah :', 'pilihan_ganda'),
(7, 144, 'Tampilan di bawah ini adalah proses :', 'pilihan_ganda'),
(7, 145, 'Proses mentransfer program ada 2 macam salah satunya adalah : Mentransfer dari PC/Laptop ke PLC tujuannya untuk mengirim program kontrol yang telah dibuat untuk dioprasikan pada PLC. Caranya klik PLC pilih Transfer dan pilih to PLC atau tekan Ctrl + T pada keyboard seperti gambar berikut. Gambar dibawah adalah Proses :', 'pilihan_ganda'),
(7, 146, 'Gambar di bawah ini adalah proses :', 'pilihan_ganda'),
(7, 147, 'TIMER adalah salah satu fasilitas yang ada pada sebuah PLC. Iya identik dan punya fungsi yang sama seperti :', 'pilihan_ganda'),
(7, 148, 'COUNTER (CNT) Counter adalah salah satu fasilitas yang da pada sebuah PLC yang mempunyai 2 masukan yakni :', 'pilihan_ganda'),
(7, 149, 'Counter berfungsi sebagai penghitung dalam program kontrol Counter mempunyai keluaran Output yang berupa :', 'pilihan_ganda'),
(7, 150, 'Gambar di bawah ini adalah :', 'pilihan_ganda'),
(7, 151, 'DIFU (Differentiate Up) dan DIFD (Differentiate Down) adalah salah satu bagian dari Bit Control Instructions.:', 'pilihan_ganda'),
(7, 152, 'Perbedaan DIFU dan DIFD Pada Keluaran (Kontak NO dan Ncnya)', 'pilihan_ganda'),
(7, 153, 'Keterangan gambar di bawah ini adalah :', 'pilihan_ganda'),
(7, 154, 'Clock Pulse Bit :', 'pilihan_ganda'),
(7, 155, 'Clock Pulse Bit identik dengan kontak NO/NC yang bekerja terus menerus memberi masukan 1 dan 0 (bekerja ON dan OFF) secara otomatis :', 'pilihan_ganda'),
(7, 156, 'Untuk menggambarkan penjelasan diatas maka buatlah program kontrol yang menggunakan Clock Puls Bit dengan satuan detik seperti gambar berikut ini', 'pilihan_ganda');

-- FOTOGRAFI
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(8, 157, 'Proses pra produksi dalam fotografi produk adalah ketika …', 'pilihan_ganda'),
(8, 158, 'Salah satu pengaruh foto produk terhadap kegiatan branding adalah …', 'pilihan_ganda'),
(8, 159, 'Rafathar ditunjuk sebagai fotografer di kelasnya, namun Ketika foto di dalam kelas, foto yang dihasilkan kamera mirrorless nya tampak gelap, yang harus dilakukan Rafathar adalah …', 'pilihan_ganda'),
(8, 160, 'Di bawah ini yang bukan termasuk konsep fotografi produk adalah :', 'pilihan_ganda'),
(8, 161, 'Yang bukan termasuk komposisi fotografi adalah …', 'pilihan_ganda'),
(8, 162, 'Dimanakah letak lampu yang digunakan pada foto dibawah ini …', 'pilihan_ganda'),
(8, 163, 'Renatta adalah fotografer professional. Dia selalu mengedit foto produk setelah dia melakukan proses foto. Proses pengeditan dalam fotografi masuk ke dalam proses …', 'pilihan_ganda'),
(8, 164, 'Berikut adalah hal hal yang harus dihindari saat foto produk, salah satunya adalah …', 'pilihan_ganda'),
(8, 165, 'Apperture pada settingan kamera digunakan untuk mengatur …', 'pilihan_ganda'),
(8, 166, 'Erick ditunjuk PSSI sebagai fotografer tim nasional sepakbola, yang perlu Erick setting di kameranya agar Ketika pemain berlari kencang dia bisa membuat foto tersebut menjadi freeze adalah ....', 'pilihan_ganda'),
(8, 167, 'Apa yang diperlukan fotografer agar memiliki banyak klien …', 'pilihan_ganda'),
(8, 168, 'Manakah settingan Apperture kamera yang benar ketika kita menginginkan foto dengan latar belakang yang blur / bokeh …', 'pilihan_ganda'),
(8, 169, 'Mana di bawah ini yang merupakan aplikasi untuk mengedit foto yang sering digunakan oleh fotografer …', 'pilihan_ganda'),
(8, 170, 'Manakah yang bukan kegunaan lighting pada fotografi produk …', 'pilihan_ganda'),
(8, 171, 'Yang tidak termasuk dalam workflow fotografer produk adalah …', 'pilihan_ganda'),
(8, 172, 'Jika ingin membuat foto tampak bokeh/blur, apa yang perlu kita setting …', 'pilihan_ganda'),
(8, 173, 'Saya ingin memotret objek yang bergerak menjadi tampak freeze/beku, apa yang perlu saya setting di kamera …', 'pilihan_ganda'),
(8, 174, 'Proses menaikan warna pada aplikasi photoshop adalah …', 'pilihan_ganda'),
(8, 175, 'ISO memiliki kegunaan untuk mengatur …', 'pilihan_ganda'),
(8, 176, 'Dibawah ini yang bukan termasuk proses produksi pada fotografi produk adalah …', 'pilihan_ganda');

-- ===================================================================
-- OPSI JAWABAN 
-- ===================================================================

-- Videografi
INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(117, 'Karena buku teks sudah tidak relevan lagi', false), (117, 'Karena siswa hanya suka belajar dengan video', false), (117, 'Karena video lebih menarik dan efektif untuk menyampaikan informasi visual dan kompleks', true), (117, 'Karena membuat video lebih murah daripada membuat materi cetak', false), (117, 'Karena semua guru harus menjadi YouTuber', false),
(118, 'Meningkatkan pemahaman dan daya ingat siswa', false), (118, 'Membuat materi abstrak menjadi lebih konkret dan mudah dipahami', false), (118, 'Menyajikan pengalaman belajar yang otentik dan menarik', false), (118, 'Menggantikan peran guru sepenuhnya di dalam kelas', true), (118, 'Mengakomodasi gaya belajar siswa yang beragam', false),
(119, 'Resolusi', false), (119, 'Aspect Ratio', false), (119, 'Frame Rate', true), (119, 'Bit Rate', false), (119, 'White Balance', false),
(120, '4:3', false), (120, '3:4', false), (120, '1:1', false), (120, '9 :16', false), (120, '16:9', true),
(121, 'Close-Up (CU)', false), (121, 'Medium Shot (MS)', false), (121, 'Long Shot (LS)', false), (121, 'Extreme Long Shot (ELS)', true), (121, 'Over-the-Shoulder Shot (OTS)', false),
(122, 'Eye-Level Angle', false), (122, 'High Angle', false), (122, 'Low Angle', true), (122, 'Dutch Angle', false), (122, 'Bird''s-Eye View', false),
(123, 'Tilt', false), (123, 'Pan', true), (123, 'Zoom', false), (123, 'Dolly', false), (123, 'Crane', false),
(124, 'Leading Lines', false), (124, 'Frame within a Frame', false), (124, 'Rule of Thirds', true), (124, 'Negative Space', false), (124, 'Simetri', false),
(125, 'Lampu neon', false), (125, 'Lampu pijar', false), (125, 'Cahaya lilin', false), (125, 'Cahaya alami (matahari)', true), (125, 'Lampu flash kamera', false),
(126, 'Fill Light', false), (126, 'Back Light', false), (126, 'Key Light', true), (126, 'Rim Light', false), (126, 'Hair Light', false),
(127, 'Mikrofon shotgun', false), (127, 'Mikrofon handheld', false), (127, 'Mikrofon bawaan kamera', false), (127, 'Mikrofon lavalier', true), (127, 'Mikrofon boom', false),
(128, 'Karena video tanpa audio tidak bisa diputar', false), (128, 'Karena audio yang bagus lebih penting dari visual yang bagus', false), (128, 'Karena audio yang jelas membantu penonton memahami pesan video', true), (128, 'Karena audio yang bagus membuat video terlihat lebih profesional', false), (128, 'Karena semua jawaban benar', false),
(129, 'Menambahkan efek transisi', false), (129, 'Mengimpor footage video dan audio', false), (129, 'Menyusun dan memotong klip video secara berurutan', true), (129, 'Memberikan koreksi warna pada video', false), (129, 'Menambahkan teks dan grafis', false),
(130, 'Fade', false), (130, 'Dissolve', false), (130, 'Wipe', false), (130, 'Cut', true), (130, 'Zoom', false),
(131, 'Memilih musik latar yang menarik', false), (131, 'Membuat storyboard dan perencanaan video', true), (131, 'Membeli peralatan videografi yang mahal', false), (131, 'Langsung merekam video tanpa persiapan', false), (131, 'Menggunakan efek transisi yang rumit', false),
(132, 'Lebih dari 60 menit', false), (132, 'Antara 30-60 menit', false), (132, 'Antara 15-30 menit', false), (132, 'Kurang dari 10 menit dan fokus pada poin utama', true), (132, 'Tidak ada batasan durasi, yang penting materinya lengkap', false),
(133, 'Vimeo', false), (133, 'Dailymotion', false), (133, 'Facebook Video', false), (133, 'Instagram Video', false), (133, 'YouTube', true),
(134, 'AVI', false), (134, 'MOV', false), (134, 'WMV', false), (134, 'MP4', true), (134, 'FLV', false),
(135, 'Menghormati privasi siswa', false), (135, 'Memastikan akurasi informasi', false), (135, 'Menggunakan musik dan gambar bebas royalti', false), (135, 'Membuat video yang provokatif agar viral', true), (135, 'Menghindari konten yang diskriminatif', false),
(136, 'Key Light', false), (136, 'Fill Light', true), (136, 'Back Light', false), (136, 'Hair Light', false), (136, 'Rim Light', false);

-- PLC
INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(137, 'Komputer elektronik yang mudah digunakan', false), (137, 'Komputer elektronik yang memiliki fungsi kendali untuk berbagai tipe dan tingkat kesulitan yang beraneka ragam', false), (137, 'Komputer elektronik yang mudah digunakan (user friendly) yang memiliki fungsi kendali', false), (137, 'Komputer elektronik yang mudah digunakan (user friendly) yang memiliki fungsi kendali untuk berbagai tipe dan tingkat kesulitan yang beraneka ragam', false), (137, 'Komputer elektronik yang mudah digunakan (user friendly) yang memiliki fungsi kendali untuk berbagai tipe dan tingkat kesulitan yang beraneka ragam', true),
(138, 'Seperangkat  Software', false), (138, 'Seperangkat Computer', false), (138, 'Software', false), (138, 'Seperangkat Computer Software', true), (138, 'Computer Software', false),
(139, 'Input PLC :  Input PLC (Push Button, sensor, Limit Swith, dll) , b. Input sumber 220 Volt AC', true), (139, 'Alamat Input : a. Alamat input PLC, b. Keterangan input tegangan 220 Volt AC', false), (139, 'Output PLC : a. Output PLC, b. Output sumber PLC Volt 24 DC', false), (139, 'Alamat Output : a. Alamat Output PLC, b. Keterangan Output tegangan 24 Volt DC', false), (139, 'Baterai PLC', false),
(140, 'Input  : 14 Buah , Output : 8 Buah', false), (140, 'Input  : 12 Buah ,  Output : 8 Buah', true), (140, 'Input  : 10 Buah ,  Output : 6 Buah', false), (140, 'Input  : 10 Buah ,  Output : 10 Buah', false), (140, 'Semua Jawaban Salah', false),
(141, 'Function Blok Diagram, Flowchat', false), (141, 'Input PLC :  Input PLC (Push Button, sensor, Limit Swith, dll) , b. Input sumber 220 Volt AC', false), (141, 'Ladder Diagram, Kode Mnemonic, Function Blok Diagram, Flowchat', true), (141, 'Ladder Mnemonic, Function Blok', false), (141, 'Ladder Function Blok Diagram, Flowchat', false),
(142, 'PB Start', false), (142, 'PB Start 1 dan PB Stop 1', true), (142, 'PB Start 1 dan 2', false), (142, 'PB Start 1 dan PB Stop 1 dan 2', false), (142, 'Semua jawaban salah', false),
(143, '• Setting File program kontrol harus sama dengan type PLC , • kabel USB yang menghubungkan laptop/CPU dengan PLC sudah terhubung dengan baik, • PLC sudah dalam keadaan ON (menyala), • Komputer dan PLC sudah Online caranya : Klik simbol   pada bar CX Programmer atau tekan Ctrl + W secara bersamaan pada keyboard.', true), (143, '• Kabel USB yang menghubungkan laptop/CPU dengan PLC sudah terhubung dengan baik , • PLC sudah dalam keadaan ON (menyala) , • Komputer dan PLC sudah Online caranya : Klik simbol   pada bar CX Programmer atau tekan Ctrl + W secara bersamaan pada keyboard', false), (143, '• PLC sudah dalam keadaan ON (menyala), • Komputer dan PLC sudah Online caranya : Klik simbol   pada bar CX Programmer atau tekan Ctrl + W secara bersamaan pada keyboard.', false), (143, '• Setting File program kontrol harus sama dengan type PLC, • Kabel USB yang menghubungkan laptop/CPU dengan PLC sudah terhubung dengan baik', false), (143, '• Setting File program kontrol harus sama dengan type PLC, • Kabel USB yang menghubungkan laptop/CPU dengan PLC sudah terhubung dengan baik,  • PLC sudah dalam keadaan ON (menyala)', false),
(144, 'Tampilan belum terhubung komunikasi PLC dan PC', false), (144, 'Tampilan setelah terhubung komunikasi PLC', false), (144, 'Tampilan setelah terhubung komunikasi PC', false), (144, 'Tampilan setelah terhubung komunikasi PLC dan PC', true), (144, 'Tampilan setelah terhubung komunikasi', false),
(145, 'Transfer From PC', false), (145, 'Transfer From PLC', false), (145, 'Transfer From PC to PC', false), (145, 'Transfer From PLC to PLC', false), (145, 'Transfer From PC to PLC', true),
(146, 'Transfer From PLC', false), (146, 'Transfer From PLC to PC', true), (146, 'Transfer From  PC', false), (146, 'Transfer From PC to Sofware', false), (146, 'Transfer From Hardware PLC', false),
(147, 'TDR (delay Relay)', false), (147, 'TDR (Time delay Relay)', true), (147, 'TDR (Time deferensial Relay)', false), (147, 'TDR (Time delly Relay)', false), (147, 'TDR (Time dely Relay)', false),
(148, 'Counter Input dan Reset Input', true), (148, 'Counter Output dan Reset Input', false), (148, 'Counter Input dan Reset Output', false), (148, 'Counter Output dan Reset Out Put', false), (148, 'Counter Input', false),
(149, 'Kontak 2 NO (Normally Open) dan NC (Normally Clouse)', false), (149, 'Kontak 2 NC (Normally Clouse)', false), (149, 'Kontak NC (Normally Clouse)', false), (149, 'Kontak NO (Normally Open)', false), (149, 'Kontak NO (Normally Open) dan NC (Normally Clouse)', true),
(150, 'Relay 1 sebagai Relay Bantu', false), (150, 'Relay 2 sebagai Relay Bantu', false), (150, 'Relay 3 sebagai Relay Bantu', false), (150, 'Relay Dalam sebagai Relay Bantu', true), (150, 'Relay Dalam sebagai Relay Utama', false),
(151, 'Keduanya mempunyai cara kerja tidak sama', false), (151, 'Mempunyai cara kerja hampir sama', false), (151, 'Keduanya mempunyai cara khusus', false), (151, 'Kerjanya hampir sama', false), (151, 'Keduanya mempunyai cara kerja hampir sama', true),
(152, 'DIFU Pada Saat Masukan (ON) maka kontak NO terhubung sesaat dan Kontak NC terputus sesaat', false), (152, 'DIFD Pada saat masukan (ON) maka kontak NO belum terhubung sesaat dan  kontak NC nya belum terputus sesaat, baru pada saat masukan (OFF) maka kontak NO terhubung sesaat dan kontak NC terputus sesaat', false), (152, 'DIFU Pada Saat Masukan (ON) maka kontak NO terhubung sesaat dan Kontak NC', false), (152, 'DIFD Pada saat masukan (ON) maka kontak NO belum terhubung sesaat dan  kontak NC nya belum terputus sesaat', false), (152, 'DIFU Pada Saat Masukan (ON) maka kontak NO terhubung sesaat dan Kontak NC terputus sesaat dan DIFD Pada saat masukan (ON) maka kontak NO belum terhubung sesaat dan  kontak NC nya belum terputus sesaat, baru pada saat masukan (OFF) maka kontak NO terhubung sesaat dan kontak NC terputus sesaat', true),
(153, 'Output DIFU', true), (153, 'Output DIFU dan DIFD', false), (153, 'Output DIFD', false), (153, 'Output DIFD dan DIF UP', false), (153, 'Output DIFU dan DIF DOWN', false),
(154, 'Adalah salah satu fasilitas yang dapat mengontrol sebuah keluaran Input', false), (154, 'Adalah salah satu fasilitas yang dapat mengontrol sebuah keluaran Input, Output', false), (154, 'Adalah salah satu fasilitas yang mengakses Output', false), (154, 'Mengontrol sebuah keluaran DIFU', false), (154, 'Adalah salah satu fasilitas yang dapat mengontrol sebuah keluaran Output', true),
(155, 'Dalam satuan  Minute (menit).', false), (155, 'Dalam satuan Secon(detik)', false), (155, 'Dalam satuan Jam', false), (155, 'Dalam satuan Secon(detik) atau Minute (menit)', true), (155, 'Dalam hitungan Minute (menit)', false),
(156, '• Buatlah File simpan dengan nama Clock Puls, • Selesai membuat program kontrol diatas, simpanlah dengan memiih save,  • Transfer ke PLC dan operasikan, • Amati Outputnya dan coba jelaskan secara singkat', true), (156, '• Selesai membuat program kontrol diatas, simpanlah dengan memiih save, • Transfer ke PLC dan operasikan, • Amati Outputnya dan coba jelaskan secara singkat', false), (156, 'Transfer ke PLC dan operasikan  dan Amati Outputnya dan coba jelaskan secara singkat.', false), (156, 'Transfer ke PLC dan Amati Outputnya dan coba jelaskan secara singkat', false), (156, 'Semua Jawaban Salah', false);

-- FOTOGRAFI
INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(157, 'Mulai proses foto', false), (157, 'Mengobrol sambil minum kopi', false), (157, 'Saat pengeditan foto', false), (157, 'Proses membuat konsep foto', true), (157, 'Membersihkan lensa kamera', false),
(158, 'Membuat fotografer lebih terkenal', false), (158, 'Membuat rasa produk yang difoto lebih enak', false), (158, 'Membuat produk yang difoto memiliki nilai jual lebih', true), (158, 'Membuat fotografer jadi lebih pintar memasak', false), (158, 'Semua jawaban benar', false),
(159, 'Merubah settingan ISO', true), (159, 'Membeli kamera baru', false), (159, 'Membayar fotografer profesional', false), (159, 'Merubah settingan shutter speed', false), (159, 'Menyuruh teman temannya foto di luar kelas', false),
(160, 'Bright Mood Concept', false), (160, 'Havana Havana Concept', true), (160, 'Levitation Concept', false), (160, 'Flatlay Concept', false), (160, 'Semua jawaban benar', false),
(161, 'Carbonara Mashed Potato', true), (161, 'Golden Triangle', false), (161, 'Rule of Third', false), (161, 'Fibonacci Spiral', false), (161, 'Phi Grid', false),
(162, 'Depan kanan produk', false), (162, 'Samping kiri produk', false), (162, 'Atas produk', false), (162, 'Depan kiri produk', false), (162, 'Belakang kiri produk', true),
(163, 'Pra produksi', false), (163, 'Post Pone', false), (163, 'Reimbush', false), (163, 'Post produksi', true), (163, 'Produksi', false),
(164, 'Konsep yang jelas', false), (164, 'Menggunakan flash internal di kamera', true), (164, 'Menyiapkan properti', false), (164, 'Membersihkan lensa', false), (164, 'Menyiapkan alas foto', false),
(165, 'Bukaan pada lensa', true), (165, 'Suhu kamera', false), (165, 'Kecepatan', false), (165, 'Tingkat keterangan pada hasil foto', false), (165, 'Hati seseorang', false),
(166, 'Shutter Speed', true), (166, 'Apperture', false), (166, 'Posisi', false), (166, 'ISO', false), (166, 'Flash', false),
(167, 'Good looking', false), (167, 'Kamera bagus', false), (167, 'Bersikap cool', false), (167, 'Komunikasi yang baik', true), (167, 'Properti yang banyak', false),
(168, 'f 2.8', true), (168, 'f 7', false), (168, 'f 20', false), (168, 'f 9', false), (168, 'Tidak ada jawaban yang benar', false),
(169, 'Adobe Premiere', false), (169, 'Davinci Code', false), (169, 'Capcut PC', false), (169, 'Adobe Photoshop', true), (169, 'Corel Draw', false),
(170, 'Memberikan dimensi pada produk', false), (170, 'Terlihat professional', true), (170, 'Memberikan cahaya yang diinginkan', false), (170, 'Mengatur sumber cahaya', false), (170, 'Membantu ketika foto di dalam ruangan yang minim cahaya', false),
(171, 'Pra Produksi', false), (171, 'Post Produksi', false), (171, 'Pre Eliminasi', true), (171, 'Produksi', false), (171, 'Foto Taking', false),
(172, 'Lensa', false), (172, 'Lighting', false), (172, 'ISO', false), (172, 'Apperture', true), (172, 'Shutter Speed', false),
(173, 'Komunikasi', false), (173, 'Shutter Speed', true), (173, 'Objek', false), (173, 'ISO', false), (173, 'Apperture', false),
(174, 'Contrast', false), (174, 'Saturation', true), (174, 'Brightness', false), (174, 'Crop', false), (174, 'Pen Tool', false),
(175, 'Tingkat kecerahan', true), (175, 'Tingkat kecepatan', false), (175, 'Tingkat detail', false), (175, 'Tingkat blur', false), (175, 'Tingkat kepercayaan diri', false),
(176, 'Menyiapkan properti', false), (176, 'Setting kamera', false), (176, 'Memilih alas foto', false), (176, 'Menentukan harga', true), (176, 'Membersihkan lensa', false);



-- monev MTU
INSERT INTO pertanyaan (tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(9, 177, 'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya.........', 'skala_likert'),
(9, 178, 'Bagaimana pendapat Saudara tentang kedisiplinan  penyelenggara dalam memberikan pelayanan.........', 'skala_likert'),
(9, 179, 'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan..........', 'skala_likert'),
(9, 180, 'Bagaimana pendapat Saudara tentang petugas pendamping kegiatan dalam memberikan pelayanan........', 'skala_likert'),
(9, 181, 'Bagaimana pendapat Saudara tentang ketersediaan konsumsi dalam kegiatan pelatihan vokasi melalui Mobile Training Unit (MTU).......', 'skala_likert'),
(9, 182, 'Pesan Dan Kesan :', 'teks_bebas'),
(9, 183, 'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan.', 'skala_likert'),
(9, 184, 'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda.', 'skala_likert'),
(9, 185, 'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan', 'skala_likert'),
(9, 186, 'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan.', 'skala_likert'),
(9, 187, 'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan.', 'skala_likert'),
(9, 188, 'Bagaimana pendapat Saudara tentang ketersediaan materi pelatihan', 'skala_likert'),
(9, 189, 'Berapa persen materi yang anda serap', 'skala_likert'),
(9, 190, 'Bagaimana menurut anda apakah perlu penambahan materi pelatihan', 'skala_likert'),
(9, 191, 'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan', 'skala_likert'),
(9, 192, 'Apakah materi-materi pelatihan sangat mendukung kompetensi anda', 'skala_likert'),
(9, 193, 'Pesan Dan Kesan :', 'teks_bebas'),
(9, 194, 'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran', 'skala_likert'),
(9, 195, 'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan', 'skala_likert'),
(9, 196, 'Bagaimana pendapat saudara tentang metode mengajar Instruktur', 'skala_likert'),
(9, 197, 'bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran', 'skala_likert'),
(9, 198, 'bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur', 'skala_likert'),
(9, 199, 'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur', 'skala_likert'),
(9, 200, 'bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan', 'skala_likert'),
(9, 201, 'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan', 'skala_likert'),
(9, 202, 'Intruktur terfavorit', 'teks_bebas'),
(9, 203, 'Pesan dan Kesan', 'teks_bebas');

-- ===================================================================
-- LANGKAH 1 (SURVEI MTU): ISI OPSI JAWABAN MASTER
-- ===================================================================
INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
-- Set Opsi 1: Skala "Memuaskan" (Master Pertanyaan ID: 177)
(177, 'Tidak Memuaskan', 0), (177, 'Kurang Memuaskan', 0), (177, 'Memuaskan', 0), (177, 'Sangat Memuaskan', 0),
-- Set Opsi 2: Skala "Bermanfaat" (Master Pertanyaan ID: 184)
(184, 'Tidak Bermanfaat', 0), (184, 'Kurang Bermanfaat', 0), (184, 'Bermanfaat', 0), (184, 'Sangat Bermanfaat', 0),
-- Set Opsi 3: Skala "Persentase" (Master Pertanyaan ID: 189)
(189, '25%', 0), (189, '50%', 0), (189, '75%', 0), (189, '100%', 0),
-- Set Opsi 4: Skala "Perlu" (Master Pertanyaan ID: 190)
(190, 'Tidak Perlu', 0), (190, 'Kurang Perlu', 0), (190, 'Perlu', 0), (190, 'Sangat Perlu', 0),
-- Set Opsi 5: Skala "Mendukung" (Master Pertanyaan ID: 192)
(192, 'Tidak Mendukung', 0), (192, 'Kurang Mendukung', 0), (192, 'Mendukung', 0), (192, 'Sangat Mendukung', 0),
-- Set Opsi 6: Skala "Disiplin" (Master Pertanyaan ID: 195)
(195, 'Tidak Disiplin', 0), (195, 'Kurang Disiplin', 0), (195, 'Disiplin', 0), (195, 'Sangat Disiplin', 0),
-- Set Opsi 7: Skala "Rapi" (Master Pertanyaan ID: 198)
(198, 'Tidak Rapi', 0), (198, 'Kurang Rapi', 0), (198, 'Rapi', 0), (198, 'Sangat Rapi', 0),
-- Set Opsi 8: Skala "Baik" (Master Pertanyaan ID: 199)
(199, 'Tidak Baik', 0), (199, 'Kurang Baik', 0), (199, 'Baik', 0), (199, 'Sangat Baik', 0);


-- ===================================================================
-- LANGKAH 2 (SURVEI MTU): ISI TABEL PIVOT pivot_jawaban
-- ===================================================================

INSERT INTO pivot_jawaban (pertanyaan_id, template_pertanyaan_id) VALUES
-- Pertanyaan yang menggunakan opsi dari master ID 177 (Skala Memuaskan)
(178, 177), (179, 177), (180, 177), (181, 177), (183, 177), (185, 177), (186, 177), (187, 177), (188, 177), (194, 177), (196, 177), (197, 177), (200, 177), (201, 177),
-- Pertanyaan yang menggunakan opsi dari master ID 190 (Skala Perlu)
(191, 190);