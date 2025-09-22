
--
-- Dumping data for table `tes`
--
INSERT INTO
  `tes` (
    `id`,
    `judul`,
    `deskripsi`,
    `tipe`,
    `sub_tipe`,
    `bidang_id`,
    `pelatihan_id`,
    `durasi_menit`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    'Post-Test Teknik Pendingin',
    'Tes akhir untuk mengukur pemahaman materi Teknik Pendingin dan Tata Udara.',
    'tes',
    'post-test',
    4,
    1,
    30,
    '2025-08-29 06:03:02',
    '2025-08-29 06:03:02'
  ),
  (
    2,
    'Post-Test Kecantikan',
    'Tes akhir untuk mengukur pemahaman materi Kecantikan (Perawatan Rambut).',
    'tes',
    'post-test',
    3,
    1,
    30,
    '2025-08-29 06:03:02',
    '2025-08-29 06:03:02'
  ),
  (
    3,
    'Post-Test Tata Boga',
    'Tes akhir untuk mengukur pemahaman materi Produk Bakery dan Pastry.',
    'tes',
    'post-test',
    1,
    1,
    30,
    '2025-08-29 06:03:02',
    '2025-08-29 06:03:02'
  ),
  (
    4,
    'Post-Test Tata Busana',
    'Tes akhir untuk mengukur pemahaman materi Menjahit dan Pola.',
    'tes',
    'post-test',
    2,
    1,
    30,
    '2025-08-29 06:03:02',
    '2025-08-29 06:03:02'
  ),
  (
    5,
    'Survei Kepuasan Pelatihan',
    'Survei untuk mengumpulkan umpan balik mengenai penyelenggaraan pelatihan.',
    'survei',
    NULL,
    1,
    1,
    NULL,
    '2025-08-29 06:03:02',
    '2025-08-29 06:03:02'
  ),
  (
    6,
    'Post-Test Videografi',
    'Tes akhir untuk mengukur pemahaman materi Videografi.',
    'tes',
    'post-test',
    5,
    1,
    30,
    '2025-09-10 04:19:53',
    '2025-09-10 04:19:53'
  ),
  (
    7,
    'Post-Test PLC',
    'Tes akhir untuk mengukur pemahaman materi Programmable Logic Controllers (PLC).',
    'tes',
    'post-test',
    6,
    1,
    30,
    '2025-09-10 04:19:53',
    '2025-09-10 04:19:53'
  ),
  (
    8,
    'Post-Test Fotografi',
    'Tes akhir untuk mengukur pemahaman materi Fotografi Produk.',
    'tes',
    'post-test',
    7,
    1,
    30,
    '2025-09-10 04:19:53',
    '2025-09-10 04:19:53'
  ),
  (
    9,
    'Survei Kepuasan Pelatihan - MTU',
    'Survei untuk mengumpulkan umpan balik mengenai penyelenggaraan pelatihan.',
    'survei',
    NULL,
    1,
    1,
    NULL,
    '2025-09-10 04:19:53',
    '2025-09-10 04:19:53'
  );


--
-- Dumping data for table `pertanyaan`
--
INSERT INTO
  `pertanyaan` (
    `id`,
    `tes_id`,
    `nomor`,
    `teks_pertanyaan`,
    `gambar`,
    `tipe_jawaban`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    1,
    1,
    'Mesin 3R adalah mesin yang digunakan untuk melakukan proses:',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    2,
    1,
    2,
    'Mesin 3R dapat melakukan tiga fungsi tersebut secara',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    3,
    1,
    3,
    'Recovery pada mesin pendingin yaitu proses menampung',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    4,
    1,
    4,
    'Unit AC wajib di recovery bila dalam keadaan :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    5,
    1,
    5,
    'Alat yang dibutuhkan di saat proses Recovery yaitu :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    6,
    1,
    6,
    'Alat dan bahan di bawah ini adalah digunakan disaat proses ….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    7,
    1,
    7,
    'Gambar di bawah ini adalah proses Recovery Refigerant yaitu :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    8,
    1,
    8,
    'Proses langkah kerja mesin 3 R di bawah ini adalah proses :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    9,
    1,
    9,
    'Gambar di bawah ini adalah proses :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    10,
    1,
    10,
    'Proses kerja atau langkah kerja berikut adalah',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    11,
    1,
    11,
    'Factor penyebab AC kotor adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    12,
    1,
    12,
    'Gambar di bawah ini bertujuan agar unit AC bekerja dengan baik pengkondisian udara bertujuan',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    13,
    1,
    13,
    'Pemeliharaan AC dibagi berapa proses agar unit AC sesuai proses memperpanjang usia pakai :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    14,
    1,
    14,
    'Dari gambar di bawah proses yang masih harus dilakukan pada point A.1 adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    15,
    1,
    15,
    'Proses yang harus di lakukan yang sesuai gambar kerja di bawah adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    16,
    1,
    16,
    'Pemeriksaan proses perawatan AC dalam proses gambar di bawah ini adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    17,
    1,
    17,
    'Kebutuhan alat dan bahan yang di bawah ini ada beberapa yang harus disiapkan agar proses perawtan berjalan dengan SOP, adapaun perlatan yang masih belum terakomodir adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    18,
    1,
    18,
    'Gambar di bawah ini adalah langkah persiapan alat dalam langkah proses perawatan AC, ada beberapa alat yang masih belum terakomodir pada gambar di bawah ini, adapun alat yang kurang adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    19,
    1,
    19,
    'Part pada indoor yang harus dibersihkan sesuai SOP no 1 s.d 6 wajib di lakukan oleh seorang calon teknisi dan teknisi, adapaun no 3 dan 4 adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    20,
    1,
    20,
    'Pada gambar di bawah ini Part yang harus dibersihkan pada system outdoor adalah yang tertera pada nomor 1 s.d 3, adapaun untuk persyaratan SOP nya untuk no 2 dan 3 adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    21,
    2,
    21,
    'Fungsi diagnosa kulit kepala dan rambut adalah untuk….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    22,
    2,
    22,
    'Shampo yang memiliki kandungan asam nitrat yang dapat melarutkan lemak atau minyak pada kulit kepala dan rambut. adalah…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    23,
    2,
    23,
    'Syarat utama pada air yang digunakan untuk mencuci rambut adalah….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    24,
    2,
    24,
    'Pengurutan kulit kepala saat melakukan penyampoan harus dilakukan dengan gerakan yang benar,berikut merupakan gerakan pada pengurutan kulit kepala dan rambut yaitu:….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    25,
    2,
    25,
    'Kosmetika yang berfungsi sebagai pengkondisi setelah penyampoan adalah ….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    26,
    2,
    26,
    'Untuk menghasilkan rambut lurus mengembang dan rapi, tehnik blowdry yang tepat adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    27,
    2,
    27,
    'Arah hair dryer yang tepat pada saat mengeringkan rambut adalah ….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    28,
    2,
    28,
    'Untuk menjaga keselamatan kerja pada waktu mengeringkan rambut,sebaiknya digunakan hair dryer dengan jarak kurang lebih ….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    29,
    2,
    29,
    'Berikut ini adalah fungsi pengurutan / massage kulit kepala ,kecuali…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    30,
    2,
    30,
    'Alat listrik yang digunakan untuk penguapan pada creambath disebut….',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    31,
    2,
    31,
    'Jenis air yang baik digunakan untuk pencucian rambut adalah air yang . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    32,
    2,
    32,
    'Shampo telur baik digunakan untuk rambut . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    33,
    2,
    33,
    'Kosmetik yang digunakan untuk mengkilatkan rambut kusam adalah . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    34,
    2,
    34,
    'Tujuan perawatan rambut dan kulit kepala adalah untuk . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    35,
    2,
    35,
    'Alat-alat yang dapat menunjang keefektifan creambath adalah bukan . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    36,
    2,
    36,
    'Seperangkat lahiriah yang terbawa sejak lahir bagi diri seseorang disebut juga .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    37,
    2,
    37,
    'Untuk pembentukan tulang dan gigi diperlukan . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    38,
    2,
    38,
    'Kosmetika yang tidak digunakan untuk pencucian rambut normal adalah . . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    39,
    2,
    39,
    'Untuk rambut yang kering sekali digunakan shampo..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    40,
    2,
    40,
    'Effleurage adalah gerakan pengurutan secara . . .',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    41,
    3,
    41,
    'Berikut ini yang merupakan bahan utama dalam membuat produk bakery dan pastry kecuali …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    42,
    3,
    42,
    'Untuk membuat adonan roti diperlukan tepung terigu dengan kadar protein …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    43,
    3,
    43,
    'Bahan pengembang untuk roti adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    44,
    3,
    44,
    'Jenis gula yang butiran nya lebh halus dari gula pasir adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    45,
    3,
    45,
    'Penggunaan garam pada pembuatan produk roti adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    46,
    3,
    46,
    'Fungsi garam dalam pembuatan roti adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    47,
    3,
    47,
    'Margarin merupakan mentega sintetis yang terbuat dari …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    48,
    3,
    48,
    'Gula memiliki sifat higrokopis artinya …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    49,
    3,
    49,
    'Pencampuran gula yang tidak merata dan terlalu banyak pada adonan roti menyebabkan …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    50,
    3,
    50,
    'Margarin yang dipergunakan untuk membuat adonan puff disebut …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    51,
    3,
    51,
    'Pada proses pembuatan produk roti perlu dilakukan proofing, tujuan nya adalah untuk …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    52,
    3,
    52,
    'Suhu pada saat proofing adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    53,
    3,
    53,
    'Lama proses proofing adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    54,
    3,
    54,
    'Kisaran suhu pada saat memanggang roti tawar adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    55,
    3,
    55,
    'Tujuan roti dilepaskan dari cetakan saat masih panas adalah',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    56,
    3,
    56,
    'Dibawah ini merupakan produk pastry kecuali …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    57,
    3,
    57,
    'Kisaran suhu pada saat memanggang adonan puff pastry adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    58,
    3,
    58,
    'Karakteristik hasil puff pastry kecuali …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    59,
    3,
    59,
    'Produk puff pastry sebelum dipanggang harus diistirahatkan selama …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    60,
    3,
    60,
    'Danish pastry adalah …..',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    61,
    4,
    61,
    'Seorang penjahit selain terampil menyelesaikan pakaian sesuai dengan model pesanan, juga harus bisa...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    62,
    4,
    62,
    'Tujuan merendam bahan sebelum digunting adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    63,
    4,
    63,
    'Gaun yang mempunyai model punggung yang terlihat disebut model ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    64,
    4,
    64,
    'Pemakaian gaun dengan garis dada yang rendah dan tanpa lengan sebaiknya dipakai oleh orang yang berperawakan ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    65,
    4,
    65,
    'Pola yang dipergunakan untuk menjahit dengan sistim konveksi adalah pola...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    66,
    4,
    66,
    'Sebelum mengelim rok lingkar sebaiknya rok digantung terlebih dahulu supaya…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    67,
    4,
    67,
    'Pola kerah yang digambar dengan mempergunakan pola dasar muka dan belakang adalah kerah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    68,
    4,
    68,
    'Wool, beludru dan drill merupakan bahan dengan tekstur.…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    69,
    4,
    69,
    'Tujuan merancang bahan adalah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    70,
    4,
    70,
    'Untuk merubah model pakaian diperlukan pola ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    71,
    4,
    71,
    'Yang termasuk warna primer adalah.…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    72,
    4,
    72,
    'Segala sesuatu yang berhubungan dengan cara berpakaian disebut …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    73,
    4,
    73,
    'Yang harus diletakkan lebih dahulu pada waktu merancang bahan adalah pola',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    74,
    4,
    74,
    'Rok yang memakai beberapa lipit bertumpukan pada satu garis adalah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    75,
    4,
    75,
    'Krah berdiri tanpa penegak, apabila ditutup menjadi krah tegak adalah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    76,
    4,
    76,
    'Untuk menggambar pola pertama yang harus diperhatikan adalah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    77,
    4,
    77,
    'Tujuan mengikat lingkaran pinggang sebelum mengambil ukuran adalah ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    78,
    4,
    78,
    'Pada pakaian mahal, kumai serong dapat dipakai untuk ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    79,
    4,
    79,
    'Etika jabatan meliputi ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    80,
    4,
    80,
    'Kerja secara professional harus dilaksanakan karena merupakan ...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    81,
    5,
    81,
    'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya.........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    82,
    5,
    82,
    'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan penyelenggaraan pelatihan di instansi ini.......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    83,
    5,
    83,
    'Bagaimana pendapat Saudara tentang kedisiplinan petugas / panitia penyelenggara dalam memberikan pelayanan.........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    84,
    5,
    84,
    'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan..........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    85,
    5,
    85,
    'Bagaimana pendapat Saudara tentang petugas bengkel dalam memberikan pelayanan........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    86,
    5,
    86,
    'Bagaimana pendapat Saudara tentang petugas asrama dalam memberikan pelayanan.........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    87,
    5,
    87,
    'Bagaimana pendapat Saudara tentang petugas konsumsi dalam memberikan pelayanan.......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    88,
    5,
    88,
    'Bagaimana pendapat Saudara tentang ketersediaan Sarana dan Prasarana di instansi ini.......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    89,
    5,
    89,
    'Bagaimana pendapat Saudara tentang kebersihan tempat ibadah (mushola) yang ada di instansi ini.......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    90,
    5,
    90,
    'Bagaimana pendapat Saudara tentang kebersihan asrama/ lingkungan asrama........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    91,
    5,
    91,
    'Bagaimana pendapat Saudara tentang kebersihan kamar mandi/lingkungan kamar mandi......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    92,
    5,
    92,
    'Bagaimana pendapat Saudara tentang kebersihan lingkungan taman dan halaman......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    93,
    5,
    93,
    'Bagaimana pendapat Saudara tentang kebersihan bengkel / kelas /lingkungan kelas......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    94,
    5,
    94,
    'Bagiamana pendapat Saudara tentang kebersihan ruang makan/ lingkungan ruang makan ......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    95,
    5,
    95,
    'Bagaimana pendapat Saudara tentang keamanan pelayanan di instansi ini....',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    96,
    5,
    96,
    'Pesan Dan Kesan :',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    97,
    5,
    97,
    'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    98,
    5,
    98,
    'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    99,
    5,
    99,
    'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    100,
    5,
    100,
    'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    101,
    5,
    101,
    'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    102,
    5,
    102,
    'Bagimana pendapat Saudara tentang ketersediaan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    103,
    5,
    103,
    'Berapa persen materi yang anda serap',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    104,
    5,
    104,
    'Bagaimana menurut anda apakah perlu penambahan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    105,
    5,
    105,
    'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    106,
    5,
    106,
    'Apakah materi-materi pelatihan sangat mendukung kompetensi anda',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    107,
    5,
    107,
    'Pesan Dan Kesan :',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    108,
    5,
    108,
    'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    109,
    5,
    109,
    'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    110,
    5,
    110,
    'Bagaimana pendapat saudara tentang metode mengajar Instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    111,
    5,
    111,
    'bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    112,
    5,
    112,
    'bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    113,
    5,
    113,
    'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    114,
    5,
    114,
    'bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    115,
    5,
    115,
    'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    116,
    5,
    116,
    'Pesan dan Kesan',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    117,
    6,
    117,
    'Mengapa videografi menjadi semakin penting dalam pendidikan modern ?',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    118,
    6,
    118,
    'Berikut ini adalah manfaat penggunaan video dalam pembelajaran, kecuali...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    119,
    6,
    119,
    'Istilah yang merujuk pada jumlah gambar diam yang ditampilkan per detik dalam video adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    120,
    6,
    120,
    'Rasio aspek video widescreen standar yang paling umum digunakan saat ini adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    121,
    6,
    121,
    'Jenis shot yang menampilkan objek secara keseluruhan dengan latar belakang yang luas, sering digunakan untuk membuka video adalah…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    122,
    6,
    122,
    'Sudut pengambilan gambar dari bawah objek, yang membuat objek terlihat lebih besar dan dominan disebut...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    123,
    6,
    123,
    'Pergerakan kamera secara horizontal dari kiri ke kanan atau sebaliknya disebut...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    124,
    6,
    124,
    'Aturan komposisi visual yang membagi bingkai gambar menjadi sembilan bagian sama besar untuk menempatkan objek penting adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    125,
    6,
    125,
    'Jenis pencahayaan yang paling baik dan mudah dimanfaatkan untuk videografi adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    126,
    6,
    126,
    'Istilah untuk cahaya utama dalam three-point lighting yang berfungsi sebagai sumber cahaya terkuat dan membentuk bayangan adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    127,
    6,
    127,
    'Mikrofon kecil yang biasanya dijepitkan pada pakaian dan ideal untuk merekam suara pembicara adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    128,
    6,
    128,
    'Mengapa kualitas audio yang baik sangat penting dalam video?',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    129,
    6,
    129,
    'Dalam penyuntingan video dasar, timeline berfungsi untuk...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    130,
    6,
    130,
    'Teknik penyuntingan video dasar yang paling umum untuk menggabungkan dua klip video secara langsung tanpa transisi adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    131,
    6,
    131,
    'Langkah awal yang paling penting dalam membuat video pembelajaran yang efektif adalah…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    132,
    6,
    132,
    'Durasi video pembelajaran yang ideal sebaiknya...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    133,
    6,
    133,
    'Platform berbagi video online yang paling populer dan banyak digunakan untuk video pembelajaran adalah…',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    134,
    6,
    134,
    'Format file video yang paling umum dan direkomendasikan untuk diunggah secara online adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    135,
    6,
    135,
    'Etika penting yang harus diperhatikan dalam produksi video pendidikan kecuali...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    136,
    6,
    136,
    'Dalam teknik pencahayaan tiga titik (three-point lighting), lampu yang berfungsi untuk mengurangi bayangan keras yang dihasilkan oleh lampu utama (key light) dan memberikan detail pada area gelap adalah...',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    137,
    7,
    137,
    'Programmable Logic Controllers (PLC) adalah',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    138,
    7,
    138,
    'Berdasarkan namanya konsep PLC adalah sebagai berikut :Penjelasan di atas digambarkan. Pada gambar dibawah ini :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    139,
    7,
    139,
    'Kode 1a dan 1b adalah',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    140,
    7,
    140,
    'Input dan Output pada PLC dengan Type CP1E :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    141,
    7,
    141,
    'Bentuk / Macam Program Kontrol adalah',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    142,
    7,
    142,
    'Sebuah motor listrik di kontrol oleh tombol ……. dan ………',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    143,
    7,
    143,
    'Ledder Diagram yang sudah dibuat dan disimpan disebuah file harus ditransfer (download) kedalam memori PLC untuk bisa di jalankan pada PLC. Syarat dan ketentuan transfer program ke PLCadalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    144,
    7,
    144,
    'Tampilan di bawah ini adalah proses :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    145,
    7,
    145,
    'Proses mentransfer program ada 2 macam salah satunya adalah : Mentransfer dari PC/Laptop ke PLC tujuannya untuk mengirim program kontrol yang telah dibuat untuk dioprasikan pada PLC. Caranya klik PLC pilih Transfer dan pilih to PLC atau tekan Ctrl + T pada keyboard seperti gambar berikut. Gambar dibawah adalah Proses :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    146,
    7,
    146,
    'Gambar di bawah ini adalah proses :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    147,
    7,
    147,
    'TIMER adalah salah satu fasilitas yang ada pada sebuah PLC. Iya identik dan punya fungsi yang sama seperti :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    148,
    7,
    148,
    'COUNTER (CNT) Counter adalah salah satu fasilitas yang da pada sebuah PLC yang mempunyai 2 masukan yakni :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    149,
    7,
    149,
    'Counter berfungsi sebagai penghitung dalam program kontrol Counter mempunyai keluaran Output yang berupa :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    150,
    7,
    150,
    'Gambar di bawah ini adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    151,
    7,
    151,
    'DIFU (Differentiate Up) dan DIFD (Differentiate Down) adalah salah satu bagian dari Bit Control Instructions.:',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    152,
    7,
    152,
    'Perbedaan DIFU dan DIFD Pada Keluaran (Kontak NO dan Ncnya)',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    153,
    7,
    153,
    'Keterangan gambar di bawah ini adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    154,
    7,
    154,
    'Clock Pulse Bit :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    155,
    7,
    155,
    'Clock Pulse Bit identik dengan kontak NO/NC yang bekerja terus menerus memberi masukan 1 dan 0 (bekerja ON dan OFF) secara otomatis :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    156,
    7,
    156,
    'Untuk menggambarkan penjelasan diatas maka buatlah program kontrol yang menggunakan Clock Puls Bit dengan satuan detik seperti gambar berikut ini',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    157,
    8,
    157,
    'Proses pra produksi dalam fotografi produk adalah ketika …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    158,
    8,
    158,
    'Salah satu pengaruh foto produk terhadap kegiatan branding adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    159,
    8,
    159,
    'Rafathar ditunjuk sebagai fotografer di kelasnya, namun Ketika foto di dalam kelas, foto yang dihasilkan kamera mirrorless nya tampak gelap, yang harus dilakukan Rafathar adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    160,
    8,
    160,
    'Di bawah ini yang bukan termasuk konsep fotografi produk adalah :',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    161,
    8,
    161,
    'Yang bukan termasuk komposisi fotografi adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    162,
    8,
    162,
    'Dimanakah letak lampu yang digunakan pada foto dibawah ini …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    163,
    8,
    163,
    'Renatta adalah fotografer professional. Dia selalu mengedit foto produk setelah dia melakukan proses foto. Proses pengeditan dalam fotografi masuk ke dalam proses …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    164,
    8,
    164,
    'Berikut adalah hal hal yang harus dihindari saat foto produk, salah satunya adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    165,
    8,
    165,
    'Apperture pada settingan kamera digunakan untuk mengatur …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    166,
    8,
    166,
    'Erick ditunjuk PSSI sebagai fotografer tim nasional sepakbola, yang perlu Erick setting di kameranya agar Ketika pemain berlari kencang dia bisa membuat foto tersebut menjadi freeze adalah ....',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    167,
    8,
    167,
    'Apa yang diperlukan fotografer agar memiliki banyak klien …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    168,
    8,
    168,
    'Manakah settingan Apperture kamera yang benar ketika kita menginginkan foto dengan latar belakang yang blur / bokeh …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    169,
    8,
    169,
    'Mana di bawah ini yang merupakan aplikasi untuk mengedit foto yang sering digunakan oleh fotografer …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    170,
    8,
    170,
    'Manakah yang bukan kegunaan lighting pada fotografi produk …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    171,
    8,
    171,
    'Yang tidak termasuk dalam workflow fotografer produk adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    172,
    8,
    172,
    'Jika ingin membuat foto tampak bokeh/blur, apa yang perlu kita setting …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    173,
    8,
    173,
    'Saya ingin memotret objek yang bergerak menjadi tampak freeze/beku, apa yang perlu saya setting di kamera …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    174,
    8,
    174,
    'Proses menaikan warna pada aplikasi photoshop adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    175,
    8,
    175,
    'ISO memiliki kegunaan untuk mengatur …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    176,
    8,
    176,
    'Dibawah ini yang bukan termasuk proses produksi pada fotografi produk adalah …',
    NULL,
    'pilihan_ganda',
    NULL,
    NULL
  ),
  (
    177,
    9,
    177,
    'Bagaimana pendapat Saudara tentang kesesuaian jenis pelayanan dengan penyelenggaraannya.........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    178,
    9,
    178,
    'Bagaimana pendapat Saudara tentang kedisiplinan  penyelenggara dalam memberikan pelayanan.........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    179,
    9,
    179,
    'Bagaimana pendapat Saudara tentang kesopanan dan keramahan petugas penyelenggara dalam memberikan pelayanan..........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    180,
    9,
    180,
    'Bagaimana pendapat Saudara tentang petugas pendamping kegiatan dalam memberikan pelayanan........',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    181,
    9,
    181,
    'Bagaimana pendapat Saudara tentang ketersediaan konsumsi dalam kegiatan pelatihan vokasi melalui Mobile Training Unit (MTU).......',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    182,
    9,
    182,
    'Pesan Dan Kesan :',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    183,
    9,
    183,
    'Bagaimana pendapat Saudara tentang waktu yang disediakan dalam penyelenggaraan pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    184,
    9,
    184,
    'Bagaimana pendapat Saudara apakah pelatihan ini bermanfaat bagi anda.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    185,
    9,
    185,
    'Bagaimana pendapat Saudara tentang ketersediaan bahan-bahan praktek dalam pelaksanaan pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    186,
    9,
    186,
    'Bagaimana pendapat Saudara tentang ketersediaan mesin/peralatan untuk pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    187,
    9,
    187,
    'Bagaimana pendapat Saudara tentang ketersediaan kondisi mesin/peralatan pelatihan.',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    188,
    9,
    188,
    'Bagaimana pendapat Saudara tentang ketersediaan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    189,
    9,
    189,
    'Berapa persen materi yang anda serap',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    190,
    9,
    190,
    'Bagaimana menurut anda apakah perlu penambahan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    191,
    9,
    191,
    'Bagaimana menurut anda apakah perlu pengurangan materi pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    192,
    9,
    192,
    'Apakah materi-materi pelatihan sangat mendukung kompetensi anda',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    193,
    9,
    193,
    'Pesan Dan Kesan :',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    194,
    9,
    194,
    'Bagaimana pendapat saudara tentang penguasaan materi/ kompetensi pada proses pembelajaran',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    195,
    9,
    195,
    'Bagaimana pendapat saudara tentang kedisiplinan/ketepatan waktu Instruktur pada saat pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    196,
    9,
    196,
    'Bagaimana pendapat saudara tentang metode mengajar Instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    197,
    9,
    197,
    'bagaimana pendapat saudara tentang sikap dan prilaku instruktur pada saat memberikan pengajaran',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    198,
    9,
    198,
    'bagaimana pendapat saudara tentang kerapian dalam berpakaian instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    199,
    9,
    199,
    'Bagaimana pendapat saudara tentang penggunaan bahasa yang digunakan Instruktur',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    200,
    9,
    200,
    'bagaimana pendapat saudara tentang instruktur dalam memberikan motivasi pada peserta pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    201,
    9,
    201,
    'Bagaimana pendapat saudara cara instruktur menjawab pertanyaan dari peserta pelatihan',
    NULL,
    'skala_likert',
    NULL,
    NULL
  ),
  (
    202,
    9,
    202,
    'Intruktur terfavorit',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  ),
  (
    203,
    9,
    203,
    'Pesan dan Kesan',
    NULL,
    'teks_bebas',
    NULL,
    NULL
  );

