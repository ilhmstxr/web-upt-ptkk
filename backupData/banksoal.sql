/* =====================================================================
   BANK SOAL TERPUSAT — MJC / UPT PTKK (FINAL)
   - Akselerasi (Post Test)
   - Reguler (Post Test)
   ===================================================================== */

START TRANSACTION;

/* =====================================================================
   A. POST TEST AKSELERASI MJC - UPT PTKK
   ===================================================================== */

/* ---------------------------------------------------------------------
   A1. TES (Akselerasi)
   --------------------------------------------------------------------- */
INSERT INTO tes (id, judul, tipe, pelatihan_id, kompetensi_pelatihan_id, durasi_menit) VALUES
(1001, 'Post Test Aksel MJC - Fotografi',        'post-test', 1, 101, 60),
(1002, 'Post Test Aksel MJC - Desain Grafis',    'post-test', 1, 102, 60),
(1003, 'Post Test Aksel MJC - Animation Motion', 'post-test', 1, 103, 60),
(1004, 'Post Test Aksel MJC - Videografi',       'post-test', 1, 104, 60);

/* ---------------------------------------------------------------------
   A2. FOTOGRAFI (tes_id = 1001)
   --------------------------------------------------------------------- */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(2001,1001,1,'Hal yang TIDAK diperlukan untuk berkembang di industri kreatif adalah …','pilihan_ganda'),
(2002,1001,2,'Yang bukan termasuk tujuan berkolaborasi adalah …','pilihan_ganda'),
(2003,1001,3,'Apa yang dimaksud dengan kamera full frame …','pilihan_ganda'),
(2004,1001,4,'ISO memiliki kegunaan untuk mengatur …','pilihan_ganda'),
(2005,1001,5,'Shutter Speed rendah tanpa tripod akan membuat foto …','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(2001,'Komunikasi yang baik',false),
(2001,'Attitude',false),
(2001,'Tujuan yang jelas',false),
(2001,'Beli kamera setiap bulan',true),
(2001,'Jalin relasi',false),

(2002,'Menguntungkan diri sendiri',true),
(2002,'Mempermudah pekerjaan',false),
(2002,'Meningkatkan kepercayaan',false),
(2002,'Belajar hal baru',false),
(2002,'Meningkatkan kepercayaan diri',false),

(2003,'Kamera dengan sensor 35mm',true),
(2003,'Sensor 50mm',false),
(2003,'Lensa wide',false),
(2003,'Lensa tele',false),
(2003,'Semua benar',false),

(2004,'Tingkat kecerahan',true),
(2004,'Tingkat blur',false),
(2004,'Tingkat fokus',false),
(2004,'Warna foto',false),
(2004,'Resolusi',false),

(2005,'Semakin detail',false),
(2005,'Semakin terang',false),
(2005,'Semakin gelap',false),
(2005,'Blur / goyang',true),
(2005,'Berwarna',false);

/* ---------------------------------------------------------------------
   A3. DESAIN GRAFIS (tes_id = 1002)
   --------------------------------------------------------------------- */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(3001,1002,1,'Mengapa identitas visual penting bagi brand?','pilihan_ganda'),
(3002,1002,2,'Bagaimana identitas visual memperkuat hubungan emosional?','pilihan_ganda'),
(3003,1002,3,'Apa persamaan self branding dan logo?','pilihan_ganda'),
(3004,1002,4,'Plagiarisme tidak sadar disebut …','pilihan_ganda'),
(3005,1002,5,'Tool Adobe Illustrator untuk mengambil sample warna adalah …','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(3001,'Brand terlihat profesional dan terpercaya',true),
(3001,'Agar terlihat mahal',false),
(3001,'Agar viral',false),
(3001,'Meniru kompetitor',false),
(3001,'Tidak penting',false),

(3002,'Melalui warna dan elemen visual',true),
(3002,'Melalui harga produk',false),
(3002,'Melalui promosi',false),
(3002,'Melalui diskon',false),
(3002,'Melalui bonus',false),

(3003,'Sama-sama identitas visual',true),
(3003,'Tidak ada persamaan',false),
(3003,'Hanya logo',false),
(3003,'Hanya branding',false),
(3003,'Semua salah',false),

(3004,'Plagiarisme tidak disengaja',true),
(3004,'Plagiarisme langsung',false),
(3004,'Plagiarisme total',false),
(3004,'Plagiarisme berat',false),
(3004,'Plagiarisme sadar',false),

(3005,'Eyedropper Tool',true),
(3005,'Pen Tool',false),
(3005,'Brush Tool',false),
(3005,'Shape Tool',false),
(3005,'Text Tool',false);

/* ---------------------------------------------------------------------
   A4. ANIMATION MOTION (tes_id = 1003)
   --------------------------------------------------------------------- */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(4001,1003,1,'Ada berapa prinsip animasi?','pilihan_ganda'),
(4002,1003,2,'Clay animation disebut juga …','pilihan_ganda'),
(4003,1003,3,'Teknik animasi menggambar frame satu per satu disebut …','pilihan_ganda'),
(4004,1003,4,'Fungsi storyboard adalah …','pilihan_ganda'),
(4005,1003,5,'Graph Editor di After Effects berfungsi untuk …','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(4001,'12 prinsip',true),
(4001,'10 prinsip',false),
(4001,'11 prinsip',false),
(4001,'15 prinsip',false),
(4001,'13 prinsip',false),

(4002,'Stop Motion',true),
(4002,'Animasi 2D',false),
(4002,'Animasi 3D',false),
(4002,'Motion Capture',false),
(4002,'Rigging',false),

(4003,'Frame by frame',true),
(4003,'Tweening',false),
(4003,'Rigging',false),
(4003,'Motion capture',false),
(4003,'Keyframe',false),

(4004,'Panduan urutan adegan',true),
(4004,'Menentukan warna',false),
(4004,'Menentukan suara',false),
(4004,'Efek visual',false),
(4004,'Rendering',false),

(4005,'Mengatur kecepatan animasi',true),
(4005,'Memotong video',false),
(4005,'Menghapus layer',false),
(4005,'Menambah audio',false),
(4005,'Mengedit warna',false);

/* ---------------------------------------------------------------------
   A5. VIDEOGRAFI (tes_id = 1004)
   --------------------------------------------------------------------- */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(5001,1004,1,'Aplikasi media sosial berbasis video adalah …','pilihan_ganda'),
(5002,1004,2,'FPS adalah singkatan dari …','pilihan_ganda'),
(5003,1004,3,'Storyboard adalah …','pilihan_ganda'),
(5004,1004,4,'Resolusi video 1080p disebut …','pilihan_ganda'),
(5005,1004,5,'Semakin tinggi FPS maka video …','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(5001,'Youtube',true),
(5001,'Blogspot',false),
(5001,'Spotify',false),
(5001,'Pinterest',false),
(5001,'Kalkulator',false),

(5002,'Frame per second',true),
(5002,'File per second',false),
(5002,'Focus per second',false),
(5002,'Flash per second',false),
(5002,'Format per second',false),

(5003,'Sketsa urutan adegan',true),
(5003,'Hasil editing',false),
(5003,'Output video',false),
(5003,'Teknik kamera',false),
(5003,'Resolusi video',false),

(5004,'Full HD',true),
(5004,'HD',false),
(5004,'SD',false),
(5004,'4K',false),
(5004,'8K',false),

(5005,'Semakin mulus',true),
(5005,'Semakin gelap',false),
(5005,'Semakin buram',false),
(5005,'Semakin kecil',false),
(5005,'Semakin berat kamera',false);

/* =====================================================================
   B. TES REGULER MJC (Desain Grafis Reguler MJC 2025)
   ===================================================================== */
INSERT INTO tes (id, judul, tipe, durasi_menit) VALUES
(40001,'Post Test Desain Grafis Reguler MJC 2025','post-test',20);

/* PERTANYAAN + OPSI JAWABAN */
INSERT INTO pertanyaan VALUES
(40011,40001,1,'Kata logo yang diserap dari Bahasa Yunani (logos) artinya adalah ....','pilihan_ganda'),
(40012,40001,2,'Gambar berikut ini yang merupakan jenis logo Letter Mark adalah ….','pilihan_ganda'),
(40013,40001,3,'Gambar berikut ini yang merupakan jenis logo Pictorial Mark adalah ….','pilihan_ganda'),
(40014,40001,4,'Berikut fungsi dari logo, kecuali ….','pilihan_ganda'),
(40015,40001,5,'Yang bukan merupakan kriteria logo yang baik dan efektif adalah ….','pilihan_ganda'),
(40016,40001,6,'Yang bukan merupakan pencetus teori Gestalt adalah ….','pilihan_ganda'),
(40017,40001,7,'Di bawah ini merupakan prinsip Gestalt yaitu ….','pilihan_ganda'),
(40018,40001,8,'Apa yang dimaksud dengan typeface?','pilihan_ganda'),
(40019,40001,9,'Di bawah ini merupakan 4 dasar jenis font, kecuali ….','pilihan_ganda'),
(40020,40001,10,'Shortcut Tool yang digunakan untuk Shape Builder adalah ….','pilihan_ganda');

INSERT INTO opsi_jawaban VALUES
(NULL,40011,'otak, budi, wajah, akal, serta pembicaraan',true),
(NULL,40011,'gambar, budi, kalimat, akal, serta perwujudan',false),
(NULL,40011,'pikiran, budi, kata, akal, serta pembicaraan',false),
(NULL,40011,'pikiran, budi, kata, jiwa, serta informasi',false),
(NULL,40011,'pikiran, perbuatan, kata, akal, serta penampilan',false),

(NULL,40012,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q03_A.png',true),
(NULL,40012,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q03_B.png',false),
(NULL,40012,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q03_C.png',false),
(NULL,40012,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q03_D.png',false),
(NULL,40012,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q03_E.png',false),

(NULL,40013,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q04_A.png',true),
(NULL,40013,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q04_B.png',false),
(NULL,40013,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q04_C.png',false),
(NULL,40013,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q04_D.png',false),
(NULL,40013,'images/opsi-jawaban/post-test-siswa-desain-grafis-angkatan-ii-tahun-2025/Q04_E.png',false),

(NULL,40014,'Salah satu sarana branding',true),
(NULL,40014,'Sarana presentasi dan promosi',false),
(NULL,40014,'Sarana informasi dan pengawas',false),
(NULL,40014,'Sarana motivasi',false),
(NULL,40014,'Melindungi produk dari cuaca',false),

(NULL,40015,'Simple (sederhana)',false),
(NULL,40015,'Original dan unik',false),
(NULL,40015,'Mudah diingat',false),
(NULL,40015,'Bagus',true),
(NULL,40015,'Relevan',false),

(NULL,40016,'Kurt Koffka',false),
(NULL,40016,'Max Wertheimer',false),
(NULL,40016,'Wolfgang Köhler',false),
(NULL,40016,'Thomas Knoll',true),
(NULL,40016,'Semua jawaban benar',false),

(NULL,40017,'Continuity',true),
(NULL,40017,'Figure Ground',false),
(NULL,40017,'Closure',false),
(NULL,40017,'Similarity',false),
(NULL,40017,'Proximity',false),

(NULL,40018,'File format digital dari keseluruhan typeface',true),
(NULL,40018,'Tipografi',false),
(NULL,40018,'Bentuk tulisan tangan',false),
(NULL,40018,'Jenis huruf dekoratif',false),
(NULL,40018,'Kerning',false),

(NULL,40019,'Serif',false),
(NULL,40019,'Sans Serif',false),
(NULL,40019,'Script',false),
(NULL,40019,'Dekoratif',false),
(NULL,40019,'Kerning',true),

(NULL,40020,'Shift + M',true),
(NULL,40020,'Shift + O',false),
(NULL,40020,'Shift + S',false),
(NULL,40020,'Shift + B',false),
(NULL,40020,'Shift + 7',false);

/* =====================================================================
   C. TES AKSELERASI (TATA) — PRE & POST
   ===================================================================== */
INSERT INTO tes (id, judul, tipe, durasi_menit) VALUES
(60001,'Pre & Post Test Tata Kecantikan Akselerasi 2025','post-test',20),
(60002,'Pre & Post Test Teknik Pendingin Akselerasi 2025','post-test',20),
(60003,'Pre & Post Test Tata Busana Akselerasi 2025','post-test',20),
(60004,'Pre & Post Test Tata Boga Akselerasi 2025','post-test',20);

/* C1. TATA KECANTIKAN (60001) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(60011,60001,1,'Tindakan analisis kulit kepala dan rambut melalui pengamatan adalah…','pilihan_ganda'),
(60012,60001,2,'Ciri rambut kusam, kemerah-merahan, elastisitas kurang menunjukkan jenis rambut…','pilihan_ganda'),
(60013,60001,3,'Halus atau kasar rambut melalui perabaan disebut…','pilihan_ganda'),
(60014,60001,4,'Sirkulasi darah buruk dan penyakit kulit kepala menyebabkan…','pilihan_ganda'),
(60015,60001,5,'Rambut berminyak, tebal, cepat kotor menunjukkan jenis rambut…','pilihan_ganda'),
(60016,60001,6,'Teknik pengeringan rambut blow dry adalah…','pilihan_ganda'),
(60017,60001,7,'Mengeringkan rambut menggunakan jari disebut…','pilihan_ganda'),
(60018,60001,8,'Membentuk arah batang rambut sesuai wajah disebut…','pilihan_ganda'),
(60019,60001,9,'Tujuan creambath berikut, kecuali…','pilihan_ganda'),
(60020,60001,10,'Shampo telur cocok untuk rambut…','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(60011,'Inspeksi',true),(60011,'Palpasi',false),(60011,'Instruksi',false),(60011,'Infeksi',false),(60011,'Refleksi',false),
(60012,'Kering',true),(60012,'Normal',false),(60012,'Berminyak',false),(60012,'Campuran',false),(60012,'Sehat',false),
(60013,'Inspeksi',true),(60013,'Palpasi',false),(60013,'Instruksi',false),(60013,'Infeksi',false),(60013,'Refleksi',false),
(60014,'Ketombe',true),(60014,'Uban',false),(60014,'Hair sutisme',false),(60014,'Alopecia',false),(60014,'Kurap',false),
(60015,'Kering',true),(60015,'Normal',false),(60015,'Berminyak',false),(60015,'Campuran',false),(60015,'Sehat',false),
(60016,'Basic blow dry, turning hair out, blow vertical',true),(60016,'Flat blow',false),(60016,'Natural dry',false),(60016,'Block dry',false),(60016,'Half dry',false),
(60017,'Blow dry',true),(60017,'Blow vertical',false),(60017,'Natural dry',false),(60017,'Block dry',false),(60017,'Half dry',false),
(60018,'Manfaat perawatan kulit kepala dan rambut',true),(60018,'Tujuan pengurutan',false),(60018,'Tujuan pengeringan',false),(60018,'Manfaat penataan',false),(60018,'Tujuan penyampoan',false),
(60019,'Menyehatkan kulit kepala dan rambut',true),(60019,'Merangsang pertumbuhan',false),(60019,'Melancarkan peredaran darah',false),(60019,'Merileksasi kulit kepala',false),(60019,'Mengubah warna rambut',false),
(60020,'Kering',true),(60020,'Normal',false),(60020,'Berminyak',false),(60020,'Berketombe',false),(60020,'Bercabang',false);

/* C2. TEKNIK PENDINGIN (60002) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(60021,60002,1,'Kapasitor pada blower fan outdoor adalah…','pilihan_ganda'),
(60022,60002,2,'Mesin 3R bekerja secara…','pilihan_ganda'),
(60023,60002,3,'Pengecekan mesin pendingin ditujukan untuk…','pilihan_ganda'),
(60024,60002,4,'AC wajib recovery jika…','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(60021,'Starting',true),(60021,'Double Starting',false),(60021,'Start Kapasitor',false),(60021,'Running Kapasitor',false),(60021,'Running & Starting',false),
(60022,'Tidak bersamaan dalam satu waktu',true),(60022,'Bergantian',false),(60022,'Berjedah',false),(60022,'Bersamaan',false),(60022,'Semua salah',false),
(60023,'Tahanan isolasi',true),(60023,'Tahanan resistance',false),(60023,'Tahanan kumparan',false),(60023,'Tahanan start',false),(60023,'Common & starting',false),
(60024,'Unit rusak dan perlu penggantian part',true),(60024,'Refrigerant lancar',false),(60024,'Normal',false),(60024,'Tidak bocor',false),(60024,'Semua salah',false);

/* C3. TATA BUSANA (60003) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(60031,60003,1,'Mencari langganan dapat dilakukan dengan cara…','pilihan_ganda'),
(60032,60003,2,'Bahan kapas cocok di daerah tropis karena…','pilihan_ganda'),
(60033,60003,3,'Pola dasar adalah pola…','pilihan_ganda'),
(60034,60003,4,'Rok panjang sampai lantai disebut…','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(60031,'Membuat tarif rendah',true),(60031,'Menjatuhkan pesaing',false),(60031,'Ruang mewah',false),(60031,'Promosi palsu',false),(60031,'Mengabaikan kualitas',false),
(60032,'Menyerap keringat dan kuat',true),(60032,'Tidak nyaman',false),(60032,'Murah',false),(60032,'Berat',false),(60032,'Kaku',false),
(60033,'Standart',true),(60033,'Bagian atas',false),(60033,'Sudah diubah',false),(60033,'Belum diubah',false),(60033,'Dekoratif',false),
(60034,'Maxi',true),(60034,'Mini',false),(60034,'Floor',false),(60034,'Ankle',false),(60034,'Midi',false);

/* C4. TATA BOGA (60004) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(60041,60004,1,'Margarin khusus untuk adonan lipat disebut…','pilihan_ganda'),
(60042,60004,2,'Suhu panggang puff pastry yang baik adalah…','pilihan_ganda'),
(60043,60004,3,'Fungsi ragi sebagai leavening agent adalah…','pilihan_ganda'),
(60044,60004,4,'Alat optimal fermentasi adalah…','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(60041,'Shortening',true),(60041,'Butter',false),(60041,'Mentega',false),(60041,'Korsvet',false),(60041,'Margarin',false),
(60042,'100-120°C',true),(60042,'150-160°C',false),(60042,'170-180°C',false),(60042,'200-220°C',false),(60042,'230-250°C',false),
(60043,'Mengembangkan adonan',true),(60043,'Memberi aroma',false),(60043,'Memadatkan',false),(60043,'Mengikat air',false),(60043,'Mengubah warna',false),
(60044,'Sheeter',true),(60044,'Oven',false),(60044,'Mixer',false),(60044,'Divider',false),(60044,'Blender',false);

/* =====================================================================
   D. TES REGULER TATA (pakai pelatihan_id & kompetensi_pelatihan_id)
   ===================================================================== */
INSERT INTO tes (id, judul, tipe, pelatihan_id, kompetensi_pelatihan_id, durasi_menit) VALUES
(10001,'Post Test Reguler Tata Kecantikan','post-test',3,301,20),
(10002,'Post Test Reguler Teknik Pendingin','post-test',3,302,20),
(10003,'Post Test Reguler Tata Busana','post-test',3,303,20),
(10004,'Post Test Reguler Tata Boga','post-test',3,304,20);

/* D1. TATA KECANTIKAN (10001) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(11001,10001,1,'Kosmetika pratata disebut juga','pilihan_ganda'),
(11002,10001,2,'Gerakan pengurutan pada creambath adalah untuk','pilihan_ganda'),
(11003,10001,3,'Kosmetik setelah rambut ditata adalah','pilihan_ganda'),
(11004,10001,4,'Gerakan pengurutan yang bersifat menenangkan disebut','pilihan_ganda'),
(11005,10001,5,'Ujung rambut terbelah memanjang disebut','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(11001,'Thioglikolat',true),
(11001,'Setress',false),
(11001,'Waving lotion',false),

(11002,'Mencegah rambut rontok',true),
(11002,'Merangsang uban',false),
(11002,'Menghilangkan uban',false),

(11003,'Haircream',true),
(11003,'Hairspa',false),
(11003,'Hairlaquer',false),

(11004,'Effleurage',true),
(11004,'Tapotage',false),
(11004,'Vibration',false),

(11005,'Trichoptilosis',true),
(11005,'Trichoclasia',false),
(11005,'Trichondosis',false);

/* D2. TEKNIK PENDINGIN (10002) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(12001,10002,1,'Mesin 3R digunakan untuk proses','pilihan_ganda'),
(12002,10002,2,'Recovery pada mesin pendingin adalah','pilihan_ganda'),
(12003,10002,3,'Unit AC wajib direcovery bila','pilihan_ganda'),
(12004,10002,4,'Alat yang dibutuhkan saat recovery','pilihan_ganda'),
(12005,10002,5,'Bagian indoor AC yang wajib dibersihkan','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(12001,'Recovery, recycling, recharging',true),
(12001,'Recharging saja',false),
(12001,'Vacuum saja',false),

(12002,'Menampung refrigerant dari sistem',true),
(12002,'Mengisi refrigerant',false),
(12002,'Membuang refrigerant',false),

(12003,'Unit rusak dan perlu perbaikan',true),
(12003,'Unit baru',false),
(12003,'Unit normal',false),

(12004,'Manifold gauge dan recovery tank',true),
(12004,'Obeng dan palu',false),
(12004,'Tang dan cutter',false),

(12005,'Cross Flow Fan',true),
(12005,'Kompresor',false),
(12005,'Kondensor',false);

/* D3. TATA BUSANA (10003) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(13001,10003,1,'Tujuan merendam bahan sebelum digunting','pilihan_ganda'),
(13002,10003,2,'Gaun punggung terbuka disebut model','pilihan_ganda'),
(13003,10003,3,'Warna primer adalah','pilihan_ganda'),
(13004,10003,4,'Segala sesuatu tentang cara berpakaian disebut','pilihan_ganda'),
(13005,10003,5,'Rok dengan lipit bertumpuk disebut','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(13001,'Mengetahui penyusutan',true),
(13001,'Memperindah warna',false),
(13001,'Menghilangkan kotoran',false),

(13002,'Backless',true),
(13002,'Strapless',false),
(13002,'Mumu',false),

(13003,'Merah, kuning, biru',true),
(13003,'Hijau, putih, hitam',false),
(13003,'Ungu, coklat, abu',false),

(13004,'Etika berbusana',true),
(13004,'Aksesoris',false),
(13004,'Pelengkap busana',false),

(13005,'Rok lipit hadap',true),
(13005,'Rok lipit kipas',false),
(13005,'Rok lipit pipih',false);

/* D4. TATA BOGA (10004) */
INSERT INTO pertanyaan (id, tes_id, nomor, teks_pertanyaan, tipe_jawaban) VALUES
(14001,10004,1,'Bahan pengembang roti adalah','pilihan_ganda'),
(14002,10004,2,'Tepung terigu untuk roti berprotein','pilihan_ganda'),
(14003,10004,3,'Fungsi garam dalam pembuatan roti','pilihan_ganda'),
(14004,10004,4,'Tujuan proofing adonan','pilihan_ganda'),
(14005,10004,5,'Danish pastry adalah','pilihan_ganda');

INSERT INTO opsi_jawaban (pertanyaan_id, teks_opsi, apakah_benar) VALUES
(14001,'Yeast',true),
(14001,'Gula',false),
(14001,'Garam',false),

(14002,'Tinggi',true),
(14002,'Rendah',false),
(14002,'Sedang',false),

(14003,'Memberi rasa dan memperkuat gluten',true),
(14003,'Membuat manis',false),
(14003,'Menghilangkan ragi',false),

(14004,'Mengembangkan adonan',true),
(14004,'Mendinginkan adonan',false),
(14004,'Menghilangkan gas',false),

(14005,'Pastry beragi dan manis',true),
(14005,'Pastry renyah',false),
(14005,'Pastry tanpa lemak',false);

/* =====================================================================
   E. TES REGULER (format berbeda: tes.kategori + pertanyaan.soal)
   ===================================================================== */

/* E1. TATA BOGA REGULER */
INSERT INTO tes (id, judul, kategori, durasi_menit) VALUES
(70001,'Post Test Tata Boga Reguler 2025','tata_boga',20);

INSERT INTO pertanyaan (id, tes_id, nomor, soal) VALUES
(700011,70001,1,'Berikut ini yang merupakan bahan utama dalam membuat produk bakery dan pastry kecuali'),
(700012,70001,2,'Untuk membuat adonan roti diperlukan tepung terigu dengan kadar protein'),
(700013,70001,3,'Bahan pengembang untuk roti adalah'),
(700014,70001,4,'Jenis gula yang butirannya lebih halus dari gula pasir adalah'),
(700015,70001,5,'Penggunaan garam pada pembuatan produk roti adalah'),
(700016,70001,6,'Fungsi garam dalam pembuatan roti adalah'),
(700017,70001,7,'Margarin merupakan mentega sintetis yang terbuat dari'),
(700018,70001,8,'Gula memiliki sifat higrokopis artinya'),
(700019,70001,9,'Pencampuran gula tidak merata menyebabkan'),
(700020,70001,10,'Margarin untuk puff pastry disebut');

INSERT INTO opsi_jawaban VALUES
(700011,'Tepung terigu',1),(700011,'Telur',0),(700011,'Vanili',0),(700011,'Gula',0),
(700012,'Rendah',1),(700012,'Tinggi',0),(700012,'Sedang',0),(700012,'Cukup',0),
(700013,'Yeast',1),(700013,'Telur',0),(700013,'Gula',0),(700013,'Margarin',0),
(700014,'Gula kastor',1),(700014,'Gula halus',0),(700014,'Gula palem',0),(700014,'Gula jawa',0),
(700015,'5%',1),(700015,'1–2%',0),(700015,'3%',0),(700015,'10%',0),
(700016,'Perasa',1),(700016,'Pewarna',0),(700016,'Stabilitator',0),(700016,'Fermentasi',0),
(700017,'Lemak hewani',1),(700017,'Lemak nabati',0),(700017,'Mentega',0),(700017,'Susu',0),
(700018,'Menahan air',1),(700018,'Menahan lemak',0),(700018,'Menahan protein',0),(700018,'Berasa manis',0),
(700019,'Bintik hitam dan berlubang',1),(700019,'Fermentasi lama',0),(700019,'Tidak mengembang',0),(700019,'Terlalu manis',0),
(700020,'Shortening',1),(700020,'Mentega putih',0),(700020,'Korsvet',0),(700020,'Mentega cair',0);

/* E2. TATA BUSANA REGULER */
INSERT INTO tes (id, judul, kategori, durasi_menit) VALUES
(70002,'Post Test Tata Busana Reguler 2025','tata_busana',20);

INSERT INTO pertanyaan VALUES
(700021,70002,1,'Penjahit selain terampil menjahit juga harus bisa'),
(700022,70002,2,'Tujuan merendam bahan sebelum digunting adalah'),
(700023,70002,3,'Gaun dengan punggung terbuka disebut'),
(700024,70002,4,'Pola untuk sistem konveksi adalah'),
(700025,70002,5,'Wool, beludru dan drill bertekstur');

INSERT INTO opsi_jawaban VALUES
(700021,'Memilihkan model yang sesuai pelanggan',1),(700021,'Modal besar',0),(700021,'Punya butik',0),(700021,'Berpakaian mewah',0),
(700022,'Menguatkan warna',1),(700022,'Mengetahui penyusutan',0),(700022,'Mengetahui mutu',0),(700022,'Menghilangkan kanji',0),
(700023,'Mumu',1),(700023,'Strapless',0),(700023,'Backless',0),(700023,'Delix plece',0),
(700024,'Standar',1),(700024,'Dasar',0),(700024,'Ukuran perorangan',0),(700024,'Drapping',0),
(700025,'Lembut',1),(700025,'Kaku',0),(700025,'Lentur',0),(700025,'Tipis',0);

/* E3. TEKNIK PENDINGIN REGULER */
INSERT INTO tes (id, judul, kategori, durasi_menit) VALUES
(70003,'Post Test Teknik Pendingin Reguler 2025','pendingin',20);

INSERT INTO pertanyaan VALUES
(700031,70003,1,'Mesin 3R digunakan untuk proses'),
(700032,70003,2,'Recovery refrigerant adalah'),
(700033,70003,3,'Unit AC wajib recovery jika'),
(700034,70003,4,'Alat utama proses recovery adalah');

INSERT INTO opsi_jawaban VALUES
(700031,'Recovery, recycling, recharging',1),(700031,'Recharging saja',0),(700031,'Recycling saja',0),(700031,'Charging',0),
(700032,'Menampung refrigerant',1),(700032,'Membuang refrigerant',0),(700032,'Mengisi oli',0),(700032,'Pendinginan',0),
(700033,'Unit rusak dan perlu perbaikan',1),(700033,'Normal',0),(700033,'Baru dipasang',0),(700033,'AC mati',0),
(700034,'Manifold gauge & vacuum pump',1),(700034,'Obeng',0),(700034,'Tang',0),(700034,'Kunci L',0);

/* E4. TATA KECANTIKAN REGULER */
INSERT INTO tes (id, judul, kategori, durasi_menit) VALUES
(70004,'Post Test Tata Kecantikan Reguler 2025','tata_kecantikan',20);

INSERT INTO pertanyaan VALUES
(700041,70004,1,'Kosmetika pratata disebut'),
(700042,70004,2,'Gerakan tapotage dilakukan secara'),
(700043,70004,3,'Efek neutralizer tidak dibilas'),
(700044,70004,4,'Ujung rambut terbelah disebut');

INSERT INTO opsi_jawaban VALUES
(700041,'Thioglikolat',1),(700041,'Setress',0),(700041,'Waving lotion',0),(700041,'Blowdry lotion',0),
(700042,'Menggetar',1),(700042,'Mengusap',0),(700042,'Menepuk',0),(700042,'Memijat',0),
(700043,'Iritasi kulit',1),(700043,'Panas',0),(700043,'Kusam',0),(700043,'Kribo',0),
(700044,'Tricology',1),(700044,'Trichoclasia',0),(700044,'Trichoptilosis',0),(700044,'Trichondosis',0);

COMMIT;
