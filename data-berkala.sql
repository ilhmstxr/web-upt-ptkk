
-- data bidang
INSERT INTO
    `bidangs` (
        `id`,
        `nama_bidang`,
        `deskripsi`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Tata Boga',
        'Bidang keahlian yang mengajarkan teknik memasak, penyajian, dan manajemen makanan.',
        NULL,
        NULL
    ),
    (
        2,
        'Tata Busana',
        'Bidang keahlian untuk menjadi desainer busana yang handal, dari membuat pola hingga jahitan akhir.',
        NULL,
        NULL
    ),
    (
        3,
        'Kecantikan',
        'Bidang keahlian yang mengajarkan teknik perawatan kecantikan, mulai dari wajah hingga tubuh.',
        NULL,
        NULL
    ),
    (
        4,
        'Teknik Pendingin dan Tata Udara',
        'Bidang keahlian yang berfokus pada instalasi dan perawatan sistem pendingin dan tata udara.',
        NULL,
        NULL
    ),
    (
        5,
        'Web Desain',
        'Bidang keahlian yang mengajarkan pembuatan dan pengembangan situs web, termasuk desain antarmuka pengguna dan pengalaman pengguna.',
        NULL,
        NULL
    ),
    (
        6,
        'Desain Grafis',
        'Bidang keahlian yang mengajarkan pembuatan dan pengembangan karya desain grafis seperti poster, brosur, dan logo.',
        NULL,
        NULL
    ),
    (
        7,
        'Animasi',
        'Bidang keahlian yang mengajarkan pembuatan dan pengembangan animasi, seperti animasi 2D dan animasi 3D.',
        NULL,
        NULL
    ),
    (
        8,
        'Fotografi',
        'Bidang keahlian yang mengajarkan teknik pengambilan gambar, pengeditan foto, dan pencetakan.',
        NULL,
        NULL
    ),
    (
        9,
        'Videografi',
        'Bidang keahlian yang mengajarkan teknik pembuatan dan pengeditan video, termasuk sinematografi dan pascaproduksi.',
        NULL,
        NULL
    );


    -- cabang dinas


-- cabang dinas
INSERT INTO
  `cabang_dinas` (
    `id`,
    `nama`,
    `alamat`,
    `laman`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    'Cabang Dinas Pendidikan Wilayah Pasuruan',
    'Jl. Panglima Sudirman No.54, Kecamatan Purworejo, Kota Pasuruan, Jawa Timur',
    'https://pasuruancab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    2,
    'Cabang Dinas Pendidikan Wilayah Probolinggo',
    'Jl. Anggur, Wonoasih, Kecamatan Wonoasih, Kota Probolinggo, Jawa Timur 67232',
    'https://probolinggocab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    3,
    'Cabang Dinas Pendidikan Wilayah Bondowoso - Situbondo',
    'Jl. HOS Cokroaminoto No.121, Gudangmas, Kademangan, Kecamatan Bondowoso, Kabupaten Bondowoso, Jawa Timur 68217',
    'https://bondowosocab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    4,
    'Cabang Dinas Pendidikan Wilayah Jember - Lumajang',
    'Jl. Kalimantan No.42, Tegalboto, Krajan Timur, Kecamatan Sumbersari, Kabupaten Jember 68121',
    'https://cabdindikwilayahjember.com/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    5,
    'Cabang Dinas Pendidikan Wilayah Banyuwangi',
    'Jl. Basuki Rahmat No.46, Lateng, Kecamatan Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68414',
    'https://www.instagram.com/cabdinbanyuwangi/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    6,
    'Cabang Dinas Pendidikan Wilayah Kabupaten Malang',
    'Jl. Simpang Ijen No.2 Oro-oro Dowo, Kecamatan Klojen, Malang, Jawa Timur 65119',
    'https://malangcab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    7,
    'Cabang Dinas Pendidikan Wilayah Malang - Batu',
    'Jl. Anjasmoro No.40, Oro-oro Dowo, Kecamatan Klojen, Kota Malang, Jawa Timur 65119',
    'https://batu-malangkotacab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    8,
    'Cabang Dinas Pendidikan Wilayah Blitar',
    'Jl. Sultan Agung No.66, Sananwetan, Kecamatan Sananwetan, Kota Blitar, Jawa Timur 66137',
    NULL,
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    9,
    'Cabang Dinas Pendidikan Wilayah Tulungagung - Trenggalek',
    'Jl. Diponegoro, Tromertan, Krajan, Surodakan, Kecamatan Trenggalek, Kabupaten Trenggalek, Jawa Timur 66316',
    NULL,
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    10,
    'Cabang Dinas Pendidikan Wilayah Kediri',
    'Jl. Jaksa Agung Suprapto No.2, Mojoroto, Kecamatan Kediri, Kota Kediri, Jawa Timur 64112',
    'https://kediricab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    11,
    'Cabang Dinas Pendidikan Wilayah Nganjuk',
    'Jl. Brantas, Gg. III, Ngrengket, Kecamatan Sukomoro, Kabupaten Nganjuk, Jawa Timur 64481',
    'https://nganjukcab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    12,
    'Cabang Dinas Pendidikan Wilayah Madiun - Ngawi',
    'Jl. Pahlawan No.31, Kartoharjo, Kecamatan Kartoharjo, Kota Madiun, Jawa Timur 63121',
    'https://www.instagram.com/cabdindik_madiun/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    13,
    'Cabang Dinas Pendidikan Wilayah Ponorogo - Magetan',
    'Jl. Gajah Mada No.40, Pesantren. Surodikraman, Kecamatan Ponorogo, Kabupaten Ponorogo, Jawa Timur 63419',
    NULL,
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    14,
    'Cabang Dinas Pendidikan Wilayah Pacitan',
    'Jl. Raden Saleh, Kabupaten Pacitan, Jawa Timur',
    'https://pacitancab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    15,
    'Cabang Dinas Pendidikan Wilayah Bojonegoro - Tuban',
    'Jl. Panglima Sudirman No.36, Kelurahan Baturetno, Kecamatan Tuban, Kabupaten Tuban, Jawa Timur',
    'https://tubancab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    16,
    'Cabang Dinas Pendidikan Wilayah Lamongan',
    'Jl. Kombespol M. Duryat No.7, Kabupaten Lamongan, Jawa Timur',
    NULL,
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    17,
    'Cabang Dinas Pendidikan Wilayah Gresik',
    'Jl. DR. Wahidin Sudiro Husodo No.229, Kembangan, Kecamatan Kebomas, Kabupaten Gresik, Jawa Timur 61124',
    'https://gresikcab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    18,
    'Cabang Dinas Pendidikan Wilayah Sidoarjo',
    'Jl. Ponti No.09, Lingkar Barat, Kabupaten Sidoarjo, Provinsi Jawa Timur',
    'https://sidoarjocab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    19,
    'Cabang Dinas Pendidikan Wilayah Jombang',
    'Jl. DR. Wahidin Sudirohusodo No.6, Sengon, Kabupaten Jombang, Jawa Timur 61419',
    'https://www.instagram.com/cabdin_jombang/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    20,
    'Cabang Dinas Pendidikan Wilayah Mojokerto',
    'Jl. Hayam Wuruk No.66, Mergelo, Kecamatan Magersari, Mojokerto, Jawa Timur 61318',
    'https://www.instagram.com/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    21,
    'Cabang Dinas Pendidikan Wilayah Bangkalan',
    'Jl. Soekarno Hatta No.16, Mlajah, Kecamatan Bangkalan, Kabupaten Bangkalan, Jawa Timur 69116',
    NULL,
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    22,
    'Cabang Dinas Pendidikan Wilayah Sampang',
    'Jl. Merpati No.5, Kabupaten Sampang, Jawa Timur 69216',
    'https://www.instagram.com/cabdin_sampang/?hl=id',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    23,
    'Cabang Dinas Pendidikan Wilayah Pamekasan',
    'Jl. Slamet Riyadi No.01, Pamekasan Jawa Timur 69313',
    'https://pamekasancab.dindik.jatimprov.go.id/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  ),
  (
    24,
    'Cabang Dinas Pendidikan Wilayah Sumenep',
    'Jl. Urip Sumoharjo, Mastasek, Pabian, Kecamatan Kota Sumenep, Kabupaten Sumenep, Jawa Timur 69417',
    'https://www.instagram.com/cabdin_sumenep/',
    '2025-08-29 06:02:59',
    '2025-08-29 06:02:59'
  );


-- instansi
INSERT INTO
  `instansis` (
    `id`,
    `asal_instansi`,
    `alamat_instansi`,
    `bidang_keahlian`,
    `kelas`,
    `cabangDinas_id`,
    `user_id`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    2,
    'SMK NEGRI 2 BOJONEGORO',
    'JL.patimura NO.3',
    '4',
    'XI',
    '15',null,
    '2025-08-20 08:29:27',
    '2025-08-20 08:29:27'
  ),
  (
    3,
    'SMKN 2 KOTA PROBOLINGGO',
    'Jl. Mastrip No. 153 Kota Probolinggo',
    '4',
    'XI',
    '2',null,
    '2025-08-20 09:28:17',
    '2025-08-20 09:28:17'
  ),
  (
    4,
    'SMKN 3 Probolinggo',
    'Jl. Pahlawan No. 26A, Kota Probolinggo.',
    '3',
    'XI',
    '2',null,
    '2025-08-20 10:06:36',
    '2025-08-20 10:06:36'
  ),
  (
    5,
    'SMKN 1 JATIREJO',
    'Jln. Sumengko Jatirejoo',
    '4',
    'XI',
    '20',null,
    '2025-08-20 11:23:32',
    '2025-08-20 11:23:32'
  ),
  (
    6,
    'SMK NEGERI 1 NGASEM',
    'Jl. Totok Kerot Ds. Sumberejo Kabupaten Kediri',
    '1',
    'XI',
    '10',null,
    '2025-08-20 11:58:13',
    '2025-08-20 11:58:13'
  ),
  (
    7,
    'SMKN 1TUBAN',
    'Jl. Mastrip No.2, Sidorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62315',
    '4',
    'XI',
    '15',null,
    '2025-08-20 12:13:59',
    '2025-08-20 12:13:59'
  ),
  (
    8,
    'SMK Negri 2 Blitar',
    'Jl. Raya Kediri - Blitar, Pakunden, Kec. Sukorejo, Kota Blitar, Jawa Timur 66122',
    '2',
    'XI',
    '8',null,
    '2025-08-20 12:19:16',
    '2025-08-20 12:19:16'
  ),
  (
    9,
    'SMKN 1 Gedangan',
    'Jl Raya Sumberejo Kecamatan Gedangan Kabupaten Malang',
    '1',
    'XI',
    '6',null,
    '2025-08-20 12:25:07',
    '2025-08-20 12:25:07'
  ),
  (
    10,
    'SMK NEGERI 2 LAMONGAN',
    'Jl. Veteran no. 7A Lamongan',
    '2',
    'XI',
    '16',null,
    '2025-08-20 12:30:28',
    '2025-08-20 12:30:28'
  ),
  (
    13,
    'SMKN 1 Pungging',
    'Dsn. Lebaksono Ds. Lebaksono Kec. pungging Kab. Mojokerto',
    '4',
    'XI',
    '20',null,
    '2025-08-20 13:04:04',
    '2025-08-20 13:04:04'
  ),
  (
    14,
    'SMK NEGERI 1 BANGIL',
    'JL. Tongkol No.3 Sukalipuro, Dermo, Bangil, Pasuruan, Jawa Timur 67153',
    '2',
    'XI',
    '1',null,
    '2025-08-20 13:10:17',
    '2025-08-20 13:10:17'
  ),
  (
    15,
    'SMKN 1 Kertosono',
    'Jl. Langsep 24 Kertosono nganjuk',
    '2',
    'XI',
    '11',null,
    '2025-08-20 13:57:25',
    '2025-08-20 13:57:25'
  ),
  (
    16,
    'SMK Negeri 2 Nganjuk',
    'Jl Lawu No.3, Kab. Nganjuk',
    '3',
    'XI',
    '11',null,
    '2025-08-20 14:09:41',
    '2025-08-20 14:09:41'
  ),
  (
    17,
    'SMKN 1 CERME GRESIK',
    'Jl. Jurit, Cerme Kidul, Kec. Cerme, Kabupaten Gresik, Jawa Timur 61171',
    '4',
    'XI',
    '17',null,
    '2025-08-20 14:12:20',
    '2025-08-20 14:12:20'
  ),
  (
    18,
    'SMKN 2 BONDOWOSO',
    'Jl. a yani 48 Bondowoso',
    '3',
    'XI',
    '3',null,
    '2025-08-20 14:28:22',
    '2025-08-20 14:28:22'
  ),
  (
    19,
    'SMK Negeri 1 Purwoasri',
    'Ds. Mranggen Kec. Purwoasri, Kab.Kediri',
    '4',
    'XI',
    '10',null,
    '2025-08-20 14:44:46',
    '2025-08-20 14:44:46'
  ),
  (
    22,
    'SMKN 1 BANYUPUTIH',
    'Jl. KH Ahmad Zaini Dahlan, Bindung, Sumberanyar, Kec. Banyuputih, Kabupaten Situbondo, Jawa Timur',
    '2',
    'XI',
    '3',null,
    '2025-08-20 15:12:10',
    '2025-08-20 15:12:10'
  ),
  (
    23,
    'smkn1 Lamongan',
    'jl. panglima sudirmam no 84 lamongan',
    '3',
    'XI',
    '16',null,
    '2025-08-20 20:24:24',
    '2025-08-20 20:24:24'
  ),
  (
    24,
    'SMK Negeri 1 Grogol',
    'Jalan Raya Cerme, Dusun Glatik, Desa Cerme, Kecamatan Grogol, Kabupaten Kediri',
    '2',
    'XI',
    '10',null,
    '2025-08-21 01:58:09',
    '2025-08-21 01:58:09'
  ),
  (
    25,
    'SMKN 1 Tamanan',
    'Jl. Maesan No, - Tamanan Bondowoso',
    '2',
    'XI',
    '3',null,
    '2025-08-21 02:29:10',
    '2025-08-21 02:29:10'
  ),
  (
    26,
    'SMK NEGERI 1 DRIYOREJO',
    'Jalan Mirah Delima Kota Baru Driyorejo (KBD) Kabupaten Gresik',
    '4',
    'XI',
    '17',null,
    '2025-08-21 02:48:09',
    '2025-08-21 02:48:09'
  ),
  (
    27,
    'SMKN 1 SAMBENG - LAMONGAN',
    'Jalan Raya Pasarlegi 01 Sambeng - Lamongan',
    '2',
    'XI',
    '16',null,
    '2025-08-21 02:49:15',
    '2025-08-21 02:49:15'
  ),
  (
    28,
    'SMKN 1 Sumberasih',
    'Jl. Brawijaya N0 78 Lemah Kembar Sumberasih Kabupaten Probolinggo',
    '4',
    'XI',
    '2',null,
    '2025-08-21 03:14:17',
    '2025-08-21 03:14:17'
  ),
  (
    29,
    'SMK Negeri 1 Sooko Mojokerto',
    'Jalan RA. Basuni, Nomor 5, Sooko, Mojokerto',
    '3',
    'XI',
    '20',null,
    '2025-08-21 03:33:49',
    '2025-08-21 03:33:49'
  ),
  (
    30,
    'SMK NEGERI 1 BRONDONG',
    'JL. Raya Brondong Ds. Tlogoretno Kec. Brondong Kab. Lamongan',
    '2',
    'XI',
    '16',null,
    '2025-08-21 03:40:57',
    '2025-08-21 03:40:57'
  ),
  (
    31,
    'SMK Negri 1 turen',
    'Jl. Panglima Sudirman No. 41 Turen',
    '2',
    'XI',
    '6',null,
    '2025-08-21 03:54:31',
    '2025-08-21 03:54:31'
  ),
  (
    32,
    'SMK NEGRI 2 BOJONEGORO',
    'Jl. Patimura No. 3',
    '4',
    'XI',
    '15',null,
    '2025-08-21 05:02:39',
    '2025-08-21 05:02:39'
  ),
  (
    33,
    'SMK Negeri 1 Kras',
    'Dusun Demangan Desa Setonorejo Kec. Kras Kabupaten Kediri',
    '1',
    'XI',
    '10',null,
    '2025-08-21 07:07:23',
    '2025-08-21 07:07:23'
  ),
  (
    34,
    'SMK Negeri 1 Dlanggu',
    'Jl. Jend. A.Yani 1 Ds.Pohkecik Kec. Dlanggu Kab.Mojokerto',
    '1',
    'XI',
    '20',null,
    '2025-08-21 09:06:51',
    '2025-08-21 09:06:51'
  ),
  (
    35,
    'SMKN 2 BAGOR',
    'Jalan Raya Solo No. 146, Selorejo, Kecamatan Bagor, Kabupaten Nganjuk, Jawa Timur, 64461',
    '1',
    'XI',
    '11',null,
    '2025-08-21 12:12:02',
    '2025-08-21 12:12:02'
  ),
  (
    36,
    'SMK Negeri 2 Mojokerto',
    'Jl. Raya Pulorejo, Mergelo, Pulorejo, Kec. Prajurit Kulon, Kota Mojokerto, Jawa Timur 61325',
    '1',
    'XI',
    '20',null,
    '2025-08-21 12:17:34',
    '2025-08-21 12:17:34'
  ),
  (
    37,
    'SMK Negeri 3 Blitar',
    'Jl. Soedanco Supriadi No. 24C, Bendogerit, Kec. Sananwetan, Kota Blitar, Jawa Timur 66133',
    '3',
    'XI',
    '8',null,
    '2025-08-21 13:56:56',
    '2025-08-21 13:56:56'
  ),
  (
    38,
    'SMKN 1 Panji Situbondo',
    'Jl. Gunung Arjuno 17, Mimbaan, Situbondo 68322',
    '3',
    'XI',
    '3',null,
    '2025-08-21 14:23:25',
    '2025-08-21 14:23:25'
  ),
  (
    39,
    'SMKN 3 Malang',
    'Jl. Surabaya no.1 gading kasri kec.klojen',
    '2',
    'XI',
    '7',null,
    '2025-08-22 02:54:10',
    '2025-08-22 02:54:10'
  ),
  (
    40,
    'SMK Negeri 1 Sawoo',
    'Jalan Route PB Jend. Soedirman No 02 Sawoo, Ponorogo',
    '1',
    'XI',
    '13',null,
    '2025-08-22 03:04:40',
    '2025-08-22 03:04:40'
  ),
  (
    41,
    'SMKN 3 MALANG',
    'Jl.surabaya no.1.gading kasri kec.klojen',
    '2',
    'XI',
    '7',null,
    '2025-08-22 03:13:06',
    '2025-08-22 03:13:06'
  ),
  (
    42,
    'SMKN 3 Malang',
    'Jl. Surabaya no. 1 kelurahan gading kadri kec. Klojen',
    '2',
    'XI',
    '7',null,
    '2025-08-22 03:29:58',
    '2025-08-22 03:29:58'
  ),
  (
    43,
    'SMKN 1 Badegan',
    'jl Suyudono no. 1 Badegan Kab. Ponorogo',
    '1',
    'XI',
    '13',null,
    '2025-08-22 03:50:52',
    '2025-08-22 03:50:52'
  ),
  (
    44,
    'SMKN 1 BATU',
    'JL BROMO 11 KOTA BATU',
    '1',
    'XI',
    '7',null,
    '2025-08-22 04:13:21',
    '2025-08-22 04:13:21'
  ),
  (
    45,
    'SMKN 1 BATU',
    'JL BROMO 11 KOTA BATU',
    '3',
    'XI',
    '7',null,
    '2025-08-22 04:18:43',
    '2025-08-22 04:18:43'
  ),
  (
    46,
    'SMKN 1 BATU',
    'JL BROMO 11 KOTA BATU',
    '1',
    'XI',
    '7',null,
    '2025-08-22 05:14:42',
    '2025-08-22 05:14:42'
  ),
  (
    47,
    'SMKN 1 BATU',
    'JL BROMO 11 KOTA BATU',
    '1',
    'XI',
    '7',null,
    '2025-08-22 05:19:47',
    '2025-08-22 05:19:47'
  ),
  (
    48,
    'SMK Negeri Prigen',
    'Jl.Pecalukan-Ledug',
    '1',
    'XI',
    '1',null,
    '2025-08-22 05:39:12',
    '2025-08-22 05:39:12'
  ),
  (
    49,
    'SMK Negeri 1 Kota Kediri',
    'Jl. Veteran 9 Mojoroto, Kota Kediri',
    '4',
    'XI',
    '10',null,
    '2025-08-22 06:11:46',
    '2025-08-22 06:11:46'
  ),
  (
    50,
    'SMKN 3 Malang',
    'Jl Surabaya no, 1 , Gading kasri, Kota MalangKec. Klojen',
    '1',
    'XI',
    '7',null,
    '2025-08-22 06:50:04',
    '2025-08-22 06:50:04'
  ),
  (
    51,
    'SMKN 1 Nglegok',
    'Jl. Penataran No. 1 Nglegok',
    '1',
    'XI',
    '8',null,
    '2025-08-22 07:00:55',
    '2025-08-22 07:00:55'
  ),
  (
    52,
    'SMK Negeri 1 Klabang',
    'Jln. Raya Blimbing no. 12 Desa Klabang Kec. Klabang Kab. Bondowoso',
    '4',
    'XI',
    '3',null,
    '2025-08-22 07:03:44',
    '2025-08-22 07:03:44'
  ),
  (
    53,
    'SMK Negeri 1 Batu',
    'Jalan Bromo no 11 Sisir, Batu',
    '2',
    'XI',
    '7',null,
    '2025-08-22 07:40:52',
    '2025-08-22 07:40:52'
  ),
  (
    54,
    'SMKN 1 Batu',
    'Jalan Bromo no 11 sisir, batu',
    '2',
    'XI',
    '7',null,
    '2025-08-22 07:53:55',
    '2025-08-22 07:53:55'
  ),
  (
    55,
    'SMKN 1 Slahung Ponorogo',
    'jln Macan Tutul, Galak, Kec Slahung, Kab Ponorogo',
    '1',
    'XI',
    '13',null,
    '2025-08-22 07:58:16',
    '2025-08-22 07:58:16'
  ),
  (
    56,
    'SMKN 2 MALANG',
    'Jl. Veteran no.17',
    '1',
    'XI',
    '7',null,
    '2025-08-22 09:32:46',
    '2025-08-22 09:32:46'
  ),
  (
    57,
    'SMK Negeri 2 Bangkalan',
    'Jl. Halim Perdana Kusuma (Ring road) Bangkalan',
    '4',
    'XI',
    '21',null,
    '2025-08-22 11:13:03',
    '2025-08-22 11:13:03'
  ),
  (
    58,
    'SMK NEGERI 1 BEJI',
    'Jl. Wicaksana No. 22b, Gununggangsir, Beji, Pasuruan, Jawa Timur, 67154.',
    '4',
    'XI',
    '1',null,
    '2025-08-22 11:45:00',
    '2025-08-22 11:45:00'
  ),
  (
    59,
    'SMK Negeri 3 Malang',
    'Jl. Surabaya No. 1',
    '3',
    'XII',
    '7',null,
    '2025-08-22 12:16:46',
    '2025-08-22 12:16:46'
  ),
  (
    60,
    'SMK NEGERI 3 MALANG',
    'JL. Surabaya No 1, Klojen, Malang',
    '3',
    'XII',
    '7',null,
    '2025-08-22 12:22:18',
    '2025-08-22 12:22:18'
  ),
  (
    61,
    'SMKN 1 LAMONGAN',
    'jl. panglima sudirmam no 84 lamongan',
    '3',
    'XI',
    '16',null,
    '2025-08-22 13:14:10',
    '2025-08-22 13:14:10'
  ),
  (
    62,
    'SMK NEGERI 2 MAGETAN',
    'JL KEMASAN NO 13 MAGETAN',
    '3',
    'XI',
    '13',null,
    '2025-08-22 13:34:42',
    '2025-08-22 13:34:42'
  ),
  (
    63,
    'SMK NEGERI 2 PONOROGO',
    'Jl Laksamana Yos Sudarso No. 21a, Kelurahan Kepatihan, Kecamatan Ponorogo, Kabupaten Ponorogo, Jawa Timur',
    '3',
    'XI',
    '13',null,
    '2025-08-22 13:39:15',
    '2025-08-22 13:39:15'
  ),
  (
    64,
    'SMKN 1 BANGKALAN',
    'Jl. Kenanga N0.04,Mlajah - Bangkalan, 69116',
    '2',
    'XI',
    '21',null,
    '2025-08-22 13:41:46',
    '2025-08-22 13:41:46'
  ),
  (
    65,
    'TEKNIK INSTALASITENAGA LISTRIK',
    'SMKN 1 SINGOSARI',
    '4',
    'XI',
    '6',null,
    '2025-08-22 13:42:27',
    '2025-08-22 13:42:27'
  ),
  (
    66,
    'SMK NEGERI 2 TRENGGALEK',
    'Jl. Ronggo Warsito Gg. Sidomukti No. 1, Kec. Trenggalek, Kab. Trenggalek',
    '4',
    'XI',
    '9',null,
    '2025-08-23 09:23:46',
    '2025-08-23 09:23:46'
  ),
  (
    67,
    'SMKN 3 MALANG',
    'Jl. Surabaya No.1, Gading Kasri, Kec. Klojen, Kota Malang',
    '1',
    'XII',
    '7',null,
    '2025-08-23 14:19:14',
    '2025-08-23 14:19:14'
  ),
  (
    68,
    'SMKN 1 PAGERGOJO',
    'Jl. Pagerwojo Ds. Mulyosari Pagerwojo',
    '3',
    'XI',
    '9',null,
    '2025-08-29 11:40:45',
    '2025-08-29 11:40:45'
  );



-- pelatihan
INSERT INTO
  `pelatihans` (
    `id`,
    `instansi_id`,
    `nama_pelatihan`,
    `slug`,
    `gambar`,
    `tanggal_mulai`,
    `tanggal_selesai`,
    `deskripsi`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    NULL,
    'Kegiatan Pengembangan dan Pelatihan Kompetensi Vokasi bagi Siswa SMA/SMK (MILEA) menuju Generasi Emas 2045 (Kelas Keterampilan) Angkatan II Tahun 2025',
    NULL,
    NULL,
    '2025-09-01',
    '2025-09-06',
    NULL,
    NULL,
    NULL
  );


-- tes
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
  );


-- pertanyaan
INSERT INTO
  `pertanyaans` (
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
  );


-- opsi jawaban
INSERT INTO
  `opsi_jawabans` (
    `id`,
    `pertanyaan_id`,
    `teks_opsi`,
    `gambar`,
    `apakah_benar`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    1,
    'Recovery, recycling dan recharging pada mesin pendingin.',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    2,
    1,
    'Recycling dan recharging pada mesin pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    3,
    1,
    'Reciever, Recovery, Recharge pada mesin pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    4,
    1,
    'Recharge pada mesin pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (5, 1, 'Semua Jawaban Salah', NULL, 0, NULL, NULL),
  (
    6,
    2,
    'Tidak bersamaan dalam satu waktu',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    7,
    2,
    'Bergantian dalam proses',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    8,
    2,
    'Bergantian dan berjedah dalam satu proses',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    9,
    2,
    'Bersamaan dalam satu waktu',
    NULL,
    1,
    NULL,
    NULL
  ),
  (10, 2, 'Semua Jawaban Salah', NULL, 0, NULL, NULL),
  (
    11,
    3,
    'Recovery pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    12,
    3,
    'Recovery pada refrigerasi yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    13,
    3,
    'Recharging pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    14,
    3,
    'Pump Down pada system bagian katup SSV dan DSV pada mesin pendingin yaitu proses menampung refrigerant yang ada pada sistem pendingin. Penampungan ini dilakukan karena sistem akan melakukan perawatan atau perbaikan.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (15, 3, 'Semua Jawaban Benar', NULL, 0, NULL, NULL),
  (
    16,
    4,
    'Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    17,
    4,
    'Refrigerant tidak dapat di pump down.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    18,
    4,
    'Refrigerant tidak lancer saat di pump down.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    19,
    4,
    'Unit mengalami kerusakan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    20,
    4,
    'Unit mengalami kerusakan dan memerlukan penggatian part atau pengelasan dan Refrigerant tidak dapat di pump down.',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    21,
    5,
    'Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    22,
    5,
    'Mesin Recovery, Manifold gauge, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    23,
    5,
    'Mesin Recovery, Refrigerant Scale, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    24,
    5,
    'Mesin Recovery, pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    25,
    5,
    'Pompa vacuum, recovery tank, Gas refrigerant, Filter Dryer.',
    NULL,
    0,
    NULL,
    NULL
  ),
  (26, 6, 'Proses pump down', NULL, 0, NULL, NULL),
  (27, 6, 'Proses Recharging', NULL, 0, NULL, NULL),
  (
    28,
    6,
    'Proses penggantian part',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    29,
    6,
    'Proses Recovery Freon',
    NULL,
    1,
    NULL,
    NULL
  ),
  (30, 6, 'Semua Jawaban Salah', NULL, 0, NULL, NULL),
  (
    31,
    7,
    'Proses Recovery Refigerant',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    32,
    7,
    'Proses Recovery Refigerant',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    33,
    7,
    'Proses Recovery Refigerant',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    34,
    7,
    'Proses Recovery Refigerant',
    NULL,
    0,
    NULL,
    NULL
  ),
  (35, 7, 'Semua Jawaban Salah', NULL, 0, NULL, NULL),
  (
    36,
    8,
    'Pengisian zat pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    37,
    8,
    'Evakuasi Udara dan kotoran',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    38,
    8,
    'Evakuasi Olie pada mesin pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    39,
    8,
    'Evakuasi system pendingin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (40, 8, 'Semua Jawaban Salah', NULL, 0, NULL, NULL),
  (41, 9, 'Vacuum', NULL, 0, NULL, NULL),
  (42, 9, 'Charging proses', NULL, 0, NULL, NULL),
  (
    43,
    9,
    'Vacuum dan Inlet sistem proses',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    44,
    9,
    'Vacuum dan Oultet Charging proses',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    45,
    9,
    'Vacuum dan Charging proses',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    46,
    10,
    'Vaccum dan Charging Proses',
    NULL,
    1,
    NULL,
    NULL
  ),
  (47, 10, 'Charging Proses', NULL, 0, NULL, NULL),
  (48, 10, 'Vaccum', NULL, 0, NULL, NULL),
  (49, 10, 'Proses Reciever', NULL, 0, NULL, NULL),
  (
    50,
    10,
    'Semua jawaban benar',
    NULL,
    0,
    NULL,
    NULL
  ),
  (51, 11, 'gambar 1', NULL, 0, NULL, NULL),
  (52, 11, 'gambar 2', NULL, 0, NULL, NULL),
  (53, 11, 'gambar 3', NULL, 0, NULL, NULL),
  (54, 11, 'gambar 4', NULL, 0, NULL, NULL),
  (55, 11, 'gambar 5', NULL, 1, NULL, NULL),
  (56, 12, 'gambar 1', NULL, 0, NULL, NULL),
  (57, 12, 'gambar 2', NULL, 0, NULL, NULL),
  (58, 12, 'gambar 3', NULL, 0, NULL, NULL),
  (59, 12, 'gambar 4', NULL, 1, NULL, NULL),
  (
    60,
    12,
    'Semua Jawaban Salah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (61, 13, 'gambar 1', NULL, 0, NULL, NULL),
  (62, 13, 'gambar 2', NULL, 0, NULL, NULL),
  (63, 13, 'gambar 3', NULL, 0, NULL, NULL),
  (64, 13, 'gambar 4', NULL, 1, NULL, NULL),
  (
    65,
    13,
    'Semua Jawaban Benar',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    66,
    14,
    '4. Cuci filter dengan sabun (max PH 7)',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    67,
    14,
    '4. Kemudian bilas dengan menggunakan air bersih (T = max 40oC (40 derajat celcius))',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    68,
    14,
    '4. Cuci filter dengan sabun kemudian bilas dengan menggunakan air bersih',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    69,
    14,
    '4. Cuci filter dengan sabun (max PH 7); kemudian bilas dengan menggunakan air bersih (T = max 40oC (40 derajat celcius))',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    70,
    14,
    'Cuci filter dengan sabun kemudian bilas dengan menggunakan air bersih',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    71,
    15,
    'Proses yang harus dilakukan adalah hidupkan unit AC minimal 15 menit sebelum pengambilan data; pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max; ambil data yang dibutuhkan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    72,
    15,
    'Pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max; ambil data yang dibutuhkan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    73,
    15,
    'Ambil data yang dibutuhkan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    74,
    15,
    'Hidupkan unit AC minimal 15 menit sebelum pengambilan data; pastikan pengaturan remote ada pada mode cool; tempt. 16oC; kecepatan kipas max',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    75,
    15,
    'Semua jawaban salah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (76, 16, 'Skala sedang', NULL, 0, NULL, NULL),
  (
    77,
    16,
    'Skala berkala bulanan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (78, 16, 'Skala kecil', NULL, 0, NULL, NULL),
  (
    79,
    16,
    'Skala Pemeliharaan bulanan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    80,
    16,
    'Semua Jawaban Salah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    81,
    17,
    '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply; #Kanebo',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    82,
    17,
    '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    83,
    17,
    '#Kunci Inggris 6 atau 8 Inch atau Kunci Pas 14; #Kabel Power Supply; #Kanebo; #Sabut/Busa; #Sabun',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    84,
    17,
    '#Sabut/Busa; #Sabun',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    85,
    17,
    'Semua Jawaban Salah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (86, 18, 'gambar 1', NULL, 0, NULL, NULL),
  (87, 18, 'gambar 2', NULL, 0, NULL, NULL),
  (88, 18, 'gambar 3', NULL, 0, NULL, NULL),
  (89, 18, 'gambar 4', NULL, 0, NULL, NULL),
  (90, 18, 'gambar 5', NULL, 1, NULL, NULL),
  (
    91,
    19,
    'Cross Flow Fan dan selang pembuangan air',
    NULL,
    1,
    NULL,
    NULL
  ),
  (92, 19, 'Cross Flow Fan', NULL, 0, NULL, NULL),
  (
    93,
    19,
    'Selang pembuangan air',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    94,
    19,
    'Cross Flow Fan dan selang pembuangan air out door',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    95,
    19,
    'Fan dan selang pembuangan air',
    NULL,
    0,
    NULL,
    NULL
  ),
  (96, 20, 'Fan dan Cabinet', NULL, 0, NULL, NULL),
  (97, 20, 'Cabinet', NULL, 0, NULL, NULL),
  (98, 20, 'Propeller Fan', NULL, 0, NULL, NULL),
  (
    99,
    20,
    'Propeller Fan dan Cabinet outdoor',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    100,
    20,
    'Propeller dan Cabinet outbow',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    101,
    21,
    'Menentukan jenis rambut dan kulit kepala sehingga dapat menentukan perawatan kosmetika yang sesuai',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    102,
    21,
    'Menentukan warna pigmen rambut sehingga dapat menentukan jenis perawatan rambut',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    103,
    21,
    'Meningkatkan peredaran darah dan mengaktifkan syaraf-syaraf pada kulit kepala',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    104,
    21,
    'Menentukan teknik penataan yang sesua jens rambut',
    NULL,
    0,
    NULL,
    NULL
  ),
  (105, 22, 'egg shampoo', NULL, 0, NULL, NULL),
  (106, 22, 'lemon shampoo', NULL, 1, NULL, NULL),
  (107, 22, 'medical shampoo', NULL, 0, NULL, NULL),
  (
    108,
    22,
    'anti hair fall shampoo',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    109,
    23,
    'bersih dan tak berbau.',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    110,
    23,
    'dingin dan tak berbau',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    111,
    23,
    'berwarna dan tak berbau',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    112,
    23,
    'hangat dan tak berbau',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    113,
    24,
    'effleurage,tapotage,friction,petrissage, vibration',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    114,
    24,
    'effleurage, rubbing,petrisage,friction',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    115,
    24,
    'effleurage,kneading,vibration,tapotage,petrissage',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    116,
    24,
    'effleurage, scrubing, kneading,petrissage',
    NULL,
    0,
    NULL,
    NULL
  ),
  (117, 25, 'shampoo', NULL, 0, NULL, NULL),
  (118, 25, 'solution', NULL, 0, NULL, NULL),
  (119, 25, 'setting lotion', NULL, 0, NULL, NULL),
  (120, 25, 'conditioner', NULL, 1, NULL, NULL),
  (121, 26, 'blow in', NULL, 1, NULL, NULL),
  (122, 26, 'blow out', NULL, 0, NULL, NULL),
  (123, 26, 'blow round', NULL, 0, NULL, NULL),
  (124, 26, 'blow horizontal', NULL, 0, NULL, NULL),
  (
    125,
    27,
    'tengah ke ujung rambut',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    126,
    27,
    'pangkal ke bagian tengah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    127,
    27,
    'pangkal ke ujung rambut',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    128,
    27,
    'tengah ke pangkal rambut',
    NULL,
    0,
    NULL,
    NULL
  ),
  (129, 28, '5 cm', NULL, 0, NULL, NULL),
  (130, 28, '15 cm', NULL, 0, NULL, NULL),
  (131, 28, '30 cm', NULL, 1, NULL, NULL),
  (132, 28, '20 cm', NULL, 0, NULL, NULL),
  (
    133,
    29,
    'memperlancar peredaran darah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    134,
    29,
    'menenangkan urat syaraf',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    135,
    29,
    'meningkatkan dan mempercepat sirkulasi darah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    136,
    29,
    'mencegah kebotakan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (137, 30, 'hair dryer', NULL, 0, NULL, NULL),
  (138, 30, 'hair curler', NULL, 0, NULL, NULL),
  (139, 30, 'hair clip', NULL, 0, NULL, NULL),
  (140, 30, 'hair steamer', NULL, 1, NULL, NULL),
  (141, 31, 'Sadah', NULL, 0, NULL, NULL),
  (142, 31, 'Lunak', NULL, 1, NULL, NULL),
  (143, 31, 'Garam', NULL, 0, NULL, NULL),
  (144, 31, 'Keras', NULL, 0, NULL, NULL),
  (145, 32, 'Berminyak', NULL, 0, NULL, NULL),
  (146, 32, 'Kering', NULL, 1, NULL, NULL),
  (147, 32, 'Normal', NULL, 0, NULL, NULL),
  (148, 32, 'Berketombe', NULL, 0, NULL, NULL),
  (149, 33, 'Hairspray', NULL, 0, NULL, NULL),
  (150, 33, 'Hairshine', NULL, 1, NULL, NULL),
  (151, 33, 'Hairdryer', NULL, 0, NULL, NULL),
  (152, 33, 'Haircream', NULL, 0, NULL, NULL),
  (
    153,
    34,
    'Mencegah timbulnya uban',
    NULL,
    0,
    NULL,
    NULL
  ),
  (154, 34, 'Merawat ketombe', NULL, 0, NULL, NULL),
  (
    155,
    34,
    'Memperlancar peredaran darah',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    156,
    34,
    'Mempersingkat fase anogen',
    NULL,
    0,
    NULL,
    NULL
  ),
  (157, 35, 'Steamer', NULL, 0, NULL, NULL),
  (158, 35, 'Accelerator', NULL, 0, NULL, NULL),
  (159, 35, 'Frekuensi tinggi', NULL, 0, NULL, NULL),
  (160, 35, 'Droogkap', NULL, 1, NULL, NULL),
  (161, 36, 'Personalia', NULL, 0, NULL, NULL),
  (162, 36, 'Personality', NULL, 1, NULL, NULL),
  (163, 36, 'Persona', NULL, 0, NULL, NULL),
  (
    164,
    36,
    'Professional ethics',
    NULL,
    0,
    NULL,
    NULL
  ),
  (165, 37, 'Zat kapur', NULL, 0, NULL, NULL),
  (166, 37, 'Tembaga', NULL, 0, NULL, NULL),
  (167, 37, 'Zat besi', NULL, 0, NULL, NULL),
  (168, 37, 'Fosfor', NULL, 1, NULL, NULL),
  (169, 38, 'Two in one', NULL, 0, NULL, NULL),
  (170, 38, 'Beauty', NULL, 0, NULL, NULL),
  (171, 38, 'Antiseptik', NULL, 1, NULL, NULL),
  (172, 38, 'Egg', NULL, 0, NULL, NULL),
  (173, 39, 'Egg', NULL, 1, NULL, NULL),
  (174, 39, 'Dry', NULL, 0, NULL, NULL),
  (175, 39, 'Cream', NULL, 0, NULL, NULL),
  (176, 39, 'Beauty', NULL, 0, NULL, NULL),
  (177, 40, 'Mencubit', NULL, 0, NULL, NULL),
  (178, 40, 'Mengetuk', NULL, 0, NULL, NULL),
  (179, 40, 'Mengusap', NULL, 1, NULL, NULL),
  (180, 40, 'Meremas', NULL, 0, NULL, NULL),
  (181, 41, 'Tepung terigu', NULL, 0, NULL, NULL),
  (182, 41, 'Telur', NULL, 0, NULL, NULL),
  (183, 41, 'Vanili', NULL, 1, NULL, NULL),
  (184, 41, 'Gula', NULL, 0, NULL, NULL),
  (185, 42, 'Rendah', NULL, 0, NULL, NULL),
  (186, 42, 'Tinggi', NULL, 1, NULL, NULL),
  (187, 42, 'Sedang', NULL, 0, NULL, NULL),
  (188, 42, 'Cukup', NULL, 0, NULL, NULL),
  (189, 43, 'Yeast', NULL, 1, NULL, NULL),
  (190, 43, 'Telur', NULL, 0, NULL, NULL),
  (191, 43, 'Gula', NULL, 0, NULL, NULL),
  (192, 43, 'Margarin', NULL, 0, NULL, NULL),
  (193, 44, 'Gula kastor', NULL, 1, NULL, NULL),
  (194, 44, 'Gula halus', NULL, 0, NULL, NULL),
  (195, 44, 'Gula palem', NULL, 0, NULL, NULL),
  (196, 44, 'Gula jawa', NULL, 0, NULL, NULL),
  (197, 45, '5 %', NULL, 0, NULL, NULL),
  (198, 45, '1-2 %', NULL, 1, NULL, NULL),
  (199, 45, '3 %', NULL, 0, NULL, NULL),
  (200, 45, '10%', NULL, 0, NULL, NULL),
  (201, 46, 'Perasa', NULL, 0, NULL, NULL),
  (
    202,
    46,
    'Membuat warna roti',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    203,
    46,
    'Stabilitator fermentation',
    NULL,
    1,
    NULL,
    NULL
  ),
  (204, 46, 'Fermentasi', NULL, 0, NULL, NULL),
  (205, 47, 'Lemak hewani', NULL, 0, NULL, NULL),
  (206, 47, 'Lemak nabati', NULL, 1, NULL, NULL),
  (207, 47, 'Perasa mentega', NULL, 0, NULL, NULL),
  (208, 47, 'Susu', NULL, 0, NULL, NULL),
  (209, 48, 'Berasa manis', NULL, 0, NULL, NULL),
  (210, 48, 'Menahan air', NULL, 1, NULL, NULL),
  (211, 48, 'Menahan lemak', NULL, 0, NULL, NULL),
  (212, 48, 'Menahan protein', NULL, 0, NULL, NULL),
  (
    213,
    49,
    'Bintik hitam pada kulit roti dan berlubang',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    214,
    49,
    'Proses fermentasi lama',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    215,
    49,
    'Roti jadi tidak mengembang',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    216,
    49,
    'Roti jadi terlalu manis',
    NULL,
    0,
    NULL,
    NULL
  ),
  (217, 50, 'Shortening', NULL, 0, NULL, NULL),
  (218, 50, 'Mentega putih', NULL, 0, NULL, NULL),
  (219, 50, 'Korsvet', NULL, 1, NULL, NULL),
  (220, 50, 'Mentega cair', NULL, 0, NULL, NULL),
  (
    221,
    51,
    'Mengistirahatkan adonan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    222,
    51,
    'Mengembangkan adonan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    223,
    51,
    'Menghilangkan gas pada adonan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    224,
    51,
    'Membuat adonan licin',
    NULL,
    0,
    NULL,
    NULL
  ),
  (225, 52, '20ºC', NULL, 0, NULL, NULL),
  (226, 52, '30ºC', NULL, 0, NULL, NULL),
  (227, 52, '35ºC', NULL, 1, NULL, NULL),
  (228, 52, '50ºC', NULL, 0, NULL, NULL),
  (229, 53, '1 jam', NULL, 0, NULL, NULL),
  (230, 53, '35 menit', NULL, 1, NULL, NULL),
  (231, 53, '2 jam', NULL, 0, NULL, NULL),
  (232, 53, '1,5 jam', NULL, 0, NULL, NULL),
  (233, 54, '200 – 220 ºC', NULL, 1, NULL, NULL),
  (234, 54, '180 – 190 ºC', NULL, 0, NULL, NULL),
  (235, 54, '160 – 170 ºC', NULL, 0, NULL, NULL),
  (236, 54, '140 – 150 ºC', NULL, 0, NULL, NULL),
  (
    237,
    55,
    'Supaya mudah dilepas',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    238,
    55,
    'Mengakhiri proses pemasakan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    239,
    55,
    'Supaya warna nya tidak berubah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    240,
    55,
    'Supaya bentuknya utuh',
    NULL,
    0,
    NULL,
    NULL
  ),
  (241, 56, 'Choux paste', NULL, 0, NULL, NULL),
  (242, 56, 'Puff pastry', NULL, 0, NULL, NULL),
  (243, 56, 'Short paste', NULL, 0, NULL, NULL),
  (244, 56, 'Roti tawar', NULL, 1, NULL, NULL),
  (245, 57, '200 – 220 ºC', NULL, 1, NULL, NULL),
  (246, 57, '180 – 190 ºC', NULL, 0, NULL, NULL),
  (247, 57, '160 – 170 ºC', NULL, 0, NULL, NULL),
  (248, 57, '140 – 150 ºC', NULL, 0, NULL, NULL),
  (249, 58, 'Harus mengembang', NULL, 0, NULL, NULL),
  (250, 58, 'Harus renyah', NULL, 0, NULL, NULL),
  (251, 58, 'Crumb yg lembut', NULL, 0, NULL, NULL),
  (252, 58, 'Aroma nya segar', NULL, 1, NULL, NULL),
  (253, 59, '30 menit', NULL, 1, NULL, NULL),
  (254, 59, '20 menit', NULL, 0, NULL, NULL),
  (255, 59, '10 menit', NULL, 0, NULL, NULL),
  (256, 59, '5 menit', NULL, 0, NULL, NULL),
  (
    257,
    60,
    'Pastry yang beragi dan berasa manis',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    258,
    60,
    'Pastry yang renyah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    259,
    60,
    'Pastry yang menggunakan susu',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    260,
    60,
    'Pastry yang digiling',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    261,
    61,
    'Berpakaian mewah seperti pakaian pesta',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    262,
    61,
    'Mempunyai modal besar untuk usaha menjahit',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    263,
    61,
    'Memiliki boutique sendiri dengan mewah',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    264,
    61,
    'Memilihkan model yang sesuai untuk pelanggannya',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    265,
    62,
    'Menguatkan warnanya',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    266,
    62,
    'Mengetahui penyusutan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    267,
    62,
    'Mengetahui mutu warnanya',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    268,
    62,
    'Menghilangkan kanjinya',
    NULL,
    0,
    NULL,
    NULL
  ),
  (269, 63, 'Mumu', NULL, 0, NULL, NULL),
  (270, 63, 'Strapless', NULL, 0, NULL, NULL),
  (271, 63, 'Backless', NULL, 1, NULL, NULL),
  (272, 63, 'Delix plece', NULL, 0, NULL, NULL),
  (273, 64, 'Pendek', NULL, 0, NULL, NULL),
  (274, 64, 'Tinggi', NULL, 0, NULL, NULL),
  (275, 64, 'Gemuk', NULL, 0, NULL, NULL),
  (276, 64, 'Langsing', NULL, 1, NULL, NULL),
  (277, 65, 'Standar', NULL, 1, NULL, NULL),
  (278, 65, 'Dasar', NULL, 0, NULL, NULL),
  (279, 65, 'Ukuran perorangan', NULL, 0, NULL, NULL),
  (280, 65, 'Drapping', NULL, 0, NULL, NULL),
  (
    281,
    66,
    'Jatuhnya rok bagus',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    282,
    66,
    'Pengerjaannya cepat',
    NULL,
    0,
    NULL,
    NULL
  ),
  (283, 66, 'Kelimnya rapih', NULL, 0, NULL, NULL),
  (
    284,
    66,
    'Lebar kelim merata',
    NULL,
    0,
    NULL,
    NULL
  ),
  (285, 67, 'Tegak', NULL, 0, NULL, NULL),
  (286, 67, 'Rebah', NULL, 1, NULL, NULL),
  (287, 67, 'Setali', NULL, 0, NULL, NULL),
  (288, 67, 'Kemeja', NULL, 0, NULL, NULL),
  (289, 68, 'Lembut', NULL, 1, NULL, NULL),
  (290, 68, 'Kaku', NULL, 0, NULL, NULL),
  (291, 68, 'Lentur', NULL, 0, NULL, NULL),
  (292, 68, 'Tipis', NULL, 0, NULL, NULL),
  (
    293,
    69,
    'Untuk merubah model',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    294,
    69,
    'Mempercepat cara menggunting',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    295,
    69,
    'Dapat menentukan ukuran bahan',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    296,
    69,
    'Mempersingkat waktu',
    NULL,
    0,
    NULL,
    NULL
  ),
  (297, 70, 'Kontruksi', NULL, 0, NULL, NULL),
  (298, 70, 'Standar', NULL, 0, NULL, NULL),
  (299, 70, 'Dasar', NULL, 1, NULL, NULL),
  (300, 70, 'Drapping', NULL, 0, NULL, NULL),
  (
    301,
    71,
    'Merah, kuning dan biru',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    302,
    71,
    'Biru, merah dan ungu',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    303,
    71,
    'Merah, kuning dan hitam',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    304,
    71,
    'Kuning, biru dan putih',
    NULL,
    0,
    NULL,
    NULL
  ),
  (305, 72, 'Etika berbusana', NULL, 1, NULL, NULL),
  (306, 72, 'Pelengkap busana', NULL, 0, NULL, NULL),
  (307, 72, 'Aksesoris busana', NULL, 0, NULL, NULL),
  (
    308,
    72,
    'Berbusana mengikuti model',
    NULL,
    0,
    NULL,
    NULL
  ),
  (309, 73, 'Yang paling kecil', NULL, 0, NULL, NULL),
  (310, 73, 'Pola lengan', NULL, 0, NULL, NULL),
  (311, 73, 'Potongan-potongan', NULL, 0, NULL, NULL),
  (312, 73, 'Yang paling besar', NULL, 1, NULL, NULL),
  (313, 74, 'Rok lipit hadap', NULL, 0, NULL, NULL),
  (314, 74, 'Rok lipit sungkup', NULL, 0, NULL, NULL),
  (315, 74, 'Rok lipit kipas', NULL, 1, NULL, NULL),
  (316, 74, 'Rok lipit pipih', NULL, 0, NULL, NULL),
  (317, 75, 'Krah shiller', NULL, 1, NULL, NULL),
  (318, 75, 'Krah shanghai', NULL, 0, NULL, NULL),
  (319, 75, 'Krah kemeja', NULL, 0, NULL, NULL),
  (320, 75, 'Tailor', NULL, 0, NULL, NULL),
  (
    321,
    76,
    'Bahan yang akan dipergunakan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    322,
    76,
    'Ukuran menurut model',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    323,
    76,
    'Kertas yang akan dipergunakan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    324,
    76,
    'Bentuk tubuh model',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    325,
    77,
    'Mengecilkan pinggang',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    326,
    77,
    'Mendapatkan ukuran yang tepat',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    327,
    77,
    'Mengetahui letak pinggang',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    328,
    77,
    'Menandai lingkar pinggang',
    NULL,
    0,
    NULL,
    NULL
  ),
  (329, 78, 'Tali pinggang', NULL, 0, NULL, NULL),
  (330, 78, 'Kerah', NULL, 0, NULL, NULL),
  (331, 78, 'Merapikan kampuh', NULL, 1, NULL, NULL),
  (332, 78, 'Pita', NULL, 0, NULL, NULL),
  (
    333,
    79,
    'Tata krama dalam hubungan kerja',
    NULL,
    1,
    NULL,
    NULL
  ),
  (
    334,
    79,
    'Jaminan untuk kedudukan social yang layak',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    335,
    79,
    'Perlindungan bagi keyamanan kerja',
    NULL,
    0,
    NULL,
    NULL
  ),
  (
    336,
    79,
    'Peraturan yang menjamin kesehatan',
    NULL,
    0,
    NULL,
    NULL
  ),
  (337, 80, 'Pelayan prima', NULL, 1, NULL, NULL),
  (338, 80, 'Promosi usaha', NULL, 0, NULL, NULL),
  (339, 80, 'Kerja kelompok', NULL, 0, NULL, NULL),
  (340, 80, 'Bekerja sendiri', NULL, 0, NULL, NULL),
  (341, 81, 'Tidak Memuaskan', NULL, 0, NULL, NULL),
  (342, 81, 'Kurang Memuaskan', NULL, 0, NULL, NULL),
  (343, 81, 'Memuaskan', NULL, 0, NULL, NULL),
  (344, 81, 'Sangat Memuaskan', NULL, 0, NULL, NULL),
  (345, 98, 'Tidak Bermanfaat', NULL, 0, NULL, NULL),
  (346, 98, 'Kurang Bermanfaat', NULL, 0, NULL, NULL),
  (347, 98, 'Bermanfaat', NULL, 0, NULL, NULL),
  (348, 98, 'Sangat Bermanfaat', NULL, 0, NULL, NULL),
  (349, 103, '25%', NULL, 0, NULL, NULL),
  (350, 103, '50%', NULL, 0, NULL, NULL),
  (351, 103, '75%', NULL, 0, NULL, NULL),
  (352, 103, '100%', NULL, 0, NULL, NULL),
  (353, 104, 'Tidak Perlu', NULL, 0, NULL, NULL),
  (354, 104, 'Kurang Perlu', NULL, 0, NULL, NULL),
  (355, 104, 'Perlu', NULL, 0, NULL, NULL),
  (356, 104, 'Sangat Perlu', NULL, 0, NULL, NULL),
  (357, 106, 'Tidak Mendukung', NULL, 0, NULL, NULL),
  (358, 106, 'Kurang Mendukung', NULL, 0, NULL, NULL),
  (359, 106, 'Mendukung', NULL, 0, NULL, NULL),
  (360, 106, 'Sangat Mendukung', NULL, 0, NULL, NULL),
  (361, 109, 'Tidak Disiplin', NULL, 0, NULL, NULL),
  (362, 109, 'Kurang Disiplin', NULL, 0, NULL, NULL),
  (363, 109, 'Disiplin', NULL, 0, NULL, NULL),
  (364, 109, 'Sangat Disiplin', NULL, 0, NULL, NULL),
  (365, 112, 'Tidak rapi', NULL, 0, NULL, NULL),
  (366, 112, 'Kurang Rapi', NULL, 0, NULL, NULL),
  (367, 112, 'Rapi', NULL, 0, NULL, NULL),
  (368, 112, 'Sangat Rapi', NULL, 0, NULL, NULL),
  (369, 113, 'Tidak baik', NULL, 0, NULL, NULL),
  (370, 113, 'Kurang baik', NULL, 0, NULL, NULL),
  (371, 113, 'baik', NULL, 0, NULL, NULL),
  (372, 113, 'Sangat baik', NULL, 0, NULL, NULL);


INSERT INTO
  `pivot_jawaban` (`pertanyaan_id`, `template_pertanyaan_id`)
VALUES
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
  (105, 104);


-- tabel peserta survei

-- tabel peserta_survei
INSERT INTO
  `peserta_surveis` (
    `id`,
    `nama`,
    `email`,
    `angkatan`,
    `bidang_id`,
    `pelatihan_id`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    3,
    'Ryndi Mega Herawati',
    'rindi121299@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 07:40:28',
    '2025-08-29 07:40:28'
  ),
  (
    4,
    'SABRINA WAFA AQILLAH',
    's6951820@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 07:41:11',
    '2025-08-29 07:41:11'
  ),
  (
    5,
    'AMELTA AULIA SUDARKO',
    'meltacialia@gmail.com',
    'Angkatan 2',
    2,
    1,
    '2025-08-29 07:42:33',
    '2025-08-29 07:42:33'
  ),
  (
    6,
    'Arum Mawarni Dwi Puspita',
    'arummawarni6pbm@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 07:42:47',
    '2025-08-29 07:42:47'
  ),
  (
    7,
    'xtwolita Elfreda ardiningrum',
    'xtwolitae@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 07:43:00',
    '2025-08-29 07:43:00'
  ),
  (
    8,
    'Masyifa Azzahra',
    'masyifaazzahra23@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 07:46:13',
    '2025-08-29 07:46:13'
  ),
  (
    9,
    'Silvia Ramadani',
    'silviaramadani665@gmail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 07:46:24',
    '2025-08-29 07:46:24'
  ),
  (
    10,
    'ANGGREA REVALDA PRATIWI PUTRI',
    'revaldaanggrea@gmail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 07:46:50',
    '2025-08-29 07:46:50'
  ),
  (
    11,
    'Salsabila nur rochim',
    'salsabila33pbmnew@gamail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 07:50:36',
    '2025-08-29 07:50:36'
  ),
  (
    12,
    'DEWI SAFIRA FEBRIANA',
    'savirapinga5@gmail.com',
    'Angkatan ||',
    2,
    1,
    '2025-08-29 07:57:45',
    '2025-08-29 07:57:45'
  ),
  (
    13,
    'rizki fajar aditya putra',
    'rizkifajaraditiyaputra@gmail.com',
    'angkatan 2',
    2,
    1,
    '2025-08-29 08:10:01',
    '2025-08-29 22:52:51'
  ),
  (
    14,
    'ANNA ALTHAFUNNISA',
    'annaalthafunnisa467@gmail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 08:11:42',
    '2025-08-29 08:11:42'
  ),
  (
    15,
    'Prajwalita Zulfa fatika Chusna',
    'prajwalitacusna@gmail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 08:17:35',
    '2025-08-29 08:17:35'
  ),
  (
    16,
    'ALEXCA EVELINA AVRILLA PUTRI',
    'acaalexca@gmail.com',
    'Angkatan II',
    2,
    1,
    '2025-08-29 08:27:34',
    '2025-08-29 08:27:34'
  ),
  (
    17,
    'MUHAMAD RIKI FEBRIANTO',
    'muhamadriki9206@smk.blajar.id',
    '2',
    4,
    1,
    '2025-08-29 08:39:04',
    '2025-08-29 08:39:04'
  ),
  (
    18,
    'MUCHAMMAD ASKAEV ANSORI',
    'www4ab@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:10',
    '2025-08-29 08:39:10'
  ),
  (
    19,
    'ZEFRIZAL RAHMADANI',
    'makmupikun@gmain.com',
    'Angkatan ll',
    4,
    1,
    '2025-08-29 08:39:16',
    '2025-08-29 08:39:16'
  ),
  (
    20,
    'AHMAD KHOIRUDDIN',
    'ahmadkhoiruddin2025@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:20',
    '2025-08-29 08:39:20'
  ),
  (
    21,
    'SYAIFUL BAHRI',
    'bahri123baik@gmail.com',
    'Angkatan ||',
    4,
    1,
    '2025-08-29 08:39:21',
    '2025-08-29 08:39:21'
  ),
  (
    22,
    'MOCH ADAM ASSADDIL RAIZ',
    'adamassaddil@gmail.com',
    'Angkatan 11',
    4,
    1,
    '2025-08-29 08:39:25',
    '2025-08-29 08:39:25'
  ),
  (
    23,
    'RADITHYA FAUSTA ARIEF',
    'faustaatifradithya@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:27',
    '2025-08-29 08:39:27'
  ),
  (
    24,
    'RANGGA EKA PUTRA ELYANTO',
    'ranggaproject63@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:38',
    '2025-08-29 08:39:38'
  ),
  (
    25,
    'MUHAMMAD NAZAR QOIRUN NAZMI',
    'mnazar.qn18@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:44',
    '2025-08-29 08:39:44'
  ),
  (
    26,
    'ARDHIANSYAH PUTRA RISQI SATRIAGUNG',
    'ardhiansyahsatriagung@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:39:46',
    '2025-08-29 08:39:46'
  ),
  (
    27,
    'SEPNI AMZAH',
    'sepniamzah5@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:40:00',
    '2025-08-29 08:40:00'
  ),
  (
    28,
    'marisa ulfi nur\'aini',
    'marisaulfinuraini@gmail.com',
    'angkatan ll',
    1,
    1,
    '2025-08-29 08:40:04',
    '2025-08-29 08:40:04'
  ),
  (
    29,
    'MUHAMMAD ALVIN ABIANSYAH',
    'alvinabiansah0@gmail.com',
    'Angkatan ||',
    4,
    1,
    '2025-08-29 08:40:05',
    '2025-08-29 08:40:05'
  ),
  (
    30,
    'ESA NUR WAHYUDI',
    'esanurwahyudisoto@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:40:08',
    '2025-08-29 08:40:08'
  ),
  (
    31,
    'MUHAMMAD BISRI ABDILLAH',
    'abdillahmuhammadbisri@gmail.com',
    'Angkatan II',
    4,
    1,
    '2025-08-29 08:40:30',
    '2025-09-01 04:41:46'
  ),
  (
    32,
    'OKY RIYANTO',
    'okyriyanto369@gmail.com',
    'ANGKAT II',
    4,
    1,
    '2025-08-29 08:42:09',
    '2025-08-29 08:42:09'
  ),
  (
    33,
    'Andi Dixie Aurellia Neysa',
    'dixieaurellianey@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 08:55:12',
    '2025-08-29 08:55:12'
  ),
  (
    34,
    'rahma pujianti',
    'rahmapuji288@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 08:56:23',
    '2025-09-01 04:26:09'
  ),
  (
    35,
    'Naysilla Novi Nur Khumaira',
    'naysillanovi11@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:01:35',
    '2025-08-29 09:01:35'
  ),
  (
    36,
    'Brillian Dwi Rahmadani',
    'rahamadani52@gmail.com',
    'Angkatan ll',
    1,
    1,
    '2025-08-29 09:03:49',
    '2025-08-29 09:03:49'
  ),
  (
    37,
    'Binti Lailatul Ilmi',
    'bintilailatulilmi@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:07:02',
    '2025-08-29 09:07:02'
  ),
  (
    38,
    'MERCY ALVINA PUTRI SILOLO',
    'mercyalvinaa@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:11:56',
    '2025-08-29 09:11:56'
  ),
  (
    39,
    'Najwa Azzahra Ramadhani',
    'najwazzahra126@gmail.com',
    'Angkatan ll',
    1,
    1,
    '2025-08-29 09:12:43',
    '2025-08-29 09:12:43'
  ),
  (
    40,
    'RAHMADINA NURAINI',
    'rahmadinanuraini7@gmail.com',
    'Angkatan ll',
    1,
    1,
    '2025-08-29 09:13:19',
    '2025-08-29 09:13:19'
  ),
  (
    41,
    'zyika ayudia mutia sari',
    'zyikaayu@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:13:44',
    '2025-08-29 09:13:44'
  ),
  (
    42,
    'callula salwa zakiah',
    'calulasalwa20@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:14:20',
    '2025-08-29 09:14:20'
  ),
  (
    43,
    'PURi BONDAN NINGTYAS',
    'puriningtyas@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:14:40',
    '2025-08-29 09:14:40'
  ),
  (
    44,
    'Diah Wahyu Sofana',
    'diahsofana752@gmail.com',
    'Angkatan ll',
    1,
    1,
    '2025-08-29 09:18:25',
    '2025-08-29 09:18:25'
  ),
  (
    45,
    'RALVIO OCZA ZEBIAN',
    'ralvioocza30@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:19:54',
    '2025-08-29 09:19:54'
  ),
  (
    46,
    'YOHANA DIAN MAHARANI',
    'hanayana302@gmail.com',
    'Angkatan 2',
    1,
    1,
    '2025-08-29 09:20:04',
    '2025-08-29 09:20:04'
  ),
  (
    47,
    'Diaz ayunda kirana',
    'diazayundakirana@gmail.com',
    'Angkatan II',
    1,
    1,
    '2025-08-29 09:21:34',
    '2025-08-29 09:21:34'
  ),
  (
    48,
    'HILMAN PUTRA AL RIZKI',
    'hlmnsii856@gmail.com',
    'ANGKATAN II',
    1,
    1,
    '2025-08-29 09:21:50',
    '2025-08-29 09:21:50'
  ),
  (
    49,
    'LUTFI HIDAYA TURROHIM',
    'lutfiturrohim@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 09:53:59',
    '2025-08-29 09:53:59'
  ),
  (
    50,
    'Margareta Pasharina Swastika',
    'swastikamargareta3@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 09:57:40',
    '2025-08-29 09:57:40'
  ),
  (
    51,
    'HALIMATUS PUTRI DEWI',
    'xtydwe@gmail.com',
    'Angkatan ll',
    2,
    1,
    '2025-08-29 10:14:53',
    '2025-08-29 10:14:53'
  ),
  (
    52,
    'Nayla Qolbina Muttaqiyah',
    'naylataqiyyah30@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 10:36:39',
    '2025-08-29 10:36:39'
  ),
  (
    53,
    'Firdaussy Nurliza Ramadhani',
    'firdanurliza9@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-08-29 10:38:29',
    '2025-08-29 10:38:29'
  ),
  (
    54,
    'dayinta suryaning sekar dhatu',
    'dayintadhatu@gmail.com',
    'angkatan II',
    3,
    1,
    '2025-08-29 22:20:45',
    '2025-08-29 22:20:45'
  ),
  (
    55,
    'zahra farrisa gitya putri',
    'zahrafarrisaputri@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-09-01 04:24:55',
    '2025-09-01 04:24:55'
  ),
  (
    56,
    'Aisyah Nadya Fitri',
    'duoaisyah124@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-09-01 04:25:11',
    '2025-09-01 04:25:11'
  ),
  (
    57,
    'NAYZILA NISRINA SALSABILA',
    'naysilaa1210@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-09-01 04:38:45',
    '2025-09-01 04:38:45'
  ),
  (
    58,
    'Naila Aurellia',
    'nailaay123@gmail.com',
    'Angkatan II',
    3,
    1,
    '2025-09-01 05:16:54',
    '2025-09-01 05:16:54'
  );



-- percobaan
INSERT INTO
  `percobaans` (
    `id`,
    `pesertaSurvei_id`,
    `tes_id`,
    `waktu_mulai`,
    `waktu_selesai`,
    `skor`,
    `lulus`,
    `pesan_kesan`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    2,
    3,
    5,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17',
    NULL,
    0,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    3,
    4,
    5,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23',
    NULL,
    0,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    4,
    6,
    5,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30',
    NULL,
    0,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    5,
    5,
    5,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30',
    NULL,
    0,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    6,
    7,
    5,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53',
    NULL,
    0,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    7,
    10,
    5,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08',
    NULL,
    0,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    8,
    11,
    5,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49',
    NULL,
    0,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    9,
    12,
    5,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29',
    NULL,
    0,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    10,
    8,
    5,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46',
    NULL,
    0,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    11,
    15,
    5,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58',
    NULL,
    0,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    12,
    14,
    5,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46',
    NULL,
    0,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    13,
    16,
    5,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23',
    NULL,
    0,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    14,
    20,
    5,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03',
    NULL,
    0,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    15,
    17,
    5,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26',
    NULL,
    0,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    16,
    18,
    5,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48',
    NULL,
    0,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    17,
    28,
    5,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10',
    NULL,
    0,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    18,
    23,
    5,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26',
    NULL,
    0,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    19,
    19,
    5,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32',
    NULL,
    0,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    20,
    21,
    5,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41',
    NULL,
    0,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    21,
    25,
    5,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15',
    NULL,
    0,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    22,
    29,
    5,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31',
    NULL,
    0,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    23,
    22,
    5,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45',
    NULL,
    0,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    24,
    30,
    5,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07',
    NULL,
    0,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    25,
    31,
    5,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08',
    NULL,
    0,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    26,
    24,
    5,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53',
    NULL,
    0,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    27,
    32,
    5,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54',
    NULL,
    0,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    28,
    26,
    5,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11',
    NULL,
    0,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    29,
    27,
    5,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19',
    NULL,
    0,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    30,
    27,
    5,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21',
    NULL,
    0,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    31,
    27,
    5,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31',
    NULL,
    0,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    32,
    27,
    5,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05',
    NULL,
    0,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    33,
    35,
    5,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03',
    NULL,
    0,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    34,
    37,
    5,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20',
    NULL,
    0,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    35,
    36,
    5,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44',
    NULL,
    0,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    36,
    42,
    5,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12',
    NULL,
    0,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    37,
    39,
    5,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33',
    NULL,
    0,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    38,
    38,
    5,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50',
    NULL,
    0,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    39,
    44,
    5,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01',
    NULL,
    0,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    40,
    40,
    5,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40',
    NULL,
    0,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    41,
    43,
    5,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15',
    NULL,
    0,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    42,
    45,
    5,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39',
    NULL,
    0,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    43,
    47,
    5,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34',
    NULL,
    0,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    44,
    46,
    5,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39',
    NULL,
    0,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    45,
    41,
    5,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54',
    NULL,
    0,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    46,
    48,
    5,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52',
    NULL,
    0,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    47,
    49,
    5,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46',
    NULL,
    0,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    48,
    50,
    5,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00',
    NULL,
    0,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    49,
    51,
    5,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28',
    NULL,
    0,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    50,
    52,
    5,
    '2025-08-29 10:43:29',
    '2025-08-29 10:43:29',
    NULL,
    0,
    NULL,
    '2025-08-29 10:43:29',
    '2025-08-29 10:43:29'
  ),
  (
    51,
    53,
    5,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42',
    NULL,
    0,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    52,
    9,
    5,
    '2025-08-29 17:29:34',
    '2025-08-29 17:29:34',
    NULL,
    0,
    NULL,
    '2025-08-29 17:29:34',
    '2025-08-29 17:29:34'
  ),
  (
    53,
    54,
    5,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02',
    NULL,
    0,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    54,
    13,
    5,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49',
    NULL,
    0,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    55,
    51,
    5,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36',
    NULL,
    0,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    56,
    12,
    5,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47',
    NULL,
    0,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    57,
    55,
    5,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08',
    NULL,
    0,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    58,
    55,
    5,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12',
    NULL,
    0,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    59,
    56,
    5,
    '2025-09-01 04:31:49',
    '2025-09-01 04:31:49',
    NULL,
    0,
    NULL,
    '2025-09-01 04:31:49',
    '2025-09-01 04:31:49'
  ),
  (
    60,
    41,
    5,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30',
    NULL,
    0,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    61,
    57,
    5,
    '2025-09-01 04:48:54',
    '2025-09-01 04:48:54',
    NULL,
    0,
    NULL,
    '2025-09-01 04:48:54',
    '2025-09-01 04:48:54'
  ),
  (
    62,
    57,
    5,
    '2025-09-01 04:48:54',
    '2025-09-01 04:48:54',
    NULL,
    0,
    NULL,
    '2025-09-01 04:48:54',
    '2025-09-01 04:48:54'
  ),
  (
    63,
    31,
    5,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35',
    NULL,
    0,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    64,
    29,
    5,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53',
    NULL,
    0,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  );


-- tabel user

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Sepni Amzah', 'sepniamzah5@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(3, 'Firdaussy Nurliza Ramadhani', 'firdanurliza9@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(4, 'MOCH ADAM ASSADDIL RAIZ', 'adamassaddil@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(5, 'NAYSILLA NOVI NUR KHUMAIRA', 'naysillanovi11@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(6, 'OKY RIYANTO', 'okyriyanto369@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(7, 'Prajwalita Zulfa Fatika Chusna', 'prajwalitacusna@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(8, 'RALVIO OCZA ZEBIAN', 'ralvioocza30@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(9, 'RYNDI MEGA HERAWATI', 'rindi121299@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(10, 'RANGGA EKA PUTRA ELYANTO', 'ranggaproject63@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(11, 'Dewi Safira Febriana', 'dewisafira973@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(12, 'Amelta Aulia Subarko', 'meltacialia@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(13, 'LUTFI HIDAYATUR ROHIM', 'lutfiturrohim@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(14, 'RADITHYA FAUSTA ARIEF', 'faustaatifradithya@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(15, 'Alifia Ziqna Faradiba', 'alifiafaradiba043@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(16, 'Muhammad Nazar Qoirun Nazmi', 'mnazar.qn18@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(17, 'HALIMATUS PUTRI DEWI', 'nurulalaina86@guru.smk.belajar.id', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(18, 'Naila Aurellia', 'nailaay123@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(19, 'Masyifa Azzahra', 'masyifaazzahra23@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(20, 'SILVIA RAMADANI', 'silviaramadani665@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(21, 'ARDHIANSYAH PUTRA RISQI SATRIAGUNG', 'ardhiansyahsatriagung@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(22, 'XTWOLITA ELFREDA ARDININGRUM', 'xtwolitae@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(23, 'Ahmad Khoiruddin', 'ahmadkhoiruddin2025@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(24, 'Zahra Farrisa Gitya Putri', 'zahrafarrisaputri@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(25, 'SABRINA WAFA AQILLAH', 's6951820@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(26, 'Anggrea Revalda Pratiwi Putri', 'revaldaanggrea@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(27, 'MUHAMAD RIKI FEBRIANTO', 'muhamadriki9206@smk.blajar.id', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(28, 'Binti Lailatul Ilmi', 'bintilailatulilmi@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(29, 'ZYIKA AYUDIA MUTIA SARI', 'huddyiswanto@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(30, 'YOHANA DIAN MAHARANI', 'hanayana302@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(31, 'Diaz Ayunda Kirana', 'diazayundakirana@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(32, 'Margareta Pasharina Swastika', 'swastikamargareta3@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(33, 'Nayla Qolbina Muttaqiyah', 'naylataqiyyah30@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(34, 'Arum Mawarni Dwi Puspita', 'arummawarni6pbm@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(35, 'DIAH WAHYU SOFANA', 'diahsofana752@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(36, 'Salsabila Nur rochim', 'salsabila33pbmnew@gamail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(37, 'Marisa Ulfi Nur\'aini', 'marisaulfinuraini@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(38, 'AISYAH NADYA FITRI', 'duoaisyah124@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(39, 'NAYZILA NISRINA SALSABILA', 'naysilaa1210@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(40, 'CALLULA SALWA ZAKIAH', 'calulasalwa20@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(41, 'NAJWA AZZAHRA RAMADHANI', 'najwazzahra126@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(42, 'MUHAMMAD ALVIN ABIANSYAH', 'muhammad.alvin6412@smk.belajar.id', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(43, 'Rahmadina Nuraini', 'rahmadinanuraini7@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(44, 'HILMAN PUTRA AL RIZKI', 'hlmnsii856@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(45, 'Muhammad Bisri Abdillah', 'muhammadbisriabdillah6@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(46, 'rizki fajar aditya putra', 'rizkifajaraditiyaputra@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(47, 'Alexca Evelina Avrilla Putri', 'acaalexca@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(48, 'Brillian Dwi Rahmadani', 'rahamadani52@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(49, 'Puri Bondan Ningtyas', 'onyourmarkiam@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(50, 'SAIFUL BAHRI', 'bahri123baik@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(51, 'MUCHAMMAD ASKAEV ANSORI', 'www4ab@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(52, 'Baitha Hayyunisa', 'baitha.hayyunisa@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(53, 'Andi Dixie Aurellia Neysa', 'dixieaurellianey@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(54, 'Rahma pujianti', 'rahmapuji288@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(55, 'DAYINTA SURYANING SEKAR DHATU', 'dayintadhtu@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(56, 'CRISSANDA REBECCA ANGELA CHILL', 'rbcsaa@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(57, 'ANNA ALTHAFUNNISA', 'annaalthafunnisa467@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(58, 'ESA NUR WAHYUDI', 'esanurwahyudisoto@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(59, 'ZEFRIZAL RAHMADANI', 'makmupikun@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(60, 'MERCY ALVINA PUTRI SILOLO', 'mercyalvinaa@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW()),
(61, 'Firda Aulia Zahra', 'firdaaulya@gmail.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());


-- tabel peserta
-- PERHATIAN:
-- 1. Kolom 'user_id' telah ditambahkan dengan nilai placeholder '1'.
--    Pastikan Anda mengganti nilai '1' ini dengan ID yang ada di tabel 'users' Anda.
-- 2. Kolom 'token' telah ditambahkan dengan nilai unik yang dibuat secara acak.
-- 3. Kolom 'email' dan nilainya telah dihapus agar sesuai dengan skema tabel.

INSERT INTO `pesertas` (
    `id`, `pelatihan_id`, `bidang_id`, `instansi_id`, `user_id`,
    `nama`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`,
    `agama`, `alamat`, `no_hp`, `created_at`, `updated_at`
) VALUES
(1, 1, 4, 3, 2, 'Sepni Amzah', '3574041406080003', 'Probolinggo', '2008-06-14', 'Laki-laki', 'Islam', 'Jalan Slamet Riyadi S Rokan Blok Klompang N0 42', '082331020465', '2025-08-20 09:28:17', '2025-08-20 09:28:17'),
(2, 1, 3, 4, 3, 'Firdaussy Nurliza Ramadhani', '3513056109070001', 'Probolinggo', '2007-09-21', 'Perempuan', 'Islam', 'Jl. Pahlawan 1, RT 004/RW 005, Sumberkedawung, Leces, Probolinggo', '085815776656', '2025-08-20 10:06:36', '2025-08-20 10:06:36'),
(3, 1, 4, 5, 4, 'MOCH ADAM ASSADDIL RAIZ', '3517062106090001', 'JOMBANG', '2009-06-21', 'Laki-laki', 'Islam', 'JAMBUWOK, DUSUN JAMBUWOK\r\nRT 07 RW 02', '085755193621', '2025-08-20 11:23:32', '2025-08-20 11:23:32'),
(4, 1, 1, 6, 5, 'NAYSILLA NOVI NUR KHUMAIRA', '3506114111080002', 'KEDIRI', '2008-11-01', 'Perempuan', 'Islam', 'Desa Tengger Kidul RT.04. RW.03 Kec. Pagu Kab. Kediri', '082143214002', '2025-08-20 11:58:13', '2025-08-20 11:58:13'),
(5, 1, 4, 7, 6, 'OKY RIYANTO', '3523152210080002', 'TUBAN', '2008-10-22', 'Laki-laki', 'Islam', 'Lingkungan Dondong Rt.03 Rw.09 Kelurahan Gedongombo Kecamatan Semanding Kabupaten Tuban', '628899179062', '2025-08-20 12:13:59', '2025-08-20 12:13:59'),
(6, 1, 2, 8, 7, 'Prajwalita Zulfa Fatika Chusna', '3505034903080001', 'BLITAR', '2008-03-09', 'Perempuan', 'Islam', 'Dsn Karanggayam\r\nKec Srengat\r\nKabupaten Blitar', '081252283167', '2025-08-20 12:19:16', '2025-08-20 12:19:16'),
(7, 1, 1, 9, 8, 'RALVIO OCZA ZEBIAN', '3507291207080001', 'Malang', '2008-08-12', 'Laki-laki', 'Islam', 'JJln. Raya Desa Segaran Rt.07 Rw.03 kec. Gedangan Kab. Malang', '082142773469', '2025-08-20 12:25:07', '2025-08-20 12:25:07'),
(8, 1, 2, 10, 9, 'RYNDI MEGA HERAWATI', '3524166002090001', 'Lamongan', '2009-02-20', 'Perempuan', 'Islam', 'Dusun Maijo, Desa Kedungsoko, Kecamatan Mantup, Kabupaten Lamongan', '085704309810', '2025-08-20 12:30:28', '2025-08-20 12:30:28'),
(9, 1, 4, 13, 10, 'RANGGA EKA PUTRA ELYANTO', '3516032105080001', 'Mojokerto', '2008-05-21', 'Laki-laki', 'Islam', 'Dsn. Kandangan Ds. kuripansari Kec. pacet', '085749308942', '2025-08-20 13:04:04', '2025-08-20 13:04:04'),
(10, 1, 2, 14, 11, 'Dewi Safira Febriana', '3514114502090001', 'Pasuruan', '2009-02-05', 'Perempuan', 'Islam', 'satak kepoh RT.007 / RW.003 - Manaruwi - Bangil', '085850100186', '2025-08-20 13:10:17', '2025-08-20 13:10:17'),
(11, 1, 2, 15, 12, 'Amelta Aulia Subarko', '3518074107070003', 'Nganjuk', '2029-03-09', 'Perempuan', 'Islam', 'Dsn. Sumberkepuh, Ds. Klurahan, Kec. Ngronggot, Kab. Nganjuk', '081805792203', '2025-08-20 13:57:25', '2025-08-20 13:57:25'),
(12, 1, 3, 16, 13, 'LUTFI HIDAYATUR ROHIM', '0000000000000000', 'Nganjuk', '2008-04-18', 'Perempuan', 'Islam', 'Ds. Jatirejo, Kec. Nganjuk, Kab. Nganjuk', '085707361280', '2025-08-20 14:09:41', '2025-08-20 14:09:41'),
(13, 1, 4, 17, 14, 'RADITHYA FAUSTA ARIEF', '3578191911080001', 'Surabaya', '2008-11-19', 'Laki-laki', 'Islam', 'SEMEMIJAYA GG 5 C BLOK 1 NO 4\r\nRT2, RW1', '085704018360', '2025-08-20 14:12:20', '2025-08-20 14:12:20'),
(14, 1, 3, 18, 15, 'Alifia Ziqna Faradiba', '3511174411080001', 'Bondowoso', '2008-11-04', 'Perempuan', 'Islam', 'Jl. Diponegoro no 214, Kotakulon Bondowoso Jawa timur', '083817525825', '2025-08-20 14:28:22', '2025-08-20 14:28:22'),
(15, 1, 4, 19, 16, 'Muhammad Nazar Qoirun Nazmi', '3506151812080001', 'Kediri', '2008-12-18', 'Laki-laki', 'Islam', 'RT/RW 1/1 Dsn. Jantok Ds. Jantok, Kec. Purwoasri, Kab. Kediri', '085806406008', '2025-08-20 14:44:46', '2025-08-20 14:44:46'),
(16, 1, 2, 22, 17, 'HALIMATUS PUTRI DEWI', '3511076601090001', 'BONDOWOSO', '2009-01-26', 'Perempuan', 'Islam', 'PONDOK PESANTREN ASSALAM\r\nJl. KH Ahmad Zaini Dahlan, Bindung, Sumberanyar, Kec. Banyuputih, Kabupaten Situbondo, Jawa Timur', '085258549996', '2025-08-20 15:12:10', '2025-08-20 15:12:10'),
(17, 1, 3, 23, 18, 'Naila Aurellia', '3515186412080002', 'Sidoarjo', '2008-12-24', 'Perempuan', 'Islam', 'ds.beru,kec.sarirejo,kab.lamongan', '087847526234', '2025-08-20 20:24:24', '2025-08-20 20:24:24'),
(18, 1, 2, 24, 19, 'Masyifa Azzahra', '3506224107030016', 'Kediri', '2008-06-12', 'Perempuan', 'Islam', 'Jalan Raya Desa Parang, Dusun Jati, Desa Parang RT.02 RW.01, Banyakan - Kabupaten Kediri', '082228280566', '2025-08-21 01:58:09', '2025-08-21 01:58:09'),
(19, 1, 2, 25, 20, 'SILVIA RAMADANI', '3509296109080001', 'Jember', '2008-09-21', 'Perempuan', 'Islam', 'DUSUN RAGANG BARAT RT 03 RW 04 DESA SUKOWONO KEC. SUKOWONO KAB. JEMBER', '081553625425', '2025-08-21 02:29:10', '2025-08-21 02:29:10'),
(20, 1, 4, 26, 21, 'ARDHIANSYAH PUTRA RISQI SATRIAGUNG', '3578131108080004', 'surabaya', '2008-08-11', 'Laki-laki', 'Islam', 'JL. BATU SAFIR HIJAU IB NO. 23 DRIYOREJO GRESIK', '081259196119', '2025-08-21 02:48:09', '2025-08-21 02:48:09'),
(21, 1, 2, 27, 22, 'XTWOLITA ELFREDA ARDININGRUM', '3524116008090001', 'LAMONGAN', '2009-08-20', 'Perempuan', 'Islam', 'DSN. PERESAN DS. GARUNG RT 02 RW 05 KEC.SAMBENG KAB.LAMONGAN 62284', '085812481417', '2025-08-21 02:49:15', '2025-08-21 02:49:15'),
(22, 1, 4, 28, 23, 'Ahmad Khoiruddin', '3513222801080001', 'Probolinggo', '2008-01-28', 'Laki-laki', 'Islam', 'Dusun Gedangan RT 06 Rw 02 Desa Sumberkare Kec. Wonomerto Kab. Probolinggo', '088289692356', '2025-08-21 03:14:17', '2025-08-21 03:14:17'),
(23, 1, 3, 29, 24, 'Zahra Farrisa Gitya Putri', '3516166408080002', 'Mojokerto', '2008-08-24', 'Perempuan', 'Islam', 'RT.03, RW.02, Dusun Sukodono, Desa Canggu, Kecamatan Jetis, Kabupaten Mojokerto', '081515273353', '2025-08-21 03:33:49', '2025-08-21 03:33:49'),
(24, 1, 2, 30, 25, 'SABRINA WAFA AQILLAH', '3524077107090002', 'LAMONGAN', '2009-07-31', 'Perempuan', 'Islam', 'Labuhan RT 024/ RW 005, Labuhan Kec. Brondong Kab. Lamongan', '0895320245861', '2025-08-21 03:40:57', '2025-08-21 03:40:57'),
(25, 1, 2, 31, 26, 'Anggrea Revalda Pratiwi Putri', '3507056311080001', 'Malang', '2008-11-23', 'Perempuan', 'Islam', 'Dusun Sumbersari RT 23 RW 5 Sumbersari Desa Jambangan Kecamatan Dampit Kabupaten Malang', '085236641625', '2025-08-21 03:54:31', '2025-08-21 03:54:31'),
(26, 1, 4, 32, 27, 'MUHAMAD RIKI FEBRIANTO', '1111111111111111', 'Bojonegoro', '2009-02-20', 'Laki-laki', 'Islam', 'Ds. Leran Kc. kalitidu Kb. Bojonegoro', '085815350339', '2025-08-21 05:02:39', '2025-08-21 05:02:39'),
(27, 1, 1, 33, 28, 'Binti Lailatul Ilmi', '3506036606080001', 'Kediri', '2008-06-26', 'Perempuan', 'Islam', 'Ds. Jambean Dsn. Ngrombeh RT03/RW03 Kec. Kras Kab. Kediri', '082331331605', '2025-08-21 07:07:23', '2025-08-21 07:07:23'),
(28, 1, 1, 34, 29, 'ZYIKA AYUDIA MUTIA SARI', '3515097107080002', 'Sidoarjo', '2008-07-31', 'Perempuan', 'Islam', 'Dsn. urung-urung Ds.Kebonagung Kec.Puri Kab. Mojokerto', '083833180370', '2025-08-21 09:06:51', '2025-08-21 09:06:51'),
(29, 1, 1, 35, 30, 'YOHANA DIAN MAHARANI', '3518014803100002', 'Nganjuk', '2010-03-08', 'Perempuan', 'Islam', 'kecamatan sawahan desa duren dusun sugihan RT02 RW03', '087861047291', '2025-08-21 12:12:02', '2025-08-21 12:12:02'),
(30, 1, 1, 36, 31, 'Diaz Ayunda Kirana', '3576026106080001', 'Mojokerto', '2008-06-21', 'Perempuan', 'Islam', 'Miji gg 5 no 15, Kec. kranggan, Kel. miji, MOJOKERTO', '089513685473', '2025-08-21 12:17:34', '2025-08-21 12:17:34'),
(31, 1, 3, 37, 32, 'Margareta Pasharina Swastika', '3505036803080003', 'Blitar', '2008-03-28', 'Perempuan', 'Katolik', 'Jl Kawi No.19, RT 01 RW 02, Lingk. Kauman, Kel. Kauman, Kec. Srengat, Kab. Blitar', '082233312984', '2025-08-21 13:56:56', '2025-08-21 13:56:56'),
(32, 1, 3, 38, 33, 'Nayla Qolbina Muttaqiyah', '3512087007080002', 'Situbondo', '2008-07-30', 'Perempuan', 'Islam', 'Jl. Basuki Rahmat RT 03 RW 12, Mimbaan, Kecamatan Panji, Situbondo 68322', '081392782093', '2025-08-21 14:23:25', '2025-08-21 14:23:25'),
(33, 1, 2, 39, 34, 'Arum Mawarni Dwi Puspita', '3573046107080001', 'Malang', '2008-07-21', 'Perempuan', 'Islam', 'Jl.s.supriadi VI RT.7 RW.6 No.2339 Sukun Malang', '081330911552', '2025-08-22 02:54:10', '2025-08-22 02:54:10'),
(34, 1, 1, 40, 35, 'DIAH WAHYU SOFANA', '3502056706080002', 'PONOROGO', '2008-06-27', 'Perempuan', 'Islam', 'RT.02/RW.02 Dukuh Gondang,Desa Tugurejo, Kec Sawoo, Kab Ponorogo', '085135330451', '2025-08-22 03:04:40', '2025-08-22 03:04:40'),
(35, 1, 2, 41, 36, 'Salsabila Nur rochim', '3507086304090002', 'Malang', '2009-04-23', 'Perempuan', 'Islam', 'JL.semeru rt.01/rw.12 wajak kab.malang', '085231570358', '2025-08-22 03:13:06', '2025-08-22 03:13:06'),
(36, 1, 1, 43, 37, 'Marisa Ulfi Nur\'aini', '3502146007090002', 'Ponorogo', '2009-07-20', 'Perempuan', 'Islam', 'Gelang Kulon, Sampung Ponorogo', '082231651789', '2025-08-22 03:50:52', '2025-08-22 03:50:52'),
(37, 1, 3, 44, 38, 'AISYAH NADYA FITRI', '3579014110080001', 'BATU', '2008-10-01', 'Perempuan', 'Islam', 'Jl. Bulu Tangkis no.28 RT/RW : 004/003 Kel. Sisir Kec. Batu - Kota Batu Jawa Timur 65314', '08976231930', '2025-08-22 04:13:21', '2025-08-22 11:30:51'),
(38, 1, 3, 45, 39, 'NAYZILA NISRINA SALSABILA', '3507235210080001', 'MALANG', '2008-10-12', 'Perempuan', 'Islam', 'JARAAN,RT/RW:022/006,Kel.Donowarih,Kec.Karangploso,Kota Malang Jawa Timur 65152', '0895639083040', '2025-08-22 04:18:43', '2025-08-22 04:18:43'),
(39, 1, 1, 46, 40, 'CALLULA SALWA ZAKIAH', '3579026012080001', 'BATU', '2008-12-20', 'Perempuan', 'Islam', 'Jl nurhadi no 15 RT/RW:02/01 kel. Bulukerto kec. Bumiaji - kota batu jawa timur 65334', '081249792711', '2025-08-22 05:14:42', '2025-08-22 05:14:42'),
(40, 1, 1, 47, 41, 'NAJWA AZZAHRA RAMADHANI', '3579015209080001', 'BATU', '2008-09-12', 'Perempuan', 'Islam', 'Jl.Darsono No.64 RT.03 RW.10 Kel.Ngaglik Kec.Batu-Kota Batu Jawa Timur 65311', '082135402667', '2025-08-22 05:19:47', '2025-08-22 05:19:47'),
(41, 1, 4, 49, 42, 'MUHAMMAD ALVIN ABIANSYAH', '3506230105080002', 'KEDIRI', '2008-05-01', 'Laki-laki', 'Islam', 'DSN. TAMANAN RT.003 RW.002 DS. NAMBAKAN KEC. RINGINREJO KAB. KEDIRI', '085854734036', '2025-08-22 06:11:46', '2025-08-22 06:11:46'),
(42, 1, 1, 50, 43, 'Rahmadina Nuraini', '3507186810080004', 'Malang', '2008-10-28', 'Perempuan', 'Islam', 'Perum Graha Puntadewa blok D3 no 9', '082257485831', '2025-08-22 06:50:04', '2025-08-22 06:50:04'),
(43, 1, 1, 51, 44, 'HILMAN PUTRA AL RIZKI', '3505061808080003', 'Blitar', '2008-08-18', 'Laki-laki', 'Islam', 'Desa Sidorejo Dusun pancir rt.01 rw.08 kecamatan ponggok kabupaten Blitar', '085855376197', '2025-08-22 07:00:55', '2025-08-22 07:00:55'),
(44, 1, 4, 52, 45, 'Muhammad Bisri Abdillah', '3511232407070001', 'Bondowoso', '2007-02-07', 'Laki-laki', 'Islam', 'Dusun Lumutan rt. 017 rw. 005 Desa Lumutan Kecamatan Prajekan Kabupaten Bondowoso', '085850823315', '2025-08-22 07:03:44', '2025-08-22 07:03:44'),
(45, 1, 2, 53, 46, 'rizki fajar aditya putra', '3507230903090004', 'malang', '2009-03-09', 'Laki-laki', 'Islam', 'RT 22 RW 06,leban, tawangarggo, Karangploso, kabupaten malang, Jawa Timur Indonesia', '088231724197', '2025-08-22 07:40:52', '2025-08-22 07:40:52'),
(46, 1, 2, 54, 47, 'Alexca Evelina Avrilla Putri', '3579016304090002', 'Kota Batu', '2009-04-23', 'Perempuan', 'Islam', 'Jawa Timur Kota Batu. sumberejo dusun santrean jl indra giri rt1 rw 1', '089516275566', '2025-08-22 07:53:55', '2025-08-22 07:53:55'),
(47, 1, 1, 55, 48, 'Brillian Dwi Rahmadani', '3502016404080002', 'Ponorogo', '2008-04-24', 'Perempuan', 'Islam', 'jln. Raya Ponorogo Pacitan RT/RW 04/01 Dusun Tengger, Desa Slahung, Kec Slahung, Kab Ponorogo', '085231359622', '2025-08-22 07:58:16', '2025-08-22 07:58:16'),
(48, 1, 1, 56, 49, 'Puri Bondan Ningtyas', '3573015003090003', 'Malang', '2009-03-10', 'Perempuan', 'Kristen', 'jl.lesti 1c / 13', '089528420787', '2025-08-22 09:32:46', '2025-08-22 09:32:46'),
(49, 1, 4, 57, 50, 'SAIFUL BAHRI', '3527120711080001', 'Sampang', '2008-11-07', 'Laki-laki', 'Islam', 'Dsn. Masaran, Desa Banyusokah, Kecamatan Ketapang, Kab. Sampang, Jawa Timur', '087770359929', '2025-08-22 11:13:03', '2025-08-22 11:13:03'),
(50, 1, 4, 58, 51, 'MUCHAMMAD ASKAEV ANSORI', '3514120804090004', 'Pasuruan', '2009-04-08', 'Laki-laki', 'Islam', 'ARJOSARI RT 03 RW 18 KEL KEJAPANAN KEC GEMPOL KAB PASURUAN', '085736667180', '2025-08-22 11:45:00', '2025-08-22 11:45:00'),
(51, 1, 3, 59, 52, 'Baitha Hayyunisa', '3573046108070001', 'Malang', '2007-08-21', 'Perempuan', 'Islam', 'Jl. Taman Agung No. 14', '087753357870', '2025-08-22 12:16:46', '2025-08-22 12:16:46'),
(52, 1, 3, 60, 53, 'Andi Dixie Aurellia Neysa', '3573047107070007', 'Malang', '2007-07-31', 'Perempuan', 'Islam', 'JL. Gamalama 56, Pisang Candi, Sukun, Malang', '0881026128055', '2025-08-22 12:22:18', '2025-08-22 12:22:18'),
(53, 1, 3, 61, 54, 'Rahma pujianti', '3524236904090001', 'Lamongan', '2009-04-29', 'Perempuan', 'Islam', 'Perumahan Tikung Kota Baru blok m12A\r\nKecamatan Tikung Kabupaten Lamongan', '085746029424', '2025-08-22 13:14:10', '2025-08-22 13:14:10'),
(54, 1, 3, 62, 55, 'DAYINTA  SURYANING SEKAR DHATU', '3519096401090001', 'MADIUN', '2009-01-24', 'Perempuan', 'Islam', 'Jl.Muria no.03 Magetan,Kec.Magetan,Kab.Magetan', '08985952237', '2025-08-22 13:34:42', '2025-08-22 13:34:42'),
(55, 1, 3, 63, 56, 'CRISSANDA REBECCA ANGELA CHILL', '3502114611090003', 'PONOROGO', '2009-11-06', 'Perempuan', 'Islam', 'Desa Karangan, Kec Balong Kab Ponorogo', '081553552667', '2025-08-22 13:39:15', '2025-08-22 13:39:15'),
(56, 1, 2, 64, 57, 'ANNA ALTHAFUNNISA', '3526014207080002', 'Bangkalan', '2008-07-02', 'Perempuan', 'Islam', 'PERUM LAGUNA INDAH BLOK L-23 , BANGKALAN', '088989073588', '2025-08-22 13:41:46', '2025-08-22 13:41:46'),
(57, 1, 4, 65, 58, 'ESA NUR WAHYUDI', '3505203006080001', 'Blitar', '2008-06-30', 'Laki-laki', 'Islam', 'JL. DIPONEGORO DSN SONGSONG, ARDIMULYO, SINGOSARI, KAB.MALANG, JAWA TIMUR', '085708259219', '2025-08-22 13:42:27', '2025-08-22 13:42:27'),
(58, 1, 4, 66, 59, 'ZEFRIZAL RAHMADANI', '3503052505090002', 'TRENGGALEK', '2009-05-25', 'Laki-laki', 'Islam', 'Ds. Prambon RT 20 RW 04, Kalongan, Kec. Tugu, Kab. Trenggalek', '083848718032', '2025-08-23 09:23:46', '2025-08-23 09:23:46'),
(59, 1, 1, 67, 60, 'MERCY ALVINA PUTRI SILOLO', '3507186301080002', 'MALANG', '2008-01-23', 'Perempuan', 'Islam', 'JL. KAPI SRABA 2, 10E NO. 27', '087777629778', '2025-08-23 14:19:14', '2025-08-23 14:19:14'),
(60, 1, 3, 68, 61, 'Firda Aulia Zahra', '3504093010170001', 'Tulungagung', '2009-01-28', 'Perempuan', 'Islam', 'Dsn. Plenggrong, Tiudan Kec. Gondang', '085815379434', '2025-08-29 11:40:45', '2025-08-29 11:40:45');

-- lampiran
INSERT INTO
  `lampirans` (
    `id`,
    `peserta_id`,
    `no_surat_tugas`,
    `fc_ktp`,
    `fc_ijazah`,
    `fc_surat_tugas`,
    `fc_surat_sehat`,
    `pas_foto`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    1,
    NULL,
    'berkas_pendaftaran/DLiOhW7qkRRVbZwHfETKUC8bKKnlmBF633kBuOgW.jpg',
    'berkas_pendaftaran/Uqav8U7DdXePLsRivxC2IgTNb0nj5KmOh9lVUbSg.jpg',
    NULL,
    'berkas_pendaftaran/LBXViL1dZLM0TsWPJuqWW8ZJLTvrNGk4IPwxAoPM.jpg',
    'berkas_pendaftaran/ip61RuFJWUBjEKh7uaL8dm4eutKk1NcJrylpYEYn.jpg',
    '2025-08-20 09:28:17',
    '2025-08-20 09:28:17'
  ),
  (
    2,
    2,
    NULL,
    'berkas_pendaftaran/R24rYR0U23bEm6bGsHpa35B7ZbKg8HqT8LJd3oZa.jpg',
    'berkas_pendaftaran/4az0mbQTUVIKdi9BhXR1LBuzQsoz36USW7Xlyktq.jpg',
    'berkas_pendaftaran/88ToPmJkkpgTDlKMXDMSJdah1xKKP52ig3Gqr0wd.jpg',
    'berkas_pendaftaran/m0YRJC5rgeAeCLnvvgWh1sFu9a80AqNWdHYfixFJ.jpg',
    'berkas_pendaftaran/oN7XlcbWUsW9t7QffHcQRXB2I4PsSqwKmNqlLOs2.jpg',
    '2025-08-20 10:06:36',
    '2025-08-20 10:06:36'
  ),
  (
    3,
    3,
    NULL,
    'berkas_pendaftaran/DsiVteYESVXEpTSifNDDB4hhum5UAvxqe9A4ctWm.jpg',
    'berkas_pendaftaran/wQ2I4soU1zhT4Hq9lipydpWacwwGLwQMYOf2fwXp.jpg',
    'berkas_pendaftaran/gSgFkVkXx33Bms4aTsgASBxrEVpjMWbTcDjbaywA.jpg',
    'berkas_pendaftaran/uRCGhWQfZ8XxDSnUZi84nGj4tM6BcCjJkuUdC3cA.jpg',
    'berkas_pendaftaran/udpmuXJdXckaDa2AbBFauTAi81PpwGjCp4eIsmDa.jpg',
    '2025-08-20 11:23:32',
    '2025-08-20 11:23:32'
  ),
  (
    4,
    4,
    NULL,
    'berkas_pendaftaran/T8GCqulx7QvQdf81jSgyP9uUrTKnKyJidClwVGK0.pdf',
    'berkas_pendaftaran/n3dIY3YqKBi7r4zzflVk4h1za4BpnB7dD00gUKNT.pdf',
    'berkas_pendaftaran/9tZScnvE7wTvAJyvIwEYIzjB6nr5QWnyaWRnTXqd.pdf',
    'berkas_pendaftaran/ZyherX39sQ8LlT1EwVZmajKDAVN7Xw0lIOooAy4I.pdf',
    'berkas_pendaftaran/EjeTsSdbjZ3ANyZfROGEogIF8wlgxjrysDI1Bpn1.png',
    '2025-08-20 11:58:14',
    '2025-08-20 11:58:14'
  ),
  (
    5,
    5,
    NULL,
    'berkas_pendaftaran/adP4asYSqmv7Ivr1bySc0nqTaqHwGeobaS6F7sv6.jpg',
    'berkas_pendaftaran/16TQRoEfW7oM1WwzfA7xH26KhAzyhjS9hBmvi5dp.jpg',
    NULL,
    'berkas_pendaftaran/anrum4dMPiF2pMryevWFctAxtGXXX4OVzphd33QS.jpg',
    'berkas_pendaftaran/WWw5TMn0Rp5yIHOHyuhame24Rlxb4hjKcWR2FBLL.jpg',
    '2025-08-20 12:13:59',
    '2025-08-20 12:13:59'
  ),
  (
    6,
    6,
    NULL,
    'berkas_pendaftaran/Kl2cQJvIJ6gdiLUUDVlOj5WXebjyk8jAOFakEgJQ.jpg',
    'berkas_pendaftaran/na3GAZTlFIrirVgiOBOfDRsTw70LgLBGv4UsyNN9.jpg',
    'berkas_pendaftaran/cufRXNkABnsGvyHdVTsgNUouyyTu52qGNHYw9gy6.jpg',
    'berkas_pendaftaran/XwdFsyx362yCm7NiRsN4pPgxqVCbIALrYIfpr5Md.jpg',
    'berkas_pendaftaran/4EgTdRjBYlJ4QbgDBps3GszRc1QunOqE1ressdck.png',
    '2025-08-20 12:19:16',
    '2025-08-20 12:19:16'
  ),
  (
    7,
    7,
    NULL,
    'berkas_pendaftaran/MGRIqH7WzKpx2SYKMvkAHoxnfU6LM78OOjZWjdGi.pdf',
    'berkas_pendaftaran/DjTkniIL7xT3WH8nqPIOsdzIh9lDdwtDapFVQgxn.pdf',
    NULL,
    'berkas_pendaftaran/JvWY6IialaMf0XhmfecLuE7OPZBVwPMcxasa4aW7.pdf',
    'berkas_pendaftaran/ScG2qwvBUtU8CNr1TGy5ECZ9Ovdxpaau71m1GxhI.jpg',
    '2025-08-20 12:25:07',
    '2025-08-20 12:25:07'
  ),
  (
    8,
    8,
    NULL,
    'berkas_pendaftaran/fsDLI9PoyXL7K7jXxZF4bsKEax23OOMzwd7hJQZl.jpg',
    'berkas_pendaftaran/PomULPvqMbe4fxKhSoVy1RZWlAVIeiaGjzP60EbN.jpg',
    'berkas_pendaftaran/CqIoh1H9sY2dGWbbJ9cXWp1yuYYFDn3PVZ8CmMSJ.jpg',
    'berkas_pendaftaran/tPaqlWqCGaFAW4fuH7TxZ9pMvlAOhM9DeCb2OoCp.jpg',
    'berkas_pendaftaran/O6ifUtluntKbhhpJE1tapFS7mK6DEZefIi7LtWhe.jpg',
    '2025-08-20 12:30:28',
    '2025-08-20 12:30:28'
  ),
  (
    9,
    9,
    NULL,
    'berkas_pendaftaran/c27idVlo7nAVWj1mONmUGyWOwn214Vq8E1y3U4ch.jpg',
    'berkas_pendaftaran/UczvMzcIGyCQqL5FhGad9PN2AfvudgmB5YCLtgfg.jpg',
    'berkas_pendaftaran/pAe8qPITHZOFeZ56bp4dEy1USErhlX3Y9jKWeglu.pdf',
    'berkas_pendaftaran/dfdkwAAMbttmb47otgiHwaHGAq3DfnuHozECfpAD.jpg',
    'berkas_pendaftaran/MtRwy4rpkUhE3N5QJpAO2X0kfx4FfBpA5gpxLgcu.jpg',
    '2025-08-20 13:04:04',
    '2025-08-20 13:04:04'
  ),
  (
    10,
    10,
    NULL,
    'berkas_pendaftaran/rm72BVLUn9xP33LCSfochbPE7F943YFBkK1k7IRb.jpg',
    'berkas_pendaftaran/bQkxTwSxa6owOSxyKgBcjPFqQtDdtXV6MwjNmiZK.jpg',
    NULL,
    'berkas_pendaftaran/JaoSHMGXdcOrjy68Aqeir1TSMLcWsXAu0zp02mJx.jpg',
    'berkas_pendaftaran/RoZwbYHlx8xdEMfvacb4LUPRWcPlrtaCzp1V2dfU.jpg',
    '2025-08-20 13:10:17',
    '2025-08-20 13:10:17'
  ),
  (
    11,
    11,
    NULL,
    'berkas_pendaftaran/23QTE0vL5jCrIBER2FULhUjuQ2tE8f634MuwMOLe.jpg',
    'berkas_pendaftaran/Uyr0zF8NsZAPuBciqJ9JzYViLTUdFRl54Qfib4PT.jpg',
    'berkas_pendaftaran/jhoGglShxEaY7ggcLHiiqyIKJazUs8KwHXMVdBul.pdf',
    'berkas_pendaftaran/bNRGbXlWgDMkHkUh8VCvqAQ4lMonuaPIC7LK5ze6.jpg',
    'berkas_pendaftaran/f121swm2K5NeTIc2SCIupamLAPMSIWTqLzqvghoP.jpg',
    '2025-08-20 13:57:25',
    '2025-08-20 13:57:25'
  ),
  (
    12,
    12,
    NULL,
    'berkas_pendaftaran/bFVqgaJRhCujegE6D7CkD4Ecl9utVzMvVUhSkcm0.jpg',
    'berkas_pendaftaran/iUP9eBVyCToLbrS4wy9DnEIPag9nVYyhSaGMxBLT.jpg',
    'berkas_pendaftaran/a8HU8FCixEqNhd9PS1yGc0PiRtUZeHlygXXMJBht.jpg',
    'berkas_pendaftaran/yXaSeV1ekbt4n4IcaIZgHIPHmFfxsGuJYArCVo1V.jpg',
    'berkas_pendaftaran/XtISkvPuUdBKAuJcSmWgJqpGVFgYGZkAGc9Dm9U3.jpg',
    '2025-08-20 14:09:41',
    '2025-08-20 14:09:41'
  ),
  (
    13,
    13,
    NULL,
    'berkas_pendaftaran/mFJoSrq2CRjD73xbxwZVlWGbYflO0MFP68UdYBvq.jpg',
    'berkas_pendaftaran/MYJZTav40iLzaCm4kEdGDdIwqUOeJCOkUKIPYzQ4.jpg',
    NULL,
    'berkas_pendaftaran/6aj6fvcNIYfnVYzh1269a1gxw9cTyGacIeskA0MN.jpg',
    'berkas_pendaftaran/SZKeu1RaRplThmJA0cQoI41EPHxqeDigG21GGutu.png',
    '2025-08-20 14:12:20',
    '2025-08-20 14:12:20'
  ),
  (
    14,
    14,
    NULL,
    'berkas_pendaftaran/e7jlNlxjtMFSovE2KrAM1TkK8wP7VSq1kRR0Kidl.pdf',
    'berkas_pendaftaran/eiqLcFicIyppENcqkSIAkV7yieYEpCqYVFq33v4O.pdf',
    'berkas_pendaftaran/TgpyYQnr01WLb13UJshx7VqgfUwrAOUs4MPyPlhH.pdf',
    'berkas_pendaftaran/g5vG7QL5sQr4ADkJxhVf04MdxgPcRh8S0dS2itAU.pdf',
    'berkas_pendaftaran/TAxbktb3wtif9W2Er92IMZ959i3WnunjOlEqbjhs.jpg',
    '2025-08-20 14:28:23',
    '2025-08-20 14:28:23'
  ),
  (
    15,
    15,
    NULL,
    'berkas_pendaftaran/6vKnxmmV8ESrcgT9fa2KTcScSV7KP5ASobdK7npm.jpg',
    'berkas_pendaftaran/2GjMxGAeVZStrJlvASwtZBHW2YocdrO8VHAkuRUa.jpg',
    'berkas_pendaftaran/AFA03lBgCxF3kAGsoQYT9NO47ROcdVxPN4sbCB81.jpg',
    'berkas_pendaftaran/9GR1Il2J6bmifPqq4X0JrvuiMfFHDcLrwFE4yvCn.jpg',
    'berkas_pendaftaran/YJ6zW5wmsPcb0keY26Ww3WbYNM84G4G1tNZbvKRJ.jpg',
    '2025-08-20 14:44:46',
    '2025-08-20 14:44:46'
  ),
  (
    16,
    16,
    NULL,
    'berkas_pendaftaran/FYCqVU1dlEqtDD94MFplSBt0YeS0N4Fezt5YQCUV.pdf',
    'berkas_pendaftaran/0ZFyZEGMsFWUTj00Me3vrRoGK2jGRzgGQ7QteCS0.pdf',
    'berkas_pendaftaran/7y2vsR4aFxL6tbHTg0w3ZAM6RTRsGsPhls7Tq9Lq.pdf',
    'berkas_pendaftaran/B5sirsn6yGUXdH4BbhcI2HiMhWAouAAlOKo6HnZR.pdf',
    'berkas_pendaftaran/zBfl3DZYSmkt9FiD1ArkYwQU6XBKf8KJJZxWs1IU.jpg',
    '2025-08-20 15:12:10',
    '2025-08-20 15:12:10'
  ),
  (
    17,
    17,
    NULL,
    'berkas_pendaftaran/vRUwqxcVoNxBRgBaFxk694LJRO2KB7hO00C9pXGh.jpg',
    'berkas_pendaftaran/tmBA36pYANHY83h7BjOIKS4yFuUSGPe3HltGTnbZ.jpg',
    'berkas_pendaftaran/vOK951Bm4nCAThpdkhUz2U7nsjR9FBHzjnEyDTdZ.jpg',
    'berkas_pendaftaran/eSRA2MwmTpywzb77pPiddXkD5P7fNAlWo9vKAVTV.jpg',
    'berkas_pendaftaran/R7fPdE8uKIM0BimPsjbxDheCgSPIeyhCnafWhy5X.jpg',
    '2025-08-20 20:24:24',
    '2025-08-20 20:24:24'
  ),
  (
    18,
    18,
    NULL,
    'berkas_pendaftaran/OZzFMWS2RdmWgjaUp5v2zQdldkzVaQgtdZqeWzCh.pdf',
    'berkas_pendaftaran/Oo59g0e2AIetE6TMIIA7LYlO6EUiE9qdFBHTPk3p.pdf',
    'berkas_pendaftaran/CFCsw0t7qbMNawuyApFUzN71Xazo0wiGP6ZhZkRt.pdf',
    'berkas_pendaftaran/Z8PK9tRwvd30emuoV5PqxsK4Z2S3xNtn6jyrXQgo.pdf',
    'berkas_pendaftaran/4OLZmayJMMENy6pzOEQw9Wuhoj4TlT7y8WfoKXgd.jpg',
    '2025-08-21 01:58:09',
    '2025-08-21 01:58:09'
  ),
  (
    19,
    19,
    NULL,
    'berkas_pendaftaran/SqDWFrqK2TNcPU5PnUPrqN9SGzfjADXNrawxpdMW.jpg',
    'berkas_pendaftaran/DqnleYtNQ7CKwFjOBUKQPfY8EQ5y8kCsjGxf51Ti.jpg',
    'berkas_pendaftaran/5K4cmDwy7bs6KnkTEX0ku6YZGoI0NdAfq07HMxot.jpg',
    'berkas_pendaftaran/BgNYF4uyx8RkkqdkTLkgkP69enLL8AvtCRVzoFN8.jpg',
    'berkas_pendaftaran/UYE4GL2u1Snxf5UzQaTNutdn8GQruZUgtaDyDUzk.jpg',
    '2025-08-21 02:29:10',
    '2025-08-21 02:29:10'
  ),
  (
    20,
    20,
    NULL,
    'berkas_pendaftaran/8ZaoUWuyEfQ9y9n6A3gwB9n3GwKPw1sWHV6wkGef.jpg',
    'berkas_pendaftaran/Pz8Yg74OCxPaa7oPC4l8jpyOutFFMVTPAbAFuYMd.jpg',
    'berkas_pendaftaran/Zrerl3PXTJgNzKQ0nmWGZ6fUPhGp5B3HVpEXfV7S.pdf',
    'berkas_pendaftaran/6s26PEiFDDOgdqAHEr4Tg5gca1twbn9QMyg0NcgJ.jpg',
    'berkas_pendaftaran/hXgEbtLd4bsBEyGQ5J3LktKxIiS14dFN3yzFadPy.jpg',
    '2025-08-21 02:48:09',
    '2025-08-21 02:48:09'
  ),
  (
    21,
    21,
    NULL,
    'berkas_pendaftaran/B7K0t98kwrnlGAXrulOce2edta4V15o7KBykTziA.jpg',
    'berkas_pendaftaran/NLqUZshHXupbdRGfCM5LAZRh4izBJBIYqi16ckeK.jpg',
    'berkas_pendaftaran/fawzEa7zDHdBbX0nsJKqwc5D2wBbMH1hzHAoVmKl.pdf',
    'berkas_pendaftaran/6vLqoJsCov1qRtGhdtQlO25w9WlvH8M9vjlKxuqh.pdf',
    'berkas_pendaftaran/MpOXYJwDqrOo767hBk9Hq7Wz3PNxyBtqo4TxcXIa.jpg',
    '2025-08-21 02:49:15',
    '2025-08-21 02:49:15'
  ),
  (
    22,
    22,
    NULL,
    'berkas_pendaftaran/25pe3Z4K4HQlYE6WQl01uHwPwvpbs777IlyamCIa.jpg',
    'berkas_pendaftaran/hFmQttUi6AcuolEpmB9xtUHuTR1lCrghCOGZOM2Z.jpg',
    NULL,
    'berkas_pendaftaran/HnRaCAZa2M72i5MzaYKBcTLlY4lkK8U4CDYDcky6.jpg',
    'berkas_pendaftaran/m9ecBKXa0iosdZngtVI3qyhrYG5IYgz5i0oCvKVF.jpg',
    '2025-08-21 03:14:17',
    '2025-08-21 03:14:17'
  ),
  (
    23,
    23,
    NULL,
    'berkas_pendaftaran/mFFg8brVl6No9kgnCGp3fwwQ4e1J7chFBVetq62W.pdf',
    'berkas_pendaftaran/40gSP4Wl3W1vZCeXnfIXEEftFxFMz5acx0KC5fWx.pdf',
    'berkas_pendaftaran/oNai5aHipvPumXtxIcNXArni5pdoARFnlUe6ffcZ.pdf',
    'berkas_pendaftaran/UNKXizCFtQVRt3kRqzfV4iVkfYIvOT4N0mvGqhto.pdf',
    'berkas_pendaftaran/qRCWyeo9uAIkcz5ieYUcBbS9SAXyXJa2EqNIp9RF.jpg',
    '2025-08-21 03:33:49',
    '2025-08-21 03:33:49'
  ),
  (
    24,
    24,
    NULL,
    'berkas_pendaftaran/eZJs6MqqgtcEDGxrAQBIMkCCz7E5EoKm9poicTbH.pdf',
    'berkas_pendaftaran/0AhpsVZh7ZfXnLhKhtgn34mzSmHbYdPd6C6B5hSh.pdf',
    'berkas_pendaftaran/PkzpUukH9JWVQIj4FgMJIeziyDGO5CbkAoOU0yqU.pdf',
    'berkas_pendaftaran/AXFGF6uU8YKxmIOw3vSz3UQCxAG37GHNpqg6WuVq.pdf',
    'berkas_pendaftaran/YuoIAlxZYFfT1FWootWdV4YsNWKXVgHJmGlP6oeV.jpg',
    '2025-08-21 03:40:58',
    '2025-08-21 03:40:58'
  ),
  (
    25,
    25,
    NULL,
    'berkas_pendaftaran/paFTdImrJFFsuhOUHJQ6xH0IjiLP9XPsx3KHlgEw.jpg',
    'berkas_pendaftaran/C81NEbXqO6kPqFIJ6qoAvvg9oLXuTKaqRMj3OgAG.jpg',
    'berkas_pendaftaran/mLsLoptYvzq4rZCWYbZ8FWZj63Ay3yWR7pMMKXFU.jpg',
    'berkas_pendaftaran/9pHnyLIOdC4wIHFrOmKatTRvHpHDgCAWsfgRv4yZ.jpg',
    'berkas_pendaftaran/UnxUt8ZxoBQKSqTP5ULReHSL9RYeRCg0QSPRU5JQ.jpg',
    '2025-08-21 03:54:31',
    '2025-08-21 03:54:31'
  ),
  (
    26,
    26,
    NULL,
    'berkas_pendaftaran/YZFsBT2Ae6vZaEDZsZ16ygocUtfDnaDC8LfzwO2e.pdf',
    'berkas_pendaftaran/5AswnU6QSi5tERpKvIRjNCcjAkEU67oifsErCa1d.pdf',
    'berkas_pendaftaran/xl9ZEdLzXwWLiqNy5BgJrLjDqDnMNaHY3COI8vR9.pdf',
    'berkas_pendaftaran/Xm3I0vTBLfhbwQKFiy1u6RdhcB1vzsQ0Hhs6HBhK.pdf',
    'berkas_pendaftaran/8TpmubPVlYCLEV6HTyzSP9Gg07XfVhMJRZM1OWe6.jpg',
    '2025-08-21 05:02:39',
    '2025-08-21 05:02:39'
  ),
  (
    27,
    27,
    NULL,
    'berkas_pendaftaran/Q21LcuDyZebKcy3GuThUYcFr1cBeE55pQ3hLG5ID.pdf',
    'berkas_pendaftaran/oW3SwaYiEy7t97DsxWLI3bXBJ3Zl4ss45SMIwQmc.pdf',
    'berkas_pendaftaran/PoLYgYzxNJ864sfL5XQWSuYcev3PmybQcckot9Dg.pdf',
    'berkas_pendaftaran/vRRcYDzPhZv1Yu55FxZMQfutKuri8bHo1DDiS4tP.pdf',
    'berkas_pendaftaran/hpnStANnsCrcGGysm4hQxbsQ0vk8OSzDECmeULKt.jpg',
    '2025-08-21 07:07:23',
    '2025-08-21 07:07:23'
  ),
  (
    28,
    28,
    NULL,
    'berkas_pendaftaran/mQWHfP0NyNQdo2ePy50KDj0cEVY3OGyNzAVsVk9c.jpg',
    'berkas_pendaftaran/Wk9sDOBj9OwKt4zuwdsBNpyxJ18WXF58lRRERR26.jpg',
    'berkas_pendaftaran/MBpYTxTAkgcxiblEHBywXR4fKOxmLcu7b21PYGl6.pdf',
    'berkas_pendaftaran/TZ67TW4q1uSFE757XFJ6fhwd1xQQzL8EnXTSbMpM.jpg',
    'berkas_pendaftaran/ikEVVR2sSo15j1NIsvSRT2doLtsgcg0oc5wmGyBV.jpg',
    '2025-08-21 09:06:51',
    '2025-08-21 09:06:51'
  ),
  (
    29,
    29,
    NULL,
    'berkas_pendaftaran/YcgE2lUUdEJLUrZQTKVS8JKIUK1Yc6kO2YIFZJvH.jpg',
    'berkas_pendaftaran/Xv6zcNYBdR9UJoo571wiVzkLUqwLdRnRZx0XVBtR.jpg',
    'berkas_pendaftaran/WPZapdM4wrJZpv9FrGhHBH7485ngn4Wmmg5oiYcn.jpg',
    'berkas_pendaftaran/YYSarK6tGG2zYgtP7f2GEuVgTuTAJ9FZtOuAyk9V.jpg',
    'berkas_pendaftaran/sck0rkGeezHTeZoTUYs6QwWLkKtkakbZ1BCPPVOa.jpg',
    '2025-08-21 12:12:02',
    '2025-08-21 12:12:02'
  ),
  (
    30,
    30,
    NULL,
    'berkas_pendaftaran/62MOI3TA0Fv2dl7VKVGzkay0yITieGrYBpiTGYtD.pdf',
    'berkas_pendaftaran/KVD5JdXOCXwks585Eq1FDnAKEsGMg1LUEusMol8w.pdf',
    'berkas_pendaftaran/X2JiUz2ZOqKffkv8KWJJSQihQ6ByDUKa8pPECOgC.pdf',
    'berkas_pendaftaran/E6LnY7R7GidRClGg2FbtcpbBfCaWBl3HNlp1yEdK.pdf',
    'berkas_pendaftaran/icyIaDiXWtVgEOFYjyzJDKTDEHTJ6KOOB0rmuTED.jpg',
    '2025-08-21 12:17:34',
    '2025-08-21 12:17:34'
  ),
  (
    31,
    31,
    NULL,
    'berkas_pendaftaran/CVE1LpDuHucvpPw7hIzo1ZW7kDAJwem4dXb0X31d.jpg',
    'berkas_pendaftaran/qsUmMbeBxKKAAWoORVIioWa3C5ZwyMbtKKML5WnR.jpg',
    'berkas_pendaftaran/ZbvaMyZ4id4fG1VSNOqqC8c8wG496k7EL6YyVWbV.jpg',
    'berkas_pendaftaran/wVY12lq1cvkiX18b9ZdvBdmpI1Em432PkRscSfSy.jpg',
    'berkas_pendaftaran/EYfS78cmIVjkhZfsAhZBazvt8meQteSiHHZAxCUI.png',
    '2025-08-21 13:56:56',
    '2025-08-21 13:56:56'
  ),
  (
    32,
    32,
    NULL,
    'berkas_pendaftaran/hiYr0KoaReifqbsFor5R5d3IJB2T0BU7oVQBnqao.jpg',
    'berkas_pendaftaran/EGHhkcfXn4FtghmncxB3O2fVTtgk0jkdrH5023m2.jpg',
    'berkas_pendaftaran/98YJL1L6mepOyykhEvRE0uznnCuSGSuZjT4BIILD.pdf',
    'berkas_pendaftaran/4XJHGbO8Mx2IIGDNFkEonIWIQizZbzdwtpFRCy4b.pdf',
    'berkas_pendaftaran/QPfldWXrKOn1ZOO4OdIqfBQcDnQ03uEDGjZolCwi.jpg',
    '2025-08-21 14:23:25',
    '2025-08-21 14:23:25'
  ),
  (
    33,
    33,
    NULL,
    'berkas_pendaftaran/zbzN56bxcz5s2IGw5hW9q7p6K995an2insYMGuTB.jpg',
    'berkas_pendaftaran/ERG9pG2vzCDX9p3C0g9DAFMwJSO9r024oJJmdXKz.jpg',
    'berkas_pendaftaran/2ro7v3km6IZqJGPlUyFzQVWmQN4xLah6hOOmuDYz.jpg',
    'berkas_pendaftaran/dx5SIGZ7VBEOcZ8ld95Mmbv85n3z4KKNyXsYzlav.jpg',
    'berkas_pendaftaran/9TXhvjK1dudIH1XwTVo4p4ZytbhAGQ5SECcZx4RO.jpg',
    '2025-08-22 02:54:10',
    '2025-08-22 02:54:10'
  ),
  (
    34,
    34,
    NULL,
    'berkas_pendaftaran/ns2QTKHKR4U1XiY8QUej4iSIenf17eEvbAlkf8ef.jpg',
    'berkas_pendaftaran/zHuv6t19qQB6Ifa8CwAiY94674qZb3DhgzgMW0A3.jpg',
    'berkas_pendaftaran/7SBsQmIbchqscplyALNkq33fliBQbWl60Jk7Vc83.pdf',
    'berkas_pendaftaran/BwafoTjxu6PpVuCKV3doxL4vbeCCY4773rRdfGdw.jpg',
    'berkas_pendaftaran/2DcIl22gVZyMmPUa9gdmfAFiwv988Do89va5P3xF.jpg',
    '2025-08-22 03:04:40',
    '2025-08-22 03:04:40'
  ),
  (
    35,
    35,
    NULL,
    'berkas_pendaftaran/gOKmhJOkU1MTT3Jkx0dXmWr0CiWw6c7CNbDCMZLI.jpg',
    'berkas_pendaftaran/ZNAt0vgbLDTRYx7DxQGlBieD47zeeTzcqW87QKPx.jpg',
    'berkas_pendaftaran/XhFdwJ6F1IzKREa3vuLqMKLu4qTwWrjEp2sEDdrU.jpg',
    'berkas_pendaftaran/UINCXIiAM0BDrX6NZpvWmHDfJ4Jod08CVaYwR3Fp.jpg',
    'berkas_pendaftaran/pQnTw8gfhWZN9pASH1dK23MtOBKqWWA9s5kkDj2q.jpg',
    '2025-08-22 03:13:07',
    '2025-08-22 03:13:07'
  ),
  (
    36,
    36,
    NULL,
    'berkas_pendaftaran/4CMuHOT7jkJHDIM1No3cT5AxOZI2dWldjQpuBiPR.jpg',
    'berkas_pendaftaran/YEyFaruRPOvDJJMXBZMTU3g77grZHcuoSlGGlSf8.jpg',
    'berkas_pendaftaran/XMKjiiMVYduN0Dt9rqXVbxfmqUbd1LrkeCEer08u.pdf',
    'berkas_pendaftaran/p4LvK4p0QMo5wGttdKSlqzZGCARBY56LuY7cnKFJ.jpg',
    'berkas_pendaftaran/ozxGofVBelDEQW8G3uKyIVd2eXx4r4cmMbOIawTi.jpg',
    '2025-08-22 03:50:52',
    '2025-08-22 03:50:52'
  ),
  (
    37,
    37,
    '800.1.11.1/2290/101.6.10/2025',
    'berkas_pendaftaran/pJXn2otF5MP5UGpvjvAJJhcpyLdHhBNHQPkAOQ0U.pdf',
    'berkas_pendaftaran/gDEmE7VuWY776UNFRuaSP1EMshbK94jUgd59mIYX.pdf',
    'berkas_pendaftaran/TtobRVriTYdpoztlFemwexiS2SutvcNqMINgpQoM.pdf',
    'berkas_pendaftaran/6mkKfO6UcR3FxwSR4XV8n6trpevs32auVhvEikSY.pdf',
    'berkas_pendaftaran/EdfsTIzswNpQM38rYGCSFWTBZjf96SdUTyDpWXgE.jpg',
    '2025-08-22 04:13:21',
    '2025-08-22 11:30:51'
  ),
  (
    38,
    38,
    NULL,
    'berkas_pendaftaran/QGdG3kYIVeJEft93MiHkqVA5cckiHmZCIPMYhnYO.pdf',
    'berkas_pendaftaran/Okbdcx9E5MUufi9rf35DaSlfE5fQDn2PJIaIvwqj.pdf',
    'berkas_pendaftaran/MbKvHBV4L1VnsZubMn2g6yi4pz2xbZlQa5v5r9vc.pdf',
    'berkas_pendaftaran/klrMrXohaHlXvIQ5bpBcjVw6pY4KlK9DgUv1tlQB.pdf',
    'berkas_pendaftaran/wI7QX3E7oyxI7eIIPMApxlx5VIBbswNskGNeU91r.jpg',
    '2025-08-22 04:18:44',
    '2025-08-22 04:18:44'
  ),
  (
    39,
    39,
    NULL,
    'berkas_pendaftaran/3ORqJYCKBZoxyvG1AzwSe2wkWl4rrwrYHdFneEYZ.pdf',
    'berkas_pendaftaran/sGW76oA6LKrm2GXamqcKWs4bvLvoAQPjN1bHQuGc.pdf',
    'berkas_pendaftaran/5XO1Xw35Wh2ORCLtkkl1f8r8Xag5reTgW1jg0ydj.pdf',
    'berkas_pendaftaran/hjwOHqT2qhB3B3dFcu3dBzjhXIiXO6bs7Xf1bHAw.pdf',
    'berkas_pendaftaran/8FEfGoYu1isDq8h7IpHrQt3VDD4H8cDn57dXat4G.jpg',
    '2025-08-22 05:14:42',
    '2025-08-22 05:14:42'
  ),
  (
    40,
    40,
    NULL,
    'berkas_pendaftaran/v8SSvdUREUPV27JRTIgk1yEuBVcEcrhO74KV0YXk.pdf',
    'berkas_pendaftaran/YlioJCv9ZfKKv0wlwLn4Nj9hEt4ev4H50xvUMvxE.pdf',
    'berkas_pendaftaran/XMS7ZNw3tDctgP73DfMnXeqRJg0iPBsiQVgLFgkf.pdf',
    'berkas_pendaftaran/hjdBB5ozV4LLbV0MhPIIhMiRcqEWHXsMY5XHKrx9.pdf',
    'berkas_pendaftaran/q5hNWDe446FqAQ1SEJMDuWnzc9MCVfhRwxYZV6Wk.jpg',
    '2025-08-22 05:19:47',
    '2025-08-22 05:19:47'
  ),
  (
    41,
    41,
    NULL,
    'berkas_pendaftaran/tyJiOd7prhvuwB93dSAZQvPQsbcgzTusuFVhLQfM.pdf',
    'berkas_pendaftaran/jJ6x4xRXG5O1OeSHGRoez4NSVOcA4r7wxIsimQAZ.jpg',
    'berkas_pendaftaran/ZszjILzIQo3bYVOGoH7cyK8dLC5qeNWYJ55x3MdV.pdf',
    'berkas_pendaftaran/f48NCWEXGyjk1WYUnpp2DPRsXYSm2vpvxljcEx9F.jpg',
    'berkas_pendaftaran/LBRAYiV8HeKpOOnNFYGPDbr2aoD9zGzqvZKiwjIk.jpg',
    '2025-08-22 06:11:46',
    '2025-08-22 06:11:46'
  ),
  (
    42,
    42,
    NULL,
    'berkas_pendaftaran/bwKbQLs04xFduhzeMfWHreb7TzghGokhWzkN5s3w.jpg',
    'berkas_pendaftaran/TXWS5sc2aJX92DUFK11RfvNg3fTSzy7NYlkFrdi0.jpg',
    'berkas_pendaftaran/6B1efcEwVXaejrBrkvQWyhBV0U8iubXfmnCzHMZK.jpg',
    'berkas_pendaftaran/cNZzUeMPjcs5tGVW7JZShGN4KqjDUi5ohIwnOfYv.jpg',
    'berkas_pendaftaran/Iqk6B8bh9Y3a99E6EelgFuq0cqUsac6VH1LTx8mk.jpg',
    '2025-08-22 06:50:04',
    '2025-08-22 06:50:04'
  ),
  (
    43,
    43,
    NULL,
    'berkas_pendaftaran/zFhTIAzHbNyfwLf4oPhgw2THRiRj3StY2oSm4UrL.jpg',
    'berkas_pendaftaran/rOL7aOBJGvWCNk0Zp3N2dpDDriSCtgWC145gHyIw.jpg',
    'berkas_pendaftaran/60KwNQCrVMUBOXinzA52jVeksl9n79vKpbK5B9kq.jpg',
    'berkas_pendaftaran/vRjSHwdGxTmPWQb4D6YvAalvI1fiuUYGQS2PYsW1.jpg',
    'berkas_pendaftaran/fyDQgD3yTQptvXsu3TbPzekItmmbbqBE6QzfnP4S.jpg',
    '2025-08-22 07:00:55',
    '2025-08-22 07:00:55'
  ),
  (
    44,
    44,
    NULL,
    'berkas_pendaftaran/qxFhZ7rXwgZpO9ovr6J0VDRxzyx4fNEhLTt6Iwy3.pdf',
    'berkas_pendaftaran/bv6JGaQrI53HaEEOf6oWDIzZfpst4hJfFTc5KxOf.pdf',
    'berkas_pendaftaran/jsCH9hiUBO8aiwGgYA7amUIbHHhuXZF3NxYBepe9.pdf',
    'berkas_pendaftaran/4kK81RD5VzzGrzkFCGIDp9IGlLzMLcBtDMgy5Dfp.pdf',
    'berkas_pendaftaran/aknZUt0HHpR6BeGwoG2YiAdtkP0uZoqX1QcfyV6W.jpg',
    '2025-08-22 07:03:44',
    '2025-08-22 07:03:44'
  ),
  (
    45,
    45,
    NULL,
    'berkas_pendaftaran/MfzmzSpRO5C7OWROeDxpzwNW2k4jXzViQDHMkBrW.jpg',
    'berkas_pendaftaran/IKI3Pgkt1LHE34iDDd7BSwaGzSRB4v1kzDz8r03g.jpg',
    'berkas_pendaftaran/Y7KEXZNYzCGhU3Zwty7nOystJNwPPCbMz5waOHKL.png',
    'berkas_pendaftaran/DlNd7S6HRAVS5OkOUnUAtsBZh8vLQafJn7tXT0Mn.png',
    'berkas_pendaftaran/cqRzx429PquHm0fUHW3zVPiLQXYfQFFhQHUlsYJt.png',
    '2025-08-22 07:40:52',
    '2025-08-22 07:40:52'
  ),
  (
    46,
    46,
    NULL,
    'berkas_pendaftaran/6IrI4C54EAUBWYWZvGHPjoP9GMfNyce6GhWfhp8A.jpg',
    'berkas_pendaftaran/JCkakLo9Bs47qYRtjCcRZdvh8Ca4BGnEfUOuFa8u.jpg',
    'berkas_pendaftaran/5ZqIk9yTG19GEl3lAIDCv8xOboXKWfQ9XRLQvkMz.pdf',
    'berkas_pendaftaran/0M7eXvwUKioTi8DUG5FNhh7XJM1dSxH04JP1g1Gu.pdf',
    'berkas_pendaftaran/z2HRbXQV1VWguzEYmyfChLgFd6veY2vUlkTNAYPQ.jpg',
    '2025-08-22 07:53:55',
    '2025-08-22 07:53:55'
  ),
  (
    47,
    47,
    NULL,
    'berkas_pendaftaran/04DbT3JFCazyBwm3Y7ufT6uLgJVLEeSWN3XnCI9D.jpg',
    'berkas_pendaftaran/zYGMC9jaSPPGVGiec0Je6lHeubSkuUj1o2a0A69d.jpg',
    'berkas_pendaftaran/mpRKkUSadjWa0AJdX6dIy7LVnBSyVYwHeIkGelSC.jpg',
    'berkas_pendaftaran/61zteKjFKVmvleKvdlpGOyIXIRFbUp0TpRtwdhC6.jpg',
    'berkas_pendaftaran/ZdhHQCre0m1Lzr9l8bJFDn21Oquqsv2SE5JTALXp.jpg',
    '2025-08-22 07:58:16',
    '2025-08-22 07:58:16'
  ),
  (
    48,
    48,
    NULL,
    'berkas_pendaftaran/AJE8BaOgGskaOX9Sr7H8HxohPsJQUpLad1cTvnHG.jpg',
    'berkas_pendaftaran/JbCQVj7PBBiDk2zRN5TtDQQ4AbJt80fgklq5GUia.jpg',
    'berkas_pendaftaran/z0uwoagegYJB7whKl5E5IZTcj5t2VxBOBvk2321a.jpg',
    'berkas_pendaftaran/hrbq050eV3Taa32f3yxQALdWKlrlWHybVjGgyY7q.jpg',
    'berkas_pendaftaran/koqZXVDHFY4ZNKSyz0IcJGf0ntUGHAnqEYCFK5mA.png',
    '2025-08-22 09:32:46',
    '2025-08-22 09:32:46'
  ),
  (
    49,
    49,
    NULL,
    'berkas_pendaftaran/9fQVf4NsmdvUP9oyi7Hz0rSS7YOpdsdZFcAShy1s.jpg',
    'berkas_pendaftaran/T0ZusrIsODow7HqwHaUoVWXlvTpQTMoIDNsHKFnf.jpg',
    'berkas_pendaftaran/IPthDaLgSuM8o7yZh24dFwl1uGYGy7FwtqUm57RY.pdf',
    'berkas_pendaftaran/GucZbwMNxXxdHWI7JvTCuMltBE3UscJVzg6C43l1.jpg',
    'berkas_pendaftaran/tlHhI6AXJrSPmWR146ksiBeWZKTJdYOUUvMiFAPc.jpg',
    '2025-08-22 11:13:03',
    '2025-08-22 11:13:03'
  ),
  (
    50,
    50,
    NULL,
    'berkas_pendaftaran/hpA9L6sUVOOOH8fKRKUv59ztlfmds1bvUWwPUsnC.jpg',
    'berkas_pendaftaran/aEqB2t7wEQE0sMSdZvCnCITi51zBPvACEGUdJE9m.jpg',
    'berkas_pendaftaran/mtco7Mxws0tj68fbhe4R6M0ah7ov4V96uBHv6Y22.jpg',
    'berkas_pendaftaran/0eWxsoxcQ2q68nxwElgt4sTUQAIH2t4qH56NfQov.jpg',
    'berkas_pendaftaran/wq9X52B0haTrEJZR0IKaBKanklxGy5lA6ye3KjHO.png',
    '2025-08-22 11:45:00',
    '2025-08-22 11:45:00'
  ),
  (
    51,
    51,
    NULL,
    'berkas_pendaftaran/rX6xsikfnEAE8M4XiI66dxe4DysFLFgNkp7pa5rh.jpg',
    'berkas_pendaftaran/7pjoXadFE9haWx4TNRydrEm1SQ2GIQYwGGCptoOI.jpg',
    'berkas_pendaftaran/8vyBLngHb6UazM5pgbjfTESQfun5LARf2xTgorhM.pdf',
    'berkas_pendaftaran/etzPQnHYyFGlqFRcXNf6kczonESqLFrUyrEh9wWb.jpg',
    'berkas_pendaftaran/VIjwLJkbA5PMdSNn9ncNSypduWY9RfsNUeoBKWNc.jpg',
    '2025-08-22 12:16:46',
    '2025-08-22 12:16:46'
  ),
  (
    52,
    52,
    NULL,
    'berkas_pendaftaran/0CkV5fMkryv4wMnCXev6U2et0Mu6k2hBfNAPOTXH.jpg',
    'berkas_pendaftaran/NiGJg2G1HascFoNYCkuLmnJvWfEpgWE6WsjytiXJ.pdf',
    'berkas_pendaftaran/tAHk8XWooyhPCqgI0gxOdJun12Lm9j36HlQ8DCy5.pdf',
    'berkas_pendaftaran/NAd3DiZkNyowtMZeTtGjesiR9mY6Bji1Sb9RprNZ.jpg',
    'berkas_pendaftaran/zoQMdme63occuQT2znJZKbSoVAfyHkA4hSe4g6Rc.jpg',
    '2025-08-22 12:22:18',
    '2025-08-22 12:22:18'
  ),
  (
    53,
    53,
    NULL,
    'berkas_pendaftaran/KtK1zu4POxrRF4mUUJ14QmdOLbhTgg6v4wKvg9y5.jpg',
    'berkas_pendaftaran/K46gUdMbWcZ6CfbDlMSCX9nGSYRDDQlacMQtxPQ3.jpg',
    'berkas_pendaftaran/Dnkqqs2vYLmFtO1UPCOmO1dMWTasnhPGiAjhe7yh.jpg',
    'berkas_pendaftaran/5e6VjeiWcq0GTHpSoPh49p9RYvZm7GfaxkvAaR0c.jpg',
    'berkas_pendaftaran/wibIDA71ECGJDChTKsUULX1OCHpnZEVQQvx8zjHS.png',
    '2025-08-22 13:14:10',
    '2025-08-22 13:14:10'
  ),
  (
    54,
    54,
    NULL,
    'berkas_pendaftaran/5dnuQ4inp73qkxRJlJoeibAfABbX6M06MolmQoNf.pdf',
    'berkas_pendaftaran/AEUs1xEA10F9q4FAol5iYYtKAMhZI0J9SikxNUTT.pdf',
    'berkas_pendaftaran/U8KwDalFO8v3tcTPQggxHmToE8NBjHWALxblKeEJ.pdf',
    'berkas_pendaftaran/4TufSx4SHMxhFBNZV46KdRSIc8IT2MQnUn1eacd0.jpg',
    'berkas_pendaftaran/mb4RDnYTKpBYBxOxKbCTsh7OMPkHrJhtG5rtg0Q8.jpg',
    '2025-08-22 13:34:42',
    '2025-08-22 13:34:42'
  ),
  (
    55,
    55,
    NULL,
    'berkas_pendaftaran/PxFTZFZNfc9CuvDblnvmaWWFXfu31bS0wLhLyCuQ.pdf',
    'berkas_pendaftaran/uejzoELBlNpP330yvXnjAw5hptkCxeZPAhDnt5cm.pdf',
    'berkas_pendaftaran/dophKBifmilYTxvtgu3lgxh3oNbqmNxFi5VbppxT.pdf',
    'berkas_pendaftaran/tUvI1giU53Cs5HpUkvtBctewXkjvBbSppSPzt4Xg.pdf',
    'berkas_pendaftaran/n6TBEYs8Llzx4BMyzbrqnjJnHk0dvUIu3ZDrt6jk.jpg',
    '2025-08-22 13:39:15',
    '2025-08-22 13:39:15'
  ),
  (
    56,
    56,
    NULL,
    'berkas_pendaftaran/LEFlgnKKmMUaz8jak7wqeap0Pno9f7aSvvKqGMid.jpg',
    'berkas_pendaftaran/A8dRcTxtRc9gvHqQu0AulwRKemFKNKgaMXQyOOJX.jpg',
    'berkas_pendaftaran/z7RhPvWWRZuZnOX84Y4qtOO1ChfaNzREaeWquCOk.jpg',
    'berkas_pendaftaran/0aepAYPqfGOd9XrnGhkXXzkWeuitOLD5JMEnbUHD.jpg',
    'berkas_pendaftaran/gt0TNPAXCS2CmcBlWQLOn4xLmXBXiVl6kwSAZdQF.jpg',
    '2025-08-22 13:41:46',
    '2025-08-22 13:41:46'
  ),
  (
    57,
    57,
    NULL,
    'berkas_pendaftaran/nFXm5BfHvZi02kwWbuxQpW2SHfam5eOi3W52saew.jpg',
    'berkas_pendaftaran/65sU2GcEvoU1Img1z2ixnCjkeqyyxptffNs8cB02.jpg',
    'berkas_pendaftaran/7iAyX3U8UxLxmQH9OzGnLnhIwNkUhV0eUODu8PKk.pdf',
    'berkas_pendaftaran/Jwqy77QjWffdrwOLlOyn6DHkQ167X1ooXmDCSPwu.jpg',
    'berkas_pendaftaran/csf6WIMkLxYX8S74EpT9xM6bPsoRWgfSUuB3VxdZ.jpg',
    '2025-08-22 13:42:27',
    '2025-08-22 13:42:27'
  ),
  (
    58,
    58,
    NULL,
    'berkas_pendaftaran/zUrdkZO0EywqFMQE9ktEgcDI1Hw7rZvJBLNT8ePb.pdf',
    'berkas_pendaftaran/r9nVL3IGneWTch2fxxNlxqppx6orW1Ww7dFeUAHb.pdf',
    'berkas_pendaftaran/qhso1gYSsegHUiu6QgOAF9UXWNWtXs3ETmVjvCKa.pdf',
    'berkas_pendaftaran/OjjCjLbbCOpsmZUSbeRaGCCrHEvWVrSuiYaonlAD.pdf',
    'berkas_pendaftaran/MDXGUIItGrV4TCD4ZHTV1bz4PoIdd0u4hjkTxJzo.jpg',
    '2025-08-23 09:23:46',
    '2025-08-23 09:23:46'
  ),
  (
    59,
    59,
    NULL,
    'berkas_pendaftaran/5Q8RAvrT0TPmkzFI0s4vnQDGgNWXqalSMnEx5Vev.jpg',
    'berkas_pendaftaran/pudyqbzzECOt0jm0nAMYJRTI3wIPNkIb7ETCnN9y.jpg',
    'berkas_pendaftaran/gA1GDtckYor1g5slXBDJURGX1QqlHzIsYssUWW63.jpg',
    'berkas_pendaftaran/f1WMCvPHi3240dqUmAfKPVsUVPx9rX8FdM3cc4pW.jpg',
    'berkas_pendaftaran/W39yAuyLvdooifm7GkM0FjAXA16Ufa3fnuBpSuzo.jpg',
    '2025-08-23 14:19:14',
    '2025-08-23 14:19:14'
  ),
  (
    60,
    60,
    NULL,
    'berkas_pendaftaran/60_3_68_fc_ktp.jpg',
    'berkas_pendaftaran/60_3_68_fc_ijazah.jpg',
    'berkas_pendaftaran/60_3_68_fc_surat_tugas.jpg',
    'berkas_pendaftaran/60_3_68_fc_surat_sehat.jpg',
    'berkas_pendaftaran/60_3_68_pas_foto.jpg',
    '2025-08-29 11:40:45',
    '2025-08-29 11:40:45'
  );



-- jawaban user
INSERT INTO
  `jawaban_users` (
    `id`,
    `opsi_jawabans_id`,
    `pertanyaan_id`,
    `percobaan_id`,
    `nilai_jawaban`,
    `jawaban_teks`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    37,
    344,
    81,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    38,
    344,
    82,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    39,
    344,
    83,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    40,
    344,
    84,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    41,
    344,
    85,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    42,
    344,
    86,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    43,
    344,
    87,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    44,
    344,
    88,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    45,
    344,
    89,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    46,
    344,
    90,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    47,
    344,
    91,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    48,
    344,
    92,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    49,
    344,
    93,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    50,
    344,
    94,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    51,
    344,
    95,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    52,
    NULL,
    96,
    2,
    NULL,
    'Terimakasih banyak',
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    53,
    344,
    97,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    54,
    348,
    98,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    55,
    344,
    99,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    56,
    344,
    100,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    57,
    344,
    101,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    58,
    344,
    102,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    59,
    352,
    103,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    60,
    354,
    104,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    61,
    356,
    105,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    62,
    360,
    106,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    63,
    NULL,
    107,
    2,
    NULL,
    'Terimakasih banyak',
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    64,
    344,
    108,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    65,
    364,
    109,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    66,
    344,
    110,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    67,
    344,
    111,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    68,
    368,
    112,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    69,
    372,
    113,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    70,
    344,
    114,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    71,
    344,
    115,
    2,
    NULL,
    NULL,
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    72,
    NULL,
    116,
    2,
    NULL,
    'Terimakasih banyak semuanya',
    '2025-08-29 07:44:17',
    '2025-08-29 07:44:17'
  ),
  (
    73,
    343,
    81,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    74,
    344,
    82,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    75,
    344,
    83,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    76,
    343,
    84,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    77,
    343,
    85,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    78,
    343,
    86,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    79,
    343,
    87,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    80,
    343,
    88,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    81,
    343,
    89,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    82,
    343,
    90,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    83,
    343,
    91,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    84,
    343,
    92,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    85,
    343,
    93,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    86,
    343,
    94,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    87,
    343,
    95,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    88,
    NULL,
    96,
    3,
    NULL,
    'saya sangat berterima kasih kepada guru\' yang telah membimbing saya selama di UPT PTKK',
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    89,
    343,
    97,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    90,
    348,
    98,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    91,
    343,
    99,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    92,
    343,
    100,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    93,
    343,
    101,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    94,
    343,
    102,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    95,
    351,
    103,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    96,
    355,
    104,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    97,
    354,
    105,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    98,
    360,
    106,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    99,
    NULL,
    107,
    3,
    NULL,
    'di UPT PTKK sangat nyaman dari lingkungan dan teman\' di sini sangat baik\'',
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    100,
    343,
    108,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    101,
    364,
    109,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    102,
    343,
    110,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    103,
    343,
    111,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    104,
    367,
    112,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    105,
    371,
    113,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    106,
    343,
    114,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    107,
    343,
    115,
    3,
    NULL,
    NULL,
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    108,
    NULL,
    116,
    3,
    NULL,
    'semoga di tahun\' selanjutnya saya bisa kesini lagi(UPT PTKK)',
    '2025-08-29 07:46:23',
    '2025-08-29 07:46:23'
  ),
  (
    109,
    344,
    81,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    110,
    344,
    82,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    111,
    344,
    83,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    112,
    344,
    84,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    113,
    344,
    85,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    114,
    343,
    86,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    115,
    344,
    87,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    116,
    343,
    88,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    117,
    344,
    89,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    118,
    344,
    90,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    119,
    343,
    91,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    120,
    344,
    92,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    121,
    344,
    93,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    122,
    344,
    94,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    123,
    344,
    95,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    124,
    NULL,
    96,
    4,
    NULL,
    'Tempatnya nyaman, bersih dan luas, bikin betah. Makanannya juga enak enak 👍',
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    125,
    344,
    97,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    126,
    348,
    98,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    127,
    344,
    99,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    128,
    344,
    100,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    129,
    344,
    101,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    130,
    344,
    102,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    131,
    351,
    103,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    132,
    355,
    104,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    133,
    354,
    105,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    134,
    360,
    106,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    135,
    NULL,
    107,
    4,
    NULL,
    'Guru guru sangat sabar dan selalu membantu, keren. Mesin dan lainnya lengkap banget, nggak espek.',
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    136,
    344,
    108,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    137,
    364,
    109,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    138,
    344,
    110,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    139,
    344,
    111,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    140,
    368,
    112,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    141,
    372,
    113,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    142,
    344,
    114,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    143,
    344,
    115,
    4,
    NULL,
    NULL,
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    144,
    NULL,
    116,
    4,
    NULL,
    'Guru guru baik bangett, salut. Sehat sehat pengurus❤️',
    '2025-08-29 07:50:30',
    '2025-08-29 07:50:30'
  ),
  (
    145,
    344,
    81,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    146,
    344,
    82,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    147,
    344,
    83,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    148,
    344,
    84,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    149,
    344,
    85,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    150,
    344,
    86,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    151,
    344,
    87,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    152,
    344,
    88,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    153,
    344,
    89,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    154,
    344,
    90,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    155,
    344,
    91,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    156,
    344,
    92,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    157,
    344,
    93,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    158,
    344,
    94,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    159,
    344,
    95,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    160,
    NULL,
    96,
    5,
    NULL,
    'kesannya jujur betah banget kalo disini, temen\" nya juga asik, gurunya juga asik. pesan nya aku kasih applause buat semuany',
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    161,
    343,
    97,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    162,
    348,
    98,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    163,
    344,
    99,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    164,
    343,
    100,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    165,
    343,
    101,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    166,
    344,
    102,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    167,
    351,
    103,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    168,
    356,
    104,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    169,
    353,
    105,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    170,
    360,
    106,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    171,
    NULL,
    107,
    5,
    NULL,
    'peralatan dan kesediannya oke poll besstt',
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    172,
    343,
    108,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    173,
    364,
    109,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    174,
    344,
    110,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    175,
    344,
    111,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    176,
    368,
    112,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    177,
    372,
    113,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    178,
    344,
    114,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    179,
    344,
    115,
    5,
    NULL,
    NULL,
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    180,
    NULL,
    116,
    5,
    NULL,
    'instrukturnya sih asikk banget, tegasss \r\nbahasanya lembut dan asikk bisa ngejokes jg \r\nterimakasih banyaakk buat instruktur yang sudah membantu',
    '2025-08-29 07:54:30',
    '2025-08-29 07:54:30'
  ),
  (
    181,
    344,
    81,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    182,
    344,
    82,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    183,
    344,
    83,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    184,
    344,
    84,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    185,
    344,
    85,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    186,
    344,
    86,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    187,
    344,
    87,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    188,
    344,
    88,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    189,
    344,
    89,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    190,
    344,
    90,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    191,
    344,
    91,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    192,
    343,
    92,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    193,
    344,
    93,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    194,
    344,
    94,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    195,
    344,
    95,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    196,
    NULL,
    96,
    6,
    NULL,
    'sangat senang sekali di sini bertemu dengan teman baru dan ibu/bapak guru pendamping sangat ramah,fasilitas sangat memuaskan',
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    197,
    343,
    97,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    198,
    348,
    98,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    199,
    344,
    99,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    200,
    344,
    100,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    201,
    344,
    101,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    202,
    344,
    102,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    203,
    352,
    103,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    204,
    355,
    104,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    205,
    355,
    105,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    206,
    360,
    106,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    207,
    NULL,
    107,
    6,
    NULL,
    'terimakasih banyak adanya pelatihan ini saya lebih semangat',
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    208,
    344,
    108,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    209,
    363,
    109,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    210,
    344,
    110,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    211,
    344,
    111,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    212,
    368,
    112,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    213,
    372,
    113,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    214,
    344,
    114,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    215,
    344,
    115,
    6,
    NULL,
    NULL,
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    216,
    NULL,
    116,
    6,
    NULL,
    'terimakasih banyakk',
    '2025-08-29 07:54:53',
    '2025-08-29 07:54:53'
  ),
  (
    217,
    343,
    81,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    218,
    344,
    82,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    219,
    343,
    83,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    220,
    343,
    84,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    221,
    343,
    85,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    222,
    343,
    86,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    223,
    343,
    87,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    224,
    343,
    88,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    225,
    344,
    89,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    226,
    344,
    90,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    227,
    343,
    91,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    228,
    344,
    92,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    229,
    343,
    93,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    230,
    343,
    94,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    231,
    344,
    95,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    232,
    NULL,
    96,
    7,
    NULL,
    'kebersihan disini sangat baguss🥰',
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    233,
    342,
    97,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    234,
    348,
    98,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    235,
    344,
    99,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    236,
    343,
    100,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    237,
    343,
    101,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    238,
    343,
    102,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    239,
    351,
    103,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    240,
    354,
    104,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    241,
    354,
    105,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    242,
    359,
    106,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    243,
    NULL,
    107,
    7,
    NULL,
    'banyak hal baru yang didapat dari pelatian ini🥰',
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    244,
    343,
    108,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    245,
    364,
    109,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    246,
    343,
    110,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    247,
    343,
    111,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    248,
    367,
    112,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    249,
    372,
    113,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    250,
    343,
    114,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    251,
    343,
    115,
    7,
    NULL,
    NULL,
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    252,
    NULL,
    116,
    7,
    NULL,
    'terimakasih banyakk instruktur sangatt baikk🥰😍😍🥰🥰🥰😍😍🥰🥰',
    '2025-08-29 07:57:08',
    '2025-08-29 07:57:08'
  ),
  (
    253,
    344,
    81,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    254,
    343,
    82,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    255,
    343,
    83,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    256,
    344,
    84,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    257,
    344,
    85,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    258,
    343,
    86,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    259,
    343,
    87,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    260,
    343,
    88,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    261,
    343,
    89,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    262,
    343,
    90,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    263,
    343,
    91,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    264,
    343,
    92,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    265,
    343,
    93,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    266,
    343,
    94,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    267,
    343,
    95,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    268,
    NULL,
    96,
    8,
    NULL,
    'Memuaskan ,dengan pelayanan yang ramah',
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    269,
    342,
    97,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    270,
    348,
    98,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    271,
    344,
    99,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    272,
    344,
    100,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    273,
    344,
    101,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    274,
    343,
    102,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    275,
    351,
    103,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    276,
    355,
    104,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    277,
    355,
    105,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    278,
    360,
    106,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    279,
    NULL,
    107,
    8,
    NULL,
    'Senang mengikuti pelatihan',
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    280,
    343,
    108,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    281,
    364,
    109,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    282,
    344,
    110,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    283,
    344,
    111,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    284,
    368,
    112,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    285,
    372,
    113,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    286,
    343,
    114,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    287,
    344,
    115,
    8,
    NULL,
    NULL,
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    288,
    NULL,
    116,
    8,
    NULL,
    'Di ajari dengan baik dan sabar',
    '2025-08-29 07:57:49',
    '2025-08-29 07:57:49'
  ),
  (
    289,
    344,
    81,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    290,
    344,
    82,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    291,
    343,
    83,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    292,
    343,
    84,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    293,
    344,
    85,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    294,
    344,
    86,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    295,
    343,
    87,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    296,
    344,
    88,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    297,
    342,
    89,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    298,
    344,
    90,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    299,
    344,
    91,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    300,
    344,
    92,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    301,
    344,
    93,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    302,
    344,
    94,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    303,
    344,
    95,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    304,
    NULL,
    96,
    9,
    NULL,
    'pelayanan oke',
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    305,
    344,
    97,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    306,
    348,
    98,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    307,
    344,
    99,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    308,
    344,
    100,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    309,
    344,
    101,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    310,
    344,
    102,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    311,
    352,
    103,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    312,
    355,
    104,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    313,
    353,
    105,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    314,
    360,
    106,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    315,
    NULL,
    107,
    9,
    NULL,
    'semoga UPT PTKK JATIM semakin maju',
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    316,
    344,
    108,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    317,
    364,
    109,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    318,
    344,
    110,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    319,
    344,
    111,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    320,
    368,
    112,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    321,
    372,
    113,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    322,
    344,
    114,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    323,
    344,
    115,
    9,
    NULL,
    NULL,
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    324,
    NULL,
    116,
    9,
    NULL,
    'semoga semakin berkembang',
    '2025-08-29 08:04:29',
    '2025-08-29 08:04:29'
  ),
  (
    325,
    344,
    81,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    326,
    344,
    82,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    327,
    344,
    83,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    328,
    344,
    84,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    329,
    343,
    85,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    330,
    344,
    86,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    331,
    344,
    87,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    332,
    344,
    88,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    333,
    344,
    89,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    334,
    344,
    90,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    335,
    342,
    91,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    336,
    344,
    92,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    337,
    344,
    93,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    338,
    344,
    94,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    339,
    344,
    95,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    340,
    NULL,
    96,
    10,
    NULL,
    'sangat menyenangkan',
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    341,
    344,
    97,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    342,
    348,
    98,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    343,
    344,
    99,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    344,
    344,
    100,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    345,
    344,
    101,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    346,
    344,
    102,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    347,
    351,
    103,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    348,
    355,
    104,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    349,
    353,
    105,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    350,
    360,
    106,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    351,
    NULL,
    107,
    10,
    NULL,
    'para pembimbing sangat menyenangkan dan mudah dipahami saat memberikan materi',
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    352,
    344,
    108,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    353,
    363,
    109,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    354,
    344,
    110,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    355,
    344,
    111,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    356,
    372,
    113,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    357,
    344,
    114,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    358,
    344,
    115,
    10,
    NULL,
    NULL,
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    359,
    NULL,
    116,
    10,
    NULL,
    'sangatt baik sekaliii',
    '2025-08-29 08:07:46',
    '2025-08-29 08:07:46'
  ),
  (
    360,
    344,
    81,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    361,
    344,
    82,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    362,
    344,
    83,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    363,
    344,
    84,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    364,
    344,
    85,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    365,
    344,
    86,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    366,
    344,
    87,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    367,
    344,
    88,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    368,
    344,
    89,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    369,
    344,
    90,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    370,
    344,
    91,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    371,
    344,
    92,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    372,
    344,
    93,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    373,
    344,
    94,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    374,
    344,
    95,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    375,
    NULL,
    96,
    11,
    NULL,
    'sangatt memuaskan dan ramah \" serta besih ,Semoga kualitas pelayanan yang baik ini tetap dipertahankan, bahkan bisa lebih ditingkatkan lagi agar semakin banyak orang merasakan kenyamanan yang sama.',
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    376,
    344,
    97,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    377,
    348,
    98,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    378,
    344,
    99,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    379,
    344,
    100,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    380,
    344,
    101,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    381,
    344,
    102,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    382,
    351,
    103,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    383,
    355,
    104,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    384,
    353,
    105,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    385,
    360,
    106,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    386,
    NULL,
    107,
    11,
    NULL,
    'guru \" nya baik dan kalau menerangkan materi bisa di pahami 🫶🏻',
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    387,
    344,
    108,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    388,
    364,
    109,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    389,
    344,
    110,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    390,
    344,
    111,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    391,
    368,
    112,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    392,
    372,
    113,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    393,
    344,
    114,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    394,
    344,
    115,
    11,
    NULL,
    NULL,
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    395,
    NULL,
    116,
    11,
    NULL,
    'orang nya ramah \" ,dan saya berterimakasih sekali sudah di ajarkan dan bisa menjadi seperti ini ,mendapatkan banyak pengalaman 🫶🏻',
    '2025-08-29 08:24:58',
    '2025-08-29 08:24:58'
  ),
  (
    396,
    344,
    81,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    397,
    343,
    82,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    398,
    344,
    83,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    399,
    344,
    84,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    400,
    343,
    85,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    401,
    344,
    86,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    402,
    344,
    87,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    403,
    344,
    88,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    404,
    344,
    89,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    405,
    344,
    90,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    406,
    344,
    91,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    407,
    344,
    92,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    408,
    344,
    93,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    409,
    344,
    94,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    410,
    344,
    95,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    411,
    NULL,
    96,
    12,
    NULL,
    'saya sangat senang dengan pelayanan di sini, di sini saya mendapatkan pelayanan yang baik dan sangat memuaskan',
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    412,
    344,
    97,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    413,
    348,
    98,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    414,
    344,
    99,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    415,
    344,
    100,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    416,
    343,
    101,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    417,
    343,
    102,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    418,
    351,
    103,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    419,
    355,
    104,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    420,
    353,
    105,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    421,
    360,
    106,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    422,
    NULL,
    107,
    12,
    NULL,
    'saya sangat senang dan puas dengan diadakannya pelatihan kompetensi ini, karena saya meningkatkan kemampuan saya, saya mendapatkan ilmu baru dan teman baru.',
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    423,
    344,
    108,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    424,
    364,
    109,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    425,
    344,
    110,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    426,
    344,
    111,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    427,
    368,
    112,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    428,
    372,
    113,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    429,
    344,
    114,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    430,
    344,
    115,
    12,
    NULL,
    NULL,
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    431,
    NULL,
    116,
    12,
    NULL,
    'saya sangat suka dan sangat kagum kepada instruktur, karena dapat memberikan materi dengan baik, dan dapat membantu saya dalam menyelesaikan dengan baik',
    '2025-08-29 08:25:46',
    '2025-08-29 08:25:46'
  ),
  (
    432,
    344,
    81,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    433,
    343,
    82,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    434,
    343,
    83,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    435,
    344,
    84,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    436,
    343,
    85,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    437,
    343,
    86,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    438,
    344,
    87,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    439,
    342,
    89,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    440,
    343,
    90,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    441,
    343,
    92,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    442,
    343,
    93,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    443,
    344,
    94,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    444,
    344,
    95,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    445,
    NULL,
    96,
    13,
    NULL,
    'saya senang dngn pelayanannya ramah',
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    446,
    342,
    97,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    447,
    348,
    98,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    448,
    344,
    99,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    449,
    343,
    100,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    450,
    343,
    101,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    451,
    342,
    102,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    452,
    351,
    103,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    453,
    355,
    104,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    454,
    354,
    105,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    455,
    358,
    106,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    456,
    NULL,
    107,
    13,
    NULL,
    'suka karna bisa mendapatkan pengalaman baru',
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    457,
    342,
    108,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    458,
    363,
    109,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    459,
    342,
    110,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    460,
    343,
    111,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    461,
    367,
    112,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    462,
    372,
    113,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    463,
    343,
    114,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    464,
    343,
    115,
    13,
    NULL,
    NULL,
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    465,
    NULL,
    116,
    13,
    NULL,
    'saya suka sama instruktur karna bisa membimbing yang baik.',
    '2025-08-29 08:40:23',
    '2025-08-29 08:40:23'
  ),
  (
    466,
    343,
    81,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    467,
    344,
    82,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    468,
    344,
    83,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    469,
    343,
    84,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    470,
    344,
    85,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    471,
    343,
    86,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    472,
    343,
    87,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    473,
    343,
    88,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    474,
    343,
    89,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    475,
    343,
    90,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    476,
    343,
    91,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    477,
    344,
    92,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    478,
    344,
    93,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    479,
    344,
    94,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    480,
    344,
    95,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    481,
    NULL,
    96,
    14,
    NULL,
    'Tempat nya sangat nyaman',
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    482,
    343,
    97,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    483,
    348,
    98,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    484,
    344,
    99,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    485,
    344,
    100,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    486,
    344,
    101,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    487,
    344,
    102,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    488,
    350,
    103,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    489,
    356,
    104,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    490,
    353,
    105,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    491,
    359,
    106,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    492,
    NULL,
    107,
    14,
    NULL,
    'Pelatihan nya sangat baik dan nyaman',
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    493,
    343,
    108,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    494,
    364,
    109,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    495,
    344,
    110,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    496,
    344,
    111,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    497,
    367,
    112,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    498,
    371,
    113,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    499,
    344,
    114,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    500,
    344,
    115,
    14,
    NULL,
    NULL,
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    501,
    NULL,
    116,
    14,
    NULL,
    'Pelajaran yang saya dapat sangat memuaskan bagi saya sendiri.....',
    '2025-08-29 08:44:03',
    '2025-08-29 08:44:03'
  ),
  (
    502,
    344,
    81,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    503,
    344,
    82,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    504,
    344,
    83,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    505,
    344,
    84,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    506,
    344,
    85,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    507,
    344,
    86,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    508,
    344,
    87,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    509,
    344,
    88,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    510,
    344,
    89,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    511,
    344,
    90,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    512,
    344,
    91,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    513,
    344,
    92,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    514,
    344,
    93,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    515,
    344,
    94,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    516,
    344,
    95,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    517,
    NULL,
    96,
    15,
    NULL,
    'Nyamuk di asrama sangat banyak itu saja kesan pelayanan memuaskan dan kebersihan',
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    518,
    344,
    97,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    519,
    348,
    98,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    520,
    344,
    99,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    521,
    344,
    100,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    522,
    344,
    101,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    523,
    344,
    102,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    524,
    352,
    103,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    525,
    356,
    104,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    526,
    353,
    105,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    527,
    360,
    106,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    528,
    NULL,
    107,
    15,
    NULL,
    'sangat bermanfaat bagi masa depan yang akan datang seperti dunia kerja',
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    529,
    344,
    108,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    530,
    364,
    109,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    531,
    344,
    110,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    532,
    344,
    111,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    533,
    368,
    112,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    534,
    372,
    113,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    535,
    344,
    114,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    536,
    344,
    115,
    15,
    NULL,
    NULL,
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    537,
    NULL,
    116,
    15,
    NULL,
    'instruktur sangat baik kita bisa belajar tenteng kebersiha  ketepatan waktu dll yang masik banyak yang kita serap dari instruktur',
    '2025-08-29 08:44:26',
    '2025-08-29 08:44:26'
  ),
  (
    538,
    344,
    81,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    539,
    344,
    82,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    540,
    343,
    83,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    541,
    344,
    84,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    542,
    344,
    85,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    543,
    343,
    86,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    544,
    344,
    87,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    545,
    343,
    88,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    546,
    344,
    89,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    547,
    344,
    90,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    548,
    344,
    91,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    549,
    344,
    92,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    550,
    344,
    93,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    551,
    344,
    94,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    552,
    344,
    95,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    553,
    NULL,
    96,
    16,
    NULL,
    'pesan nya:banyak tapi hanya satu,tetap ber konsisten saat mengadakan acara seperti ini lagi,dipertahankan dan semangat untuk guru-guru semuanya dan panita penyelenggara.\r\nkesan:seperti tidak ingin pulang,karena lingkungan nya sangat cocok untuk diri saya.',
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    554,
    343,
    97,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    555,
    348,
    98,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    556,
    344,
    99,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    557,
    344,
    100,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    558,
    343,
    101,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    559,
    344,
    102,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    560,
    352,
    103,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    561,
    356,
    104,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    562,
    353,
    105,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    563,
    360,
    106,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    564,
    NULL,
    107,
    16,
    NULL,
    'saat penyampaian materi sangat jelas dan rinci,bermanfaat bagi saya.\r\nkesan nya ingin tinggal lagi disini.',
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    565,
    343,
    108,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    566,
    364,
    109,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    567,
    344,
    110,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    568,
    344,
    111,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    569,
    368,
    112,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    570,
    372,
    113,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    571,
    343,
    114,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    572,
    343,
    115,
    16,
    NULL,
    NULL,
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    573,
    NULL,
    116,
    16,
    NULL,
    'instruktur seperti ikhlas menyampaikan ilmu yang dimilikinya kepada siswa-siswi pelatihan di upt pttk unesa',
    '2025-08-29 08:44:48',
    '2025-08-29 08:44:48'
  ),
  (
    574,
    343,
    81,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    575,
    343,
    82,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    576,
    343,
    83,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    577,
    343,
    84,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    578,
    344,
    85,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    579,
    343,
    86,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    580,
    343,
    87,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    581,
    342,
    88,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    582,
    343,
    89,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    583,
    343,
    90,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    584,
    343,
    91,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    585,
    343,
    92,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    586,
    344,
    93,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    587,
    343,
    94,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    588,
    343,
    95,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    589,
    NULL,
    96,
    17,
    NULL,
    'seru',
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    590,
    342,
    97,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    591,
    348,
    98,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    592,
    343,
    99,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    593,
    343,
    100,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    594,
    343,
    101,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    595,
    343,
    102,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    596,
    350,
    103,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    597,
    355,
    104,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    598,
    353,
    105,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    599,
    359,
    106,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    600,
    NULL,
    107,
    17,
    NULL,
    'baik menyenangkan',
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    601,
    343,
    108,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    602,
    363,
    109,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    603,
    344,
    110,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    604,
    344,
    111,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    605,
    368,
    112,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    606,
    372,
    113,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    607,
    344,
    114,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    608,
    343,
    115,
    17,
    NULL,
    NULL,
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    609,
    NULL,
    116,
    17,
    NULL,
    'instruktur nya baik',
    '2025-08-29 08:45:10',
    '2025-08-29 08:45:10'
  ),
  (
    610,
    343,
    81,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    611,
    344,
    82,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    612,
    343,
    83,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    613,
    344,
    84,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    614,
    344,
    85,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    615,
    343,
    86,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    616,
    343,
    87,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    617,
    343,
    88,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    618,
    343,
    89,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    619,
    343,
    90,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    620,
    343,
    91,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    621,
    343,
    92,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    622,
    344,
    93,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    623,
    343,
    94,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    624,
    343,
    95,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    625,
    NULL,
    96,
    18,
    NULL,
    'Saya nyaman saat pelatihan berlangsung 5 hari ini, Thank you.',
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    626,
    343,
    97,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    627,
    348,
    98,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    628,
    344,
    99,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    629,
    344,
    100,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    630,
    343,
    101,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    631,
    343,
    102,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    632,
    351,
    103,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    633,
    355,
    104,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    634,
    355,
    105,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    635,
    360,
    106,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    636,
    NULL,
    107,
    18,
    NULL,
    'Saya suka karena keperluan praktek yang saya butuhkan ada di sini dan cukup lengkap',
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    637,
    343,
    108,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    638,
    363,
    109,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    639,
    344,
    110,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    640,
    343,
    111,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    641,
    367,
    112,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    642,
    371,
    113,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    643,
    343,
    114,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    644,
    344,
    115,
    18,
    NULL,
    NULL,
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    645,
    NULL,
    116,
    18,
    NULL,
    'Instruktur di sini sangat baik dalam menyampaikan materi dan motivasi bagi saya, terimakasih',
    '2025-08-29 08:45:26',
    '2025-08-29 08:45:26'
  ),
  (
    646,
    344,
    81,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  );

INSERT INTO
  `jawaban_users` (
    `id`,
    `opsi_jawabans_id`,
    `pertanyaan_id`,
    `percobaan_id`,
    `nilai_jawaban`,
    `jawaban_teks`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    647,
    343,
    82,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    648,
    343,
    83,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    649,
    344,
    84,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    650,
    344,
    85,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    651,
    344,
    86,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    652,
    344,
    87,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    653,
    344,
    88,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    654,
    343,
    89,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    655,
    344,
    90,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    656,
    344,
    91,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    657,
    344,
    92,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    658,
    344,
    93,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    659,
    344,
    94,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    660,
    344,
    95,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    661,
    NULL,
    96,
    19,
    NULL,
    'Kesan saya disini nyaman , aman, banyak teman, belajar ilmu baruu, dan kesan saya sangatt memuaskann',
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    662,
    344,
    97,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    663,
    347,
    98,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    664,
    344,
    99,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    665,
    344,
    100,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    666,
    343,
    101,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    667,
    344,
    102,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    668,
    351,
    103,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    669,
    355,
    104,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    670,
    353,
    105,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    671,
    360,
    106,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    672,
    NULL,
    107,
    19,
    NULL,
    'Kalau di sini sedikit teori tapi banyak praktek nya , lebih mudah seperti itu dari pada kebanyakan teori kurang praktek',
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    673,
    343,
    108,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    674,
    364,
    109,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    675,
    344,
    110,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    676,
    344,
    111,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    677,
    368,
    112,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    678,
    372,
    113,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    679,
    344,
    114,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    680,
    344,
    115,
    19,
    NULL,
    NULL,
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    681,
    NULL,
    116,
    19,
    NULL,
    'Instruktur nya semua baik dan enak di ajak ngobrol di tanya langsung jawab dan bantu yang belum faham pokok nya joss dehh buatt instruktur nyaaa...',
    '2025-08-29 08:45:32',
    '2025-08-29 08:45:32'
  ),
  (
    682,
    344,
    81,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    683,
    344,
    82,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    684,
    344,
    83,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    685,
    344,
    84,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    686,
    344,
    85,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    687,
    344,
    86,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    688,
    344,
    87,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    689,
    344,
    88,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    690,
    344,
    89,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    691,
    344,
    90,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    692,
    344,
    91,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    693,
    344,
    92,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    694,
    344,
    93,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    695,
    344,
    94,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    696,
    344,
    95,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    697,
    NULL,
    96,
    20,
    NULL,
    'kesan saya saya sangat bangga sekali bisa mengikuti pelatihan di sini dan mendapatkan hal yang sangat baruu',
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    698,
    344,
    97,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    699,
    348,
    98,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    700,
    344,
    99,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    701,
    344,
    100,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    702,
    344,
    101,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    703,
    344,
    102,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    704,
    352,
    103,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    705,
    354,
    104,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    706,
    353,
    105,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    707,
    360,
    106,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    708,
    NULL,
    107,
    20,
    NULL,
    'lebih seneng dengan praktek',
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    709,
    344,
    108,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    710,
    364,
    109,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    711,
    344,
    110,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    712,
    344,
    111,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    713,
    368,
    112,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    714,
    372,
    113,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    715,
    344,
    114,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    716,
    344,
    115,
    20,
    NULL,
    NULL,
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    717,
    NULL,
    116,
    20,
    NULL,
    'terima kasih pada instruktur telah membingbing saya dengan baik',
    '2025-08-29 08:45:41',
    '2025-08-29 08:45:41'
  ),
  (
    718,
    344,
    81,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    719,
    344,
    82,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    720,
    344,
    83,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    721,
    344,
    84,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    722,
    344,
    85,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    723,
    344,
    86,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    724,
    344,
    87,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    725,
    344,
    88,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    726,
    344,
    89,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    727,
    344,
    90,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    728,
    344,
    91,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    729,
    344,
    92,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    730,
    344,
    93,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    731,
    344,
    94,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    732,
    344,
    95,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    733,
    NULL,
    96,
    21,
    NULL,
    'kesan : Saya di sini merasa terdidik untuk mencapai generasi yang maju di era sekarang dan menyongsong Indonesia maju 2045.\r\nPesan : Mungkin tidak ada karena di sini semua sudah melaksanakan tugasnya dengan baik dan perfect.',
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    734,
    344,
    97,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    735,
    348,
    98,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    736,
    344,
    99,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    737,
    344,
    100,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    738,
    344,
    101,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    739,
    344,
    102,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    740,
    352,
    103,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    741,
    356,
    104,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    742,
    353,
    105,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    743,
    360,
    106,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    744,
    NULL,
    107,
    21,
    NULL,
    'Pesan : Mungkin lebih di tambah untuk materi pelatihannya untuk menjadikan generasi muda yang lebih bagus lagi. \r\nKesan : saya di sini merasa sangat suka dan terdidik menjadi generasi muda yang maju dan berkarakter',
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    745,
    344,
    108,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    746,
    364,
    109,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    747,
    344,
    110,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    748,
    344,
    111,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    749,
    368,
    112,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    750,
    372,
    113,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    751,
    344,
    114,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    752,
    344,
    115,
    21,
    NULL,
    NULL,
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    753,
    NULL,
    116,
    21,
    NULL,
    'pesan : Tidak ada karena instruktur sudah melaksanakan tugas dengan sangat baik dan membantu kita untuk belajar menjadi lebih mudah dan cepat mengerti. \r\nKesan : saya merasa terdidik selama di sini dan merasa lebih Berkarakter dengan didikan instruktur di sini.',
    '2025-08-29 08:46:15',
    '2025-08-29 08:46:15'
  ),
  (
    754,
    344,
    81,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    755,
    344,
    82,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    756,
    344,
    83,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    757,
    344,
    84,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    758,
    344,
    85,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    759,
    344,
    86,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    760,
    344,
    87,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    761,
    344,
    88,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    762,
    344,
    89,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    763,
    344,
    90,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    764,
    344,
    91,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    765,
    344,
    92,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    766,
    344,
    93,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    767,
    344,
    94,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    768,
    344,
    95,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    769,
    NULL,
    96,
    22,
    NULL,
    'Kesan\r\n\r\nSelama mengikuti kegiatan ini, saya merasa sangat senang dan mendapatkan banyak pengalaman berharga. Selain ilmu pengetahuan, saya juga belajar tentang kerja sama, tanggung jawab, dan kekompakan. Semua ini memberikan kesan mendalam yang akan selalu saya ingat.\r\n\r\nPesan\r\n\r\nSemoga kegiatan seperti ini dapat terus dilaksanakan di masa mendatang dengan persiapan yang lebih baik, sehingga manfaat yang diperoleh semakin maksimal.',
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    770,
    344,
    97,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    771,
    348,
    98,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    772,
    344,
    99,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    773,
    344,
    100,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    774,
    343,
    101,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    775,
    344,
    102,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    776,
    352,
    103,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    777,
    356,
    104,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    778,
    353,
    105,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    779,
    360,
    106,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    780,
    NULL,
    107,
    22,
    NULL,
    'sangat senang dan dapat banyak ilmu baru yang bermanfaat di masa depan',
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    781,
    344,
    108,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    782,
    364,
    109,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    783,
    344,
    110,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    784,
    344,
    111,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    785,
    368,
    112,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    786,
    372,
    113,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    787,
    344,
    114,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    788,
    344,
    115,
    22,
    NULL,
    NULL,
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    789,
    NULL,
    116,
    22,
    NULL,
    'Saya sangat senang dapat belajar bersama orang yang ahli, karena hal itu membuat saya lebih paham dan termotivasi.',
    '2025-08-29 08:47:31',
    '2025-08-29 08:47:31'
  ),
  (
    790,
    344,
    81,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    791,
    344,
    82,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    792,
    344,
    83,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    793,
    344,
    84,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    794,
    344,
    85,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    795,
    344,
    86,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    796,
    344,
    87,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    797,
    344,
    88,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    798,
    344,
    89,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    799,
    344,
    90,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    800,
    344,
    91,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    801,
    344,
    92,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    802,
    344,
    93,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    803,
    344,
    94,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    804,
    344,
    95,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    805,
    NULL,
    96,
    23,
    NULL,
    'Sangat memuaskan dan sangat mendukung dalam setiap kegiatan berlangsung, semoga bisa menjaga kualitas agar terus menjadi tempat pelatian yang menyenangkan',
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    806,
    344,
    97,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    807,
    348,
    98,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    808,
    344,
    99,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    809,
    344,
    100,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    810,
    344,
    101,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    811,
    344,
    102,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    812,
    352,
    103,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    813,
    356,
    104,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    814,
    353,
    105,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    815,
    360,
    106,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    816,
    NULL,
    107,
    23,
    NULL,
    'Semoga ilmu yang di dapat bisa berguna untuk seterus nya dan',
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    817,
    344,
    108,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    818,
    364,
    109,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    819,
    344,
    110,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    820,
    344,
    111,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    821,
    368,
    112,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    822,
    372,
    113,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    823,
    344,
    114,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    824,
    344,
    115,
    23,
    NULL,
    NULL,
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    825,
    NULL,
    116,
    23,
    NULL,
    'Semoga pak instruktur sehat sehat selalu dan saya sangat ber trimakasih atas segala ilmu dan motivasi moral yang di berikan selama pelatian berlangsung',
    '2025-08-29 08:47:45',
    '2025-08-29 08:47:45'
  ),
  (
    826,
    344,
    81,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    827,
    344,
    82,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    828,
    344,
    83,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    829,
    344,
    84,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    830,
    344,
    85,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    831,
    344,
    86,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    832,
    344,
    87,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    833,
    344,
    88,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    834,
    344,
    89,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    835,
    344,
    90,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    836,
    344,
    91,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    837,
    344,
    92,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    838,
    344,
    93,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    839,
    344,
    94,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    840,
    344,
    95,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    841,
    NULL,
    96,
    24,
    NULL,
    'Saya bisa mendapatkan ilmu, pengalaman, teman baru dan fasilitas di UPT PTKK sangat terbaek & jos jis.... mantap....',
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    842,
    344,
    97,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    843,
    348,
    98,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    844,
    344,
    99,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    845,
    344,
    100,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    846,
    343,
    101,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    847,
    344,
    102,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    848,
    352,
    103,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    849,
    356,
    104,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    850,
    353,
    105,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    851,
    360,
    106,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    852,
    NULL,
    107,
    24,
    NULL,
    'karena ini adalah suatu kesempatan emas menurut saya bisa menambah wawasan',
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    853,
    344,
    108,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    854,
    364,
    109,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    855,
    344,
    110,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    856,
    344,
    111,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    857,
    368,
    112,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    858,
    372,
    113,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    859,
    344,
    114,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    860,
    344,
    115,
    24,
    NULL,
    NULL,
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    861,
    NULL,
    116,
    24,
    NULL,
    'kata Pak Basuki: \"KALAU MAU KERJANYA BAGUS KAMU HARUS DISIPLIN DAN YANG PALING PENTING ADALAH ATITUDE MU JANGAN SAMPAI TERLEWATKAN\"',
    '2025-08-29 08:50:07',
    '2025-08-29 08:50:07'
  ),
  (
    862,
    344,
    81,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    863,
    344,
    82,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    864,
    344,
    83,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    865,
    344,
    84,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    866,
    344,
    85,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    867,
    344,
    86,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    868,
    344,
    87,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    869,
    344,
    88,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    870,
    344,
    89,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    871,
    344,
    90,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    872,
    344,
    91,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    873,
    344,
    92,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    874,
    344,
    93,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    875,
    344,
    94,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    876,
    344,
    95,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    877,
    NULL,
    96,
    25,
    NULL,
    'Pesan semoga kedepannya lebih baik lagi dari yang sekarang\r\nKesan enak',
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    878,
    344,
    97,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    879,
    348,
    98,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    880,
    344,
    99,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    881,
    344,
    100,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    882,
    344,
    101,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    883,
    344,
    102,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    884,
    351,
    103,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    885,
    355,
    104,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    886,
    353,
    105,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    887,
    360,
    106,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    888,
    NULL,
    107,
    25,
    NULL,
    'Pesan materinya sudah baik\r\nKesan enak',
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    889,
    344,
    108,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    890,
    364,
    109,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    891,
    344,
    110,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    892,
    344,
    111,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    893,
    368,
    112,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    894,
    372,
    113,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    895,
    344,
    114,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    896,
    344,
    115,
    25,
    NULL,
    NULL,
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    897,
    NULL,
    116,
    25,
    NULL,
    'Pesan terima kasih kepada instruktur telah mengajari atau memberi ilmu yang belum kita ketahui\r\nKesan enak',
    '2025-08-29 08:50:08',
    '2025-08-29 08:50:08'
  ),
  (
    898,
    344,
    81,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    899,
    344,
    82,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    900,
    344,
    83,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    901,
    344,
    84,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    902,
    344,
    85,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    903,
    344,
    86,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    904,
    344,
    87,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    905,
    344,
    88,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    906,
    343,
    89,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    907,
    344,
    90,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    908,
    344,
    91,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    909,
    344,
    92,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    910,
    344,
    93,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    911,
    344,
    94,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    912,
    344,
    95,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    913,
    NULL,
    96,
    26,
    NULL,
    'pesan: dikamar banyak nyamuk\r\nkesan: puas dengan pelayanan instansi',
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    914,
    344,
    97,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    915,
    348,
    98,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    916,
    344,
    99,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    917,
    344,
    100,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    918,
    344,
    101,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    919,
    344,
    102,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    920,
    352,
    103,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    921,
    356,
    104,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    922,
    353,
    105,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    923,
    360,
    106,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    924,
    NULL,
    107,
    26,
    NULL,
    'pesan: dibengkel alatnya banyak, materi sedikit tapi mendalam jadi langsung paham tentang ac. \r\nkesan: heran meski diberi materi sedikit tapi kami semua paham tentang ac.',
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    925,
    344,
    108,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    926,
    364,
    109,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    927,
    344,
    110,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    928,
    344,
    111,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    929,
    368,
    112,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    930,
    372,
    113,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    931,
    344,
    114,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    932,
    344,
    115,
    26,
    NULL,
    NULL,
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    933,
    NULL,
    116,
    26,
    NULL,
    'pesan: meski penjelasan dari instruktur sedikit tapi kami semua, paham semua tentang pendingin, jadi ga hanya materi\" praktek diutamakan. \r\nkesan:\r\ninstruktur sangat disiplin, ada trouble di pekerjaan instruktur membantu sampai bisa.',
    '2025-08-29 08:52:53',
    '2025-08-29 08:52:53'
  ),
  (
    934,
    343,
    81,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    935,
    343,
    82,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    936,
    343,
    83,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    937,
    343,
    84,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    938,
    344,
    85,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    939,
    344,
    86,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    940,
    344,
    87,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    941,
    343,
    88,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    942,
    343,
    89,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    943,
    343,
    90,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    944,
    343,
    91,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    945,
    343,
    92,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    946,
    343,
    93,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    947,
    344,
    94,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    948,
    343,
    95,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    949,
    NULL,
    96,
    27,
    NULL,
    'selama di pelatihan saya cukup nyaman dengan kamar dan toilet yang disediakan serta dengan fasilitas\" lainya',
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    950,
    343,
    97,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    951,
    348,
    98,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    952,
    343,
    99,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    953,
    343,
    100,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    954,
    343,
    101,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    955,
    352,
    103,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    956,
    356,
    104,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    957,
    356,
    105,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    958,
    360,
    106,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    959,
    NULL,
    107,
    27,
    NULL,
    'saya sangat senang dapat belajar tentang pemasangan dan pembongkaran AC',
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    960,
    344,
    108,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    961,
    364,
    109,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    962,
    344,
    110,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    963,
    344,
    111,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    964,
    368,
    112,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    965,
    372,
    113,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    966,
    344,
    114,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    967,
    344,
    115,
    27,
    NULL,
    NULL,
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    968,
    NULL,
    116,
    27,
    NULL,
    'kesan saat pelatihan cukup baik karena saya dapat memperoleh pengalaman baru,teman baru, ilmu baru',
    '2025-08-29 08:52:54',
    '2025-08-29 08:52:54'
  ),
  (
    969,
    343,
    81,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    970,
    344,
    82,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    971,
    344,
    83,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    972,
    344,
    84,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    973,
    344,
    85,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    974,
    344,
    86,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    975,
    344,
    87,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    976,
    343,
    88,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    977,
    344,
    89,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    978,
    344,
    90,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    979,
    343,
    91,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    980,
    343,
    92,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    981,
    344,
    93,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    982,
    344,
    94,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    983,
    344,
    95,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    984,
    NULL,
    96,
    28,
    NULL,
    'Pesan: \"semoga acara pelatihan ini dapat membuat para siswa dan siswi SMK untuk siap menghadapi dunia kerja yang sesungguhnya\"\r\n\r\nKesan: \"kesan saya adalah semua hal yang ada dalam program pelatihan yang dilaksanakan telah teroganisir dengan baik dan nyaman untuk dilakukan\"',
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    985,
    344,
    97,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    986,
    348,
    98,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    987,
    344,
    99,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    988,
    344,
    100,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    989,
    344,
    101,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    990,
    344,
    102,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    991,
    352,
    103,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    992,
    356,
    104,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    993,
    353,
    105,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    994,
    360,
    106,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    995,
    NULL,
    107,
    28,
    NULL,
    'Pesan: \"semoga kedepannya program pelatihan ini tetap berjalan dengan materi yang membuat para peserta siap untuk menghadapi dunia kerja sesungguhnya\"\r\n\r\nKesan: \"Penyampaian materi dari guru atau instruktur pelatihan nya sangat mudah untuk dipahami dan di praktekkan\"',
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    996,
    344,
    108,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    997,
    364,
    109,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    998,
    344,
    110,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    999,
    344,
    111,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1000,
    368,
    112,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1001,
    372,
    113,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1002,
    344,
    114,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1003,
    344,
    115,
    28,
    NULL,
    NULL,
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1004,
    NULL,
    116,
    28,
    NULL,
    'Pesan: \"Perbanyak program seperti ini agar para siswa dan siswi SMK siap menghadapi dunia kerja setelah lulus\"\r\n\r\nKesan: \"Program pelatihan kejuruan ini sangat berkesan dan bermanfaat untuk saya dan teman-teman saya juga, saya belajar banyak hal dari program ini dan saya berharap untuk menjadi yang terbaik setelah lulus dari program ini\"',
    '2025-08-29 08:53:11',
    '2025-08-29 08:53:11'
  ),
  (
    1005,
    344,
    81,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1006,
    344,
    82,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1007,
    344,
    83,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1008,
    344,
    84,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1009,
    344,
    85,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1010,
    344,
    86,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1011,
    344,
    87,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1012,
    344,
    88,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1013,
    344,
    89,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1014,
    344,
    90,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1015,
    344,
    91,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1016,
    344,
    92,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1017,
    344,
    93,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1018,
    344,
    94,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1019,
    344,
    95,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1020,
    NULL,
    96,
    29,
    NULL,
    'Pesan : Tetap dijaga kebersihannya di setiap kamar tidur maupun kamar mandi\r\nPesan : lebih di jaga kebersihannya lagi supaya gak gampang kotor dan siapapun yang menempatinya merasa lebih nyaman dan bahagia',
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1021,
    344,
    97,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1022,
    348,
    98,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1023,
    344,
    99,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1024,
    344,
    100,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1025,
    344,
    101,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1026,
    344,
    102,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1027,
    352,
    103,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1028,
    356,
    104,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1029,
    353,
    105,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1030,
    360,
    106,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1031,
    NULL,
    107,
    29,
    NULL,
    'Pesan saya : Terus adakan pelatihan seperti ini karna kita dapat melatih kedisiplinan dan percaya diri, bagaimana caranya bekerja dengan baik dan benar ketika saat di lapangan pekerjaan.\r\nKesana : Saya dapat banyak pengalaman yang Begitu berarti bagi hidup saya, saya dapat lebih di siplin dan saya juga dapat wawasan yang lebih, dan saya sangat Begitu senang dengan apa yang telah saya dapatkan selama saya di sini, yang awalnya saya tidak tau bagaimana caranya yang benar sehingga saya bisa tau gimana cara bekerja yang benar dan lebih praktis.',
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1032,
    344,
    108,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1033,
    364,
    109,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1034,
    344,
    110,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1035,
    344,
    111,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1036,
    368,
    112,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1037,
    372,
    113,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1038,
    344,
    114,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1039,
    344,
    115,
    29,
    NULL,
    NULL,
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1040,
    NULL,
    116,
    29,
    NULL,
    'Pesan saya : Terimakasih kepada semua para instruktur, panitia dan guru pelatihan di bengkel yang telah mengajarkan saya dan memberikan ilmu yang begitu bermanfaat bagi saya\r\nKesan : Saya akan terus terapkan ilmu yang telah saya dapatkan di sini dan saya bakal terus pelajari materi yang telah di sampaikan karna itu adalah bekal saya ketika saya sudah menghadapi dunia kerja yang sesungguhnya.',
    '2025-08-29 08:53:19',
    '2025-08-29 08:53:19'
  ),
  (
    1041,
    344,
    81,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1042,
    344,
    82,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1043,
    344,
    83,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1044,
    344,
    84,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1045,
    344,
    85,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1046,
    344,
    86,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1047,
    344,
    87,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1048,
    344,
    88,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1049,
    344,
    89,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1050,
    344,
    90,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1051,
    344,
    91,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1052,
    344,
    92,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1053,
    344,
    93,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1054,
    344,
    94,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1055,
    344,
    95,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1056,
    NULL,
    96,
    30,
    NULL,
    'Pesan : Tetap dijaga kebersihannya di setiap kamar tidur maupun kamar mandi\r\nPesan : lebih di jaga kebersihannya lagi supaya gak gampang kotor dan siapapun yang menempatinya merasa lebih nyaman dan bahagia',
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1057,
    344,
    97,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1058,
    348,
    98,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1059,
    344,
    99,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1060,
    344,
    100,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1061,
    344,
    101,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1062,
    344,
    102,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1063,
    352,
    103,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1064,
    356,
    104,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1065,
    353,
    105,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1066,
    360,
    106,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1067,
    NULL,
    107,
    30,
    NULL,
    'Pesan saya : Terus adakan pelatihan seperti ini karna kita dapat melatih kedisiplinan dan percaya diri, bagaimana caranya bekerja dengan baik dan benar ketika saat di lapangan pekerjaan.\r\nKesana : Saya dapat banyak pengalaman yang Begitu berarti bagi hidup saya, saya dapat lebih di siplin dan saya juga dapat wawasan yang lebih, dan saya sangat Begitu senang dengan apa yang telah saya dapatkan selama saya di sini, yang awalnya saya tidak tau bagaimana caranya yang benar sehingga saya bisa tau gimana cara bekerja yang benar dan lebih praktis.',
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1068,
    344,
    108,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1069,
    364,
    109,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1070,
    344,
    110,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1071,
    344,
    111,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1072,
    368,
    112,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1073,
    372,
    113,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1074,
    344,
    114,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1075,
    344,
    115,
    30,
    NULL,
    NULL,
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1076,
    NULL,
    116,
    30,
    NULL,
    'Pesan saya : Terimakasih kepada semua para instruktur, panitia dan guru pelatihan di bengkel yang telah mengajarkan saya dan memberikan ilmu yang begitu bermanfaat bagi saya\r\nKesan : Saya akan terus terapkan ilmu yang telah saya dapatkan di sini dan saya bakal terus pelajari materi yang telah di sampaikan karna itu adalah bekal saya ketika saya sudah menghadapi dunia kerja yang sesungguhnya.',
    '2025-08-29 08:53:21',
    '2025-08-29 08:53:21'
  ),
  (
    1077,
    344,
    81,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1078,
    344,
    82,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1079,
    344,
    83,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1080,
    344,
    84,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1081,
    344,
    85,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1082,
    344,
    86,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1083,
    344,
    87,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1084,
    344,
    88,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1085,
    344,
    89,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1086,
    344,
    90,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1087,
    344,
    91,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1088,
    344,
    92,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1089,
    344,
    93,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1090,
    344,
    94,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1091,
    344,
    95,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1092,
    NULL,
    96,
    31,
    NULL,
    'Pesan : Tetap dijaga kebersihannya di setiap kamar tidur maupun kamar mandi\r\nPesan : lebih di jaga kebersihannya lagi supaya gak gampang kotor dan siapapun yang menempatinya merasa lebih nyaman dan bahagia',
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1093,
    344,
    97,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1094,
    348,
    98,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1095,
    344,
    99,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1096,
    344,
    100,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1097,
    344,
    101,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1098,
    344,
    102,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1099,
    352,
    103,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1100,
    356,
    104,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1101,
    353,
    105,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1102,
    360,
    106,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1103,
    NULL,
    107,
    31,
    NULL,
    'Pesan saya : Terus adakan pelatihan seperti ini karna kita dapat melatih kedisiplinan dan percaya diri, bagaimana caranya bekerja dengan baik dan benar ketika saat di lapangan pekerjaan.\r\nKesana : Saya dapat banyak pengalaman yang Begitu berarti bagi hidup saya, saya dapat lebih di siplin dan saya juga dapat wawasan yang lebih, dan saya sangat Begitu senang dengan apa yang telah saya dapatkan selama saya di sini, yang awalnya saya tidak tau bagaimana caranya yang benar sehingga saya bisa tau gimana cara bekerja yang benar dan lebih praktis.',
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1104,
    344,
    108,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1105,
    364,
    109,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1106,
    344,
    110,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1107,
    344,
    111,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1108,
    368,
    112,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1109,
    372,
    113,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1110,
    344,
    114,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1111,
    344,
    115,
    31,
    NULL,
    NULL,
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1112,
    NULL,
    116,
    31,
    NULL,
    'Pesan saya : Terimakasih kepada semua para instruktur, panitia dan guru pelatihan di bengkel yang telah mengajarkan saya dan memberikan ilmu yang begitu bermanfaat bagi saya\r\nKesan : Saya akan terus terapkan ilmu yang telah saya dapatkan di sini dan saya bakal terus pelajari materi yang telah di sampaikan karna itu adalah bekal saya ketika saya sudah menghadapi dunia kerja yang sesungguhnya.',
    '2025-08-29 08:53:31',
    '2025-08-29 08:53:31'
  ),
  (
    1113,
    344,
    81,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1114,
    344,
    82,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1115,
    344,
    83,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1116,
    344,
    84,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1117,
    344,
    85,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1118,
    344,
    86,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1119,
    344,
    87,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1120,
    344,
    88,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1121,
    344,
    89,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1122,
    344,
    90,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1123,
    344,
    91,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1124,
    344,
    92,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1125,
    344,
    93,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1126,
    344,
    94,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1127,
    344,
    95,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1128,
    NULL,
    96,
    32,
    NULL,
    'Pesan : dijaga kebersihannya di kamar mandi maupun di kamar tidur \r\nKesan : Saya senang mendapatkan tempat tidur yang nyaman dan mendapatkan fasilitas yang Begitu Bagus',
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1129,
    344,
    97,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1130,
    348,
    98,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1131,
    344,
    99,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1132,
    344,
    100,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1133,
    344,
    101,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1134,
    344,
    102,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1135,
    352,
    103,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1136,
    356,
    104,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1137,
    356,
    105,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1138,
    360,
    106,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1139,
    NULL,
    107,
    32,
    NULL,
    'Pesan : Terus diakan pelatihan seperti ini supaya kita bisa saling sama-sama belajar dengan satu sama lainnya \r\nKesan : saya mendapatkan banyak ilmu yang belum saya ketahui dari sebelum sebelumnya, saya diajarkan tentang kedisiplinan dan soft skill saya lebih berkembang dan lebih baik',
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1140,
    344,
    108,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1141,
    364,
    109,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1142,
    344,
    110,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1143,
    344,
    111,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1144,
    368,
    112,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1145,
    372,
    113,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1146,
    344,
    114,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1147,
    344,
    115,
    32,
    NULL,
    NULL,
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1148,
    NULL,
    116,
    32,
    NULL,
    'Pesan : Terimakasih kepada semua instruktur, panitia dan guru guru pengajar yang telah membimbing saya dan mengajari saya supaya lebih baik kedepannya, dan saya bakal bawa terus materi ini sampai kapan aja dan di manapun saya bekerja \r\nKesan : saya dapat banyak ilmu yang telah diajarkan oleh pelatih maupun pembimbing yang ada disini, saya bakal terus bawa ilmu',
    '2025-08-29 09:08:05',
    '2025-08-29 09:08:05'
  ),
  (
    1149,
    344,
    81,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1150,
    344,
    82,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1151,
    344,
    83,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1152,
    344,
    84,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1153,
    344,
    85,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1154,
    344,
    86,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1155,
    343,
    87,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1156,
    343,
    88,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1157,
    343,
    89,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1158,
    344,
    90,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1159,
    344,
    91,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1160,
    343,
    92,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1161,
    344,
    94,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1162,
    343,
    95,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1163,
    NULL,
    96,
    33,
    NULL,
    'Sangat seruuuuuu',
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1164,
    343,
    97,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1165,
    348,
    98,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1166,
    344,
    99,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1167,
    344,
    100,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1168,
    344,
    101,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1169,
    344,
    102,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1170,
    351,
    103,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1171,
    355,
    104,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1172,
    353,
    105,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1173,
    360,
    106,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1174,
    NULL,
    107,
    33,
    NULL,
    'Menyenangkan',
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1175,
    343,
    108,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1176,
    364,
    109,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1177,
    344,
    110,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1178,
    344,
    111,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1179,
    368,
    112,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1180,
    372,
    113,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1181,
    344,
    114,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  );

INSERT INTO
  `jawaban_users` (
    `id`,
    `opsi_jawabans_id`,
    `pertanyaan_id`,
    `percobaan_id`,
    `nilai_jawaban`,
    `jawaban_teks`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1182,
    344,
    115,
    33,
    NULL,
    NULL,
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1183,
    NULL,
    116,
    33,
    NULL,
    'Seru dan menyenangkan',
    '2025-08-29 09:09:03',
    '2025-08-29 09:09:03'
  ),
  (
    1184,
    344,
    81,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1185,
    343,
    82,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1186,
    343,
    83,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1187,
    344,
    84,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1188,
    343,
    85,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1189,
    344,
    86,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1190,
    343,
    87,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1191,
    344,
    88,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1192,
    344,
    89,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1193,
    343,
    90,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1194,
    342,
    91,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1195,
    344,
    92,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1196,
    344,
    93,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1197,
    344,
    94,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1198,
    344,
    95,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1199,
    NULL,
    96,
    34,
    NULL,
    'menyenangkannn, dan sangat bermanfaat untuk diri saya sendiri',
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1200,
    343,
    97,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1201,
    348,
    98,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1202,
    343,
    99,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1203,
    343,
    100,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1204,
    344,
    101,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1205,
    344,
    102,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1206,
    351,
    103,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1207,
    355,
    104,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1208,
    356,
    105,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1209,
    360,
    106,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1210,
    NULL,
    107,
    34,
    NULL,
    'materi dapat dipahami dan sangat jelas',
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1211,
    344,
    108,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1212,
    364,
    109,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1213,
    344,
    110,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1214,
    344,
    111,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1215,
    368,
    112,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1216,
    371,
    113,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1217,
    344,
    114,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1218,
    344,
    115,
    34,
    NULL,
    NULL,
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1219,
    NULL,
    116,
    34,
    NULL,
    'instruktur menyenangkan dan mudah mengerti apa yng kita mksud',
    '2025-08-29 09:10:20',
    '2025-08-29 09:10:20'
  ),
  (
    1220,
    344,
    81,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1221,
    344,
    82,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1222,
    344,
    83,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1223,
    344,
    84,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1224,
    344,
    85,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1225,
    344,
    86,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1226,
    343,
    87,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1227,
    344,
    88,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1228,
    344,
    89,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1229,
    344,
    90,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1230,
    344,
    91,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1231,
    344,
    92,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1232,
    344,
    93,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1233,
    344,
    94,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1234,
    344,
    95,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1235,
    NULL,
    96,
    35,
    NULL,
    'pelayanan di instansi ini sangat nyaman semoga kedepannya tetap seperti ini bahkan bisa ditingkatkan lagi',
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1236,
    343,
    97,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1237,
    348,
    98,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1238,
    344,
    99,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1239,
    344,
    100,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1240,
    344,
    101,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1241,
    344,
    102,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1242,
    352,
    103,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1243,
    355,
    104,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1244,
    353,
    105,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1245,
    360,
    106,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1246,
    NULL,
    107,
    35,
    NULL,
    'fasilitas nya sangat mendukung dalam melakukan pembelajaran jika perlu bisa menambah peralatan bengkel lagi',
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1247,
    344,
    108,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1248,
    364,
    109,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1249,
    344,
    110,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1250,
    344,
    111,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1251,
    368,
    112,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1252,
    372,
    113,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1253,
    344,
    114,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1254,
    344,
    115,
    35,
    NULL,
    NULL,
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1255,
    NULL,
    116,
    35,
    NULL,
    'instruktur nya sangat ramah dan materi yang diberikan juga mudah dipahami',
    '2025-08-29 09:11:44',
    '2025-08-29 09:11:44'
  ),
  (
    1256,
    344,
    81,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1257,
    344,
    82,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1258,
    343,
    83,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1259,
    343,
    84,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1260,
    343,
    85,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1261,
    343,
    86,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1262,
    343,
    87,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1263,
    343,
    88,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1264,
    343,
    89,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1265,
    344,
    90,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1266,
    343,
    91,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1267,
    343,
    92,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1268,
    343,
    93,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1269,
    343,
    94,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1270,
    343,
    95,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1271,
    NULL,
    96,
    36,
    NULL,
    'sangat senang berada di lingkungan sini',
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1272,
    343,
    97,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1273,
    348,
    98,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1274,
    343,
    99,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1275,
    343,
    100,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1276,
    343,
    101,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1277,
    343,
    102,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1278,
    352,
    103,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1279,
    353,
    104,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1280,
    353,
    105,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1281,
    359,
    106,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1282,
    NULL,
    107,
    36,
    NULL,
    'sangat senang pelatihan di sini',
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1283,
    343,
    108,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1284,
    363,
    109,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1285,
    343,
    110,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1286,
    343,
    111,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1287,
    367,
    112,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1288,
    371,
    113,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1289,
    343,
    114,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1290,
    343,
    115,
    36,
    NULL,
    NULL,
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1291,
    NULL,
    116,
    36,
    NULL,
    'sangat seru, senang, memuaskan',
    '2025-08-29 09:19:12',
    '2025-08-29 09:19:12'
  ),
  (
    1292,
    343,
    81,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1293,
    344,
    82,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1294,
    343,
    83,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1295,
    344,
    84,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1296,
    344,
    85,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1297,
    344,
    86,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1298,
    343,
    87,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1299,
    344,
    88,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1300,
    344,
    89,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1301,
    344,
    90,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1302,
    344,
    91,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1303,
    344,
    92,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1304,
    344,
    93,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1305,
    344,
    94,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1306,
    344,
    95,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1307,
    NULL,
    96,
    37,
    NULL,
    'mohon maaf ketika makan jangan hanya di waktu 20 menit saja, mungkin awal\' 30 menit dulu, soalnya saya perutnya setelah makan sakit karena kaget langsung makan cepat',
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1308,
    343,
    97,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1309,
    348,
    98,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1310,
    344,
    99,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1311,
    344,
    100,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1312,
    344,
    101,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1313,
    344,
    102,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1314,
    352,
    103,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1315,
    356,
    104,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1316,
    356,
    105,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1317,
    360,
    106,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1318,
    NULL,
    107,
    37,
    NULL,
    'sangat seru, dan sangat bermanfaat ilmu nya',
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1319,
    344,
    108,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1320,
    364,
    109,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1321,
    344,
    110,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1322,
    344,
    111,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1323,
    368,
    112,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1324,
    372,
    113,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1325,
    344,
    114,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1326,
    344,
    115,
    37,
    NULL,
    NULL,
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1327,
    NULL,
    116,
    37,
    NULL,
    'instruktur nya sangat baik dan ramah',
    '2025-08-29 09:20:33',
    '2025-08-29 09:20:33'
  ),
  (
    1328,
    344,
    81,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1329,
    344,
    82,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1330,
    343,
    83,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1331,
    343,
    84,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1332,
    344,
    85,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1333,
    344,
    86,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1334,
    343,
    87,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1335,
    343,
    88,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1336,
    344,
    89,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1337,
    344,
    90,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1338,
    343,
    91,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1339,
    343,
    92,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1340,
    343,
    93,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1341,
    343,
    94,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1342,
    343,
    95,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1343,
    NULL,
    96,
    38,
    NULL,
    'saya senang sekali dengan pelatihan ini, banyak ilmu baru yang bisa saya bawa sebagai bekal dimana pun saya berada, tetapi sangat disayangkan remot ac tidak berfungsi serta tidak ada selimut jadi satu kamar kedinginan',
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1344,
    343,
    97,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1345,
    348,
    98,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1346,
    344,
    99,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1347,
    343,
    100,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1348,
    344,
    101,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1349,
    344,
    102,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1350,
    352,
    103,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1351,
    355,
    104,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1352,
    353,
    105,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1353,
    360,
    106,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1354,
    NULL,
    107,
    38,
    NULL,
    'seruu saat praktek, dibimbing satu persatu dari awal sampai akhir jadi dan plating pun tetap dibantu',
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1355,
    344,
    108,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1356,
    364,
    109,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1357,
    344,
    110,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1358,
    344,
    111,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1359,
    368,
    112,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1360,
    372,
    113,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1361,
    344,
    114,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1362,
    344,
    115,
    38,
    NULL,
    NULL,
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1363,
    NULL,
    116,
    38,
    NULL,
    'terimakasihh pembimbingnya sangat baikk, cara menjelaskannya mudah difahami dan jelas penyampaiannya sehingga mudah diserap ilmunyaa',
    '2025-08-29 09:20:50',
    '2025-08-29 09:20:50'
  ),
  (
    1364,
    344,
    81,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1365,
    344,
    82,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1366,
    344,
    83,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1367,
    344,
    84,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1368,
    344,
    85,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1369,
    344,
    86,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1370,
    344,
    87,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1371,
    344,
    88,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1372,
    344,
    89,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1373,
    344,
    90,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1374,
    344,
    91,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1375,
    344,
    92,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1376,
    344,
    93,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1377,
    344,
    94,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1378,
    344,
    95,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1379,
    NULL,
    96,
    39,
    NULL,
    'nyaman banget semua pelayanan sangat memuaskan',
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1380,
    343,
    97,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1381,
    348,
    98,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1382,
    344,
    99,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1383,
    344,
    100,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1384,
    344,
    101,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1385,
    344,
    102,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1386,
    352,
    103,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1387,
    355,
    104,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1388,
    354,
    105,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1389,
    360,
    106,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1390,
    NULL,
    107,
    39,
    NULL,
    'semua materi yg diberi sangat bermanfaat',
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1391,
    344,
    108,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1392,
    364,
    109,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1393,
    344,
    110,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1394,
    344,
    111,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1395,
    368,
    112,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1396,
    372,
    113,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1397,
    344,
    114,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1398,
    344,
    115,
    39,
    NULL,
    NULL,
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1399,
    NULL,
    116,
    39,
    NULL,
    'instruktur yang menjelaskan sangat mudah untuk di pahami dan asik',
    '2025-08-29 09:21:01',
    '2025-08-29 09:21:01'
  ),
  (
    1400,
    343,
    81,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1401,
    343,
    82,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1402,
    343,
    83,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1403,
    343,
    84,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1404,
    343,
    85,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1405,
    343,
    86,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1406,
    343,
    87,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1407,
    343,
    88,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1408,
    343,
    89,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1409,
    343,
    90,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1410,
    342,
    91,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1411,
    343,
    92,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1412,
    343,
    93,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1413,
    343,
    94,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1414,
    343,
    95,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1415,
    NULL,
    96,
    40,
    NULL,
    'Alhamdulillah saya betah di sini tetapi remot ACE nya rusak kamar mandi kurang bersih',
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1416,
    343,
    97,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1417,
    347,
    98,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1418,
    343,
    99,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1419,
    344,
    100,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1420,
    343,
    101,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1421,
    343,
    102,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1422,
    351,
    103,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1423,
    355,
    104,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1424,
    354,
    105,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1425,
    359,
    106,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1426,
    NULL,
    107,
    40,
    NULL,
    'Selama pembelajaran seru sekali',
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1427,
    343,
    108,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1428,
    363,
    109,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1429,
    343,
    110,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1430,
    343,
    111,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1431,
    367,
    112,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1432,
    371,
    113,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1433,
    343,
    114,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1434,
    343,
    115,
    40,
    NULL,
    NULL,
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1435,
    NULL,
    116,
    40,
    NULL,
    'Memuaskan',
    '2025-08-29 09:21:40',
    '2025-08-29 09:21:40'
  ),
  (
    1436,
    343,
    81,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1437,
    343,
    82,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1438,
    343,
    83,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1439,
    343,
    84,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1440,
    343,
    85,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1441,
    343,
    86,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1442,
    343,
    87,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1443,
    343,
    88,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1444,
    343,
    89,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1445,
    343,
    90,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1446,
    343,
    91,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1447,
    343,
    92,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1448,
    343,
    93,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1449,
    343,
    94,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1450,
    343,
    95,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1451,
    NULL,
    96,
    41,
    NULL,
    'sangat seru dan sangat menyenangkan fasilitas dan keamanan yang diberikan juga sangat memuaskan',
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1452,
    343,
    97,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1453,
    347,
    98,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1454,
    343,
    99,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1455,
    343,
    100,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1456,
    343,
    101,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1457,
    343,
    102,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1458,
    351,
    103,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1459,
    354,
    104,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1460,
    354,
    105,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1461,
    359,
    106,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1462,
    NULL,
    107,
    41,
    NULL,
    'selama saya pelatihan dan masuk kedalam kelas saya merasa bahwa ilmu yang diberikan bisa saya terima dengan baik',
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1463,
    343,
    108,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1464,
    363,
    109,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1465,
    343,
    110,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1466,
    343,
    111,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1467,
    367,
    112,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1468,
    371,
    113,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1469,
    343,
    114,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1470,
    343,
    115,
    41,
    NULL,
    NULL,
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1471,
    NULL,
    116,
    41,
    NULL,
    'instruktur dikelas sangat ramah bisa berbaur dan memberikan materi dengan sangat baik, ketika kesusahan sangat dibantu dan menjawab i pertanyaan dengan baik sehingga saya bisa menerima apa yang dimaksud dengan baik',
    '2025-08-29 09:23:15',
    '2025-08-29 09:23:15'
  ),
  (
    1472,
    344,
    81,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1473,
    343,
    82,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1474,
    343,
    83,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1475,
    343,
    84,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1476,
    343,
    85,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1477,
    343,
    86,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1478,
    344,
    87,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1479,
    344,
    88,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1480,
    344,
    89,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1481,
    344,
    90,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1482,
    344,
    91,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1483,
    344,
    92,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1484,
    344,
    93,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1485,
    344,
    94,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1486,
    344,
    95,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1487,
    NULL,
    96,
    42,
    NULL,
    'SAYA DI SINI DAPAT PENGALAMAN BANYAK',
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1488,
    344,
    97,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1489,
    348,
    98,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1490,
    344,
    99,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1491,
    344,
    100,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1492,
    344,
    101,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1493,
    344,
    102,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1494,
    352,
    103,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1495,
    356,
    104,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1496,
    355,
    105,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1497,
    360,
    106,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1498,
    NULL,
    107,
    42,
    NULL,
    'Ilmu yang saya peroleh juga baik untuk masa depan saya',
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1499,
    344,
    108,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1500,
    364,
    109,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1501,
    344,
    110,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1502,
    344,
    111,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1503,
    368,
    112,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1504,
    372,
    113,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1505,
    344,
    114,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1506,
    344,
    115,
    42,
    NULL,
    NULL,
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1507,
    NULL,
    116,
    42,
    NULL,
    'Pertanyaan yang baik dan itu bisa Mengasah pikiran Anak Gen Z sekarang',
    '2025-08-29 09:23:39',
    '2025-08-29 09:23:39'
  ),
  (
    1508,
    344,
    81,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1509,
    344,
    82,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1510,
    344,
    83,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1511,
    344,
    84,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1512,
    344,
    85,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1513,
    344,
    86,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1514,
    344,
    87,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1515,
    344,
    88,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1516,
    344,
    89,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1517,
    344,
    90,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1518,
    344,
    91,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1519,
    344,
    92,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1520,
    344,
    93,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1521,
    344,
    94,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1522,
    344,
    95,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1523,
    NULL,
    96,
    43,
    NULL,
    'Sangat amat bermanfaat pembelajaran di sini',
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1524,
    344,
    97,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1525,
    348,
    98,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1526,
    344,
    99,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1527,
    344,
    100,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1528,
    344,
    101,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1529,
    344,
    102,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1530,
    352,
    103,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1531,
    356,
    104,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1532,
    356,
    105,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1533,
    360,
    106,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1534,
    NULL,
    107,
    43,
    NULL,
    'Sangat mendukung dan menyenangkan',
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1535,
    344,
    108,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1536,
    364,
    109,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1537,
    344,
    110,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1538,
    344,
    111,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1539,
    368,
    112,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1540,
    372,
    113,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1541,
    344,
    114,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1542,
    344,
    115,
    43,
    NULL,
    NULL,
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1543,
    NULL,
    116,
    43,
    NULL,
    'Sangat amat mudah di pahami ketika instruktur menjelaskan',
    '2025-08-29 09:24:34',
    '2025-08-29 09:24:34'
  ),
  (
    1544,
    343,
    81,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1545,
    344,
    82,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1546,
    344,
    83,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1547,
    343,
    84,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1548,
    343,
    85,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1549,
    344,
    86,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1550,
    343,
    87,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1551,
    344,
    88,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1552,
    344,
    89,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1553,
    344,
    90,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1554,
    343,
    91,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1555,
    344,
    92,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1556,
    344,
    93,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1557,
    344,
    94,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1558,
    344,
    95,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1559,
    NULL,
    96,
    44,
    NULL,
    'saya habis makan sakit perut',
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1560,
    343,
    97,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1561,
    348,
    98,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1562,
    344,
    99,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1563,
    343,
    100,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1564,
    343,
    101,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1565,
    344,
    102,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1566,
    351,
    103,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1567,
    354,
    104,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1568,
    353,
    105,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1569,
    360,
    106,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1570,
    NULL,
    107,
    44,
    NULL,
    'PAK DAVID PAK USMAN DAN PAK ULUM SANGAT ASIK,DAN SAYA NYAMAN DIAJAR OLEH BELIAU\"🙏🏻🙏🏻👍🏻👍🏻👍🏻👍🏻👍🏻SAYA JADI PAHAM TENTANG PASTRY DAN BAKERY',
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1571,
    344,
    108,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1572,
    364,
    109,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1573,
    344,
    110,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1574,
    344,
    111,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1575,
    368,
    112,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1576,
    372,
    113,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1577,
    344,
    114,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1578,
    344,
    115,
    44,
    NULL,
    NULL,
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1579,
    NULL,
    116,
    44,
    NULL,
    'SAYA SANGAT BAHAGIA DAN MERASA BERKEMBANG DISINI,BERKAT ILMU DARI PAK ULUM PAK USMAN DAN PAK DAVID🙏🏻',
    '2025-08-29 09:25:39',
    '2025-08-29 09:25:39'
  ),
  (
    1580,
    343,
    81,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1581,
    343,
    82,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1582,
    343,
    83,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1583,
    343,
    84,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1584,
    343,
    85,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1585,
    343,
    86,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1586,
    343,
    87,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1587,
    343,
    88,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1588,
    343,
    89,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1589,
    343,
    90,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1590,
    343,
    91,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1591,
    343,
    92,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1592,
    343,
    93,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1593,
    343,
    94,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1594,
    343,
    95,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1595,
    NULL,
    96,
    45,
    NULL,
    'untuk pelatihan kali inii sudaa sangat bagus pada saat melaksanakan pelatihan',
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1596,
    343,
    97,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1597,
    348,
    98,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1598,
    344,
    99,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1599,
    344,
    100,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1600,
    344,
    101,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1601,
    344,
    102,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1602,
    351,
    103,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1603,
    354,
    104,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1604,
    355,
    105,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1605,
    360,
    106,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1606,
    NULL,
    107,
    45,
    NULL,
    'kesan pesan untuk inii sangat bermanfaat bagi saya karena memberikan ilmu yang banyak dari yang awalnya kurang mengetahui dari patry bakery sampai memahami',
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1607,
    344,
    108,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1608,
    363,
    109,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1609,
    344,
    110,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1610,
    344,
    111,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1611,
    368,
    112,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1612,
    372,
    113,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1613,
    344,
    114,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1614,
    344,
    115,
    45,
    NULL,
    NULL,
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1615,
    NULL,
    116,
    45,
    NULL,
    'untuk para instruktur tetap semangat yaa pak sehat selalu',
    '2025-08-29 09:25:54',
    '2025-08-29 09:25:54'
  ),
  (
    1616,
    343,
    81,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1617,
    343,
    82,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1618,
    343,
    83,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1619,
    343,
    84,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1620,
    343,
    85,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1621,
    343,
    86,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1622,
    344,
    87,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1623,
    343,
    88,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1624,
    344,
    89,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1625,
    344,
    90,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1626,
    344,
    91,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1627,
    344,
    92,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1628,
    344,
    93,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1629,
    344,
    94,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1630,
    343,
    95,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1631,
    NULL,
    96,
    46,
    NULL,
    'terimakasih untuk semua petugas, panitia yang ikut serta dalam kegiatan ini',
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1632,
    343,
    97,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1633,
    348,
    98,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1634,
    344,
    99,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1635,
    344,
    100,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1636,
    344,
    101,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1637,
    344,
    102,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1638,
    352,
    103,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1639,
    355,
    104,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1640,
    353,
    105,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1641,
    360,
    106,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1642,
    NULL,
    107,
    46,
    NULL,
    'sangat seru',
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1643,
    343,
    108,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1644,
    364,
    109,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1645,
    344,
    110,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1646,
    344,
    111,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1647,
    368,
    112,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1648,
    372,
    113,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1649,
    344,
    114,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1650,
    344,
    115,
    46,
    NULL,
    NULL,
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1651,
    NULL,
    116,
    46,
    NULL,
    'untuk instruktur sangat sangat membimbing dalam materi maupun praktek',
    '2025-08-29 09:27:52',
    '2025-08-29 09:27:52'
  ),
  (
    1652,
    343,
    81,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1653,
    343,
    82,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1654,
    344,
    83,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1655,
    343,
    84,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1656,
    343,
    85,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1657,
    344,
    86,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1658,
    343,
    87,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1659,
    343,
    88,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1660,
    343,
    89,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1661,
    343,
    90,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1662,
    344,
    91,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1663,
    343,
    92,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1664,
    344,
    93,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1665,
    344,
    94,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1666,
    343,
    95,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1667,
    NULL,
    96,
    47,
    NULL,
    'pesan : saya berharap kalian selalu sehat. Dilancarkan rezekinya dan jangan ragu untuk menghubungi saya kapan pun kalian butuh bantuan\r\nKesan : saya sangat senang mendapatkan ilmu dan teman baru',
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1668,
    343,
    97,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1669,
    348,
    98,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1670,
    343,
    99,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1671,
    343,
    100,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1672,
    343,
    101,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1673,
    344,
    102,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1674,
    351,
    103,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1675,
    355,
    104,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1676,
    355,
    105,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1677,
    360,
    106,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1678,
    NULL,
    107,
    47,
    NULL,
    'pesan : saya berharap kalian selalu sehat. Dilancarkan rezekinya dan jangan ragu untuk menghubungi saya kapan pun kalian butuh bantuan\r\nKesan : saya sangat senang mendapatkan ilmu dan teman baru',
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1679,
    343,
    108,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1680,
    363,
    109,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1681,
    343,
    110,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1682,
    343,
    111,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1683,
    367,
    112,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1684,
    371,
    113,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1685,
    343,
    114,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1686,
    343,
    115,
    47,
    NULL,
    NULL,
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1687,
    NULL,
    116,
    47,
    NULL,
    'pesan : saya berharap kalian selalu sehat. Dilancarkan rezekinya dan jangan ragu untuk menghubungi saya kapan pun kalian butuh bantuan\r\nKesan : saya sangat senang mendapatkan ilmu dan teman baru',
    '2025-08-29 10:09:46',
    '2025-08-29 10:09:46'
  ),
  (
    1688,
    344,
    81,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1689,
    343,
    82,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1690,
    343,
    83,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1691,
    344,
    84,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1692,
    343,
    85,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1693,
    343,
    86,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1694,
    343,
    87,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1695,
    343,
    88,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1696,
    343,
    89,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1697,
    343,
    90,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1698,
    343,
    91,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1699,
    344,
    92,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1700,
    344,
    93,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1701,
    343,
    94,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1702,
    344,
    95,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1703,
    NULL,
    96,
    48,
    NULL,
    'Kegiatan ini sangat seru, tapi untuk peralatan kecantikan mungkin bisa tolong lebih dilengkapi.. terimakasih',
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1704,
    343,
    97,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1705,
    348,
    98,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1706,
    343,
    99,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1707,
    342,
    100,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1708,
    342,
    101,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1709,
    344,
    102,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1710,
    352,
    103,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1711,
    355,
    104,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1712,
    353,
    105,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1713,
    360,
    106,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1714,
    NULL,
    107,
    48,
    NULL,
    'Semua materi sangat menyenangkan, hanya saja peralatan yg tersedia kurang mendukung.',
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1715,
    344,
    108,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1716,
    364,
    109,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1717,
    344,
    110,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1718,
    344,
    111,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1719,
    368,
    112,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1720,
    372,
    113,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1721,
    344,
    114,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1722,
    344,
    115,
    48,
    NULL,
    NULL,
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1723,
    NULL,
    116,
    48,
    NULL,
    'Instruktur sangatt memuaskann.. semoga kedepannyaa jugaa teruss seperti ituu 🤍🤍🤍',
    '2025-08-29 10:12:00',
    '2025-08-29 10:12:00'
  ),
  (
    1724,
    344,
    81,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1725,
    344,
    82,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1726,
    344,
    83,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1727,
    344,
    84,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1728,
    344,
    85,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1729,
    344,
    86,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1730,
    344,
    87,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1731,
    344,
    88,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1732,
    344,
    89,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1733,
    344,
    90,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1734,
    344,
    91,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1735,
    344,
    92,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1736,
    344,
    93,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1737,
    344,
    94,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1738,
    344,
    95,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1739,
    NULL,
    96,
    49,
    NULL,
    'Saya sangat puas',
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1740,
    344,
    97,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1741,
    348,
    98,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1742,
    344,
    99,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1743,
    344,
    100,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1744,
    344,
    101,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1745,
    344,
    102,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1746,
    351,
    103,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1747,
    355,
    104,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1748,
    353,
    105,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1749,
    360,
    106,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1750,
    NULL,
    107,
    49,
    NULL,
    'Menambah pengetahuan saya',
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1751,
    344,
    108,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1752,
    364,
    109,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1753,
    344,
    110,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1754,
    344,
    111,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1755,
    368,
    112,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1756,
    372,
    113,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1757,
    344,
    114,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1758,
    344,
    115,
    49,
    NULL,
    NULL,
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1759,
    NULL,
    116,
    49,
    NULL,
    'Instruktur sangat disiplin',
    '2025-08-29 10:22:28',
    '2025-08-29 10:22:28'
  ),
  (
    1760,
    343,
    81,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1761,
    343,
    82,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1762,
    344,
    83,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1763,
    343,
    84,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1764,
    343,
    85,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1765,
    344,
    86,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1766,
    344,
    87,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1767,
    343,
    88,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1768,
    343,
    89,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1769,
    343,
    90,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1770,
    343,
    91,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1771,
    343,
    92,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1772,
    343,
    93,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1773,
    343,
    94,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1774,
    343,
    95,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  );

INSERT INTO
  `jawaban_users` (
    `id`,
    `opsi_jawabans_id`,
    `pertanyaan_id`,
    `percobaan_id`,
    `nilai_jawaban`,
    `jawaban_teks`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1775,
    NULL,
    96,
    50,
    NULL,
    '1 minggu disini sangatt seruu dan menyenangkan. semoga uptptkk semakin baik kedepannya',
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1776,
    343,
    97,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1777,
    348,
    98,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1778,
    343,
    99,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1779,
    344,
    100,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1780,
    344,
    101,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1781,
    343,
    102,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1782,
    351,
    103,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1783,
    356,
    104,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1784,
    353,
    105,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1785,
    359,
    106,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1786,
    NULL,
    107,
    50,
    NULL,
    'materi yang saya dapat selama 1 minggu disini sangat bermanfaat sekali',
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1787,
    343,
    108,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1788,
    363,
    109,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1789,
    343,
    110,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1790,
    343,
    111,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1791,
    367,
    112,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1792,
    371,
    113,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1793,
    343,
    114,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1794,
    343,
    115,
    50,
    NULL,
    NULL,
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1795,
    NULL,
    116,
    50,
    NULL,
    'sangat memuaskan',
    '2025-08-29 10:43:30',
    '2025-08-29 10:43:30'
  ),
  (
    1796,
    343,
    81,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1797,
    343,
    82,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1798,
    344,
    83,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1799,
    344,
    84,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1800,
    343,
    85,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1801,
    344,
    86,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1802,
    343,
    87,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1803,
    343,
    88,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1804,
    344,
    89,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1805,
    344,
    90,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1806,
    344,
    91,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1807,
    344,
    92,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1808,
    344,
    93,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1809,
    344,
    94,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1810,
    344,
    95,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1811,
    NULL,
    96,
    51,
    NULL,
    'Pesan : semoga sukses selalu dalam kegiatan apapun\r\nKesan : saya suka dengan orang orang di sini, ramah dan mengayomi',
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1812,
    343,
    97,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1813,
    348,
    98,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1814,
    343,
    99,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1815,
    343,
    100,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1816,
    343,
    101,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1817,
    343,
    102,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1818,
    351,
    103,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1819,
    355,
    104,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1820,
    354,
    105,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1821,
    360,
    106,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1822,
    NULL,
    107,
    51,
    NULL,
    'Materi yang diberikan sangat bermanfaat bagi saya, sehingga ilmu saya bertambah',
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1823,
    343,
    108,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1824,
    364,
    109,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1825,
    343,
    110,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1826,
    343,
    111,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1827,
    367,
    112,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1828,
    371,
    113,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1829,
    343,
    114,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1830,
    343,
    115,
    51,
    NULL,
    NULL,
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1831,
    NULL,
    116,
    51,
    NULL,
    'Semuanya memuaskan termasuk instruktur, semoga semakin berkembang di masa depan',
    '2025-08-29 10:44:42',
    '2025-08-29 10:44:42'
  ),
  (
    1832,
    344,
    81,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1833,
    343,
    82,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1834,
    344,
    83,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1835,
    344,
    84,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1836,
    344,
    85,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1837,
    344,
    86,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1838,
    344,
    87,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1839,
    344,
    88,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1840,
    344,
    89,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1841,
    344,
    90,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1842,
    344,
    91,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1843,
    344,
    92,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1844,
    344,
    93,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1845,
    344,
    94,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1846,
    344,
    95,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1847,
    NULL,
    96,
    52,
    NULL,
    'Tetap semangat semuanya, semoga kita bisa bertemu kembali',
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1848,
    344,
    97,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1849,
    348,
    98,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1850,
    344,
    99,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1851,
    344,
    100,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1852,
    344,
    101,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1853,
    343,
    102,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1854,
    351,
    103,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1855,
    355,
    104,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1856,
    353,
    105,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1857,
    360,
    106,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1858,
    NULL,
    107,
    52,
    NULL,
    'Tetap semangat semuanya, semoga kita bisa bertemu kembali',
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1859,
    344,
    108,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1860,
    364,
    109,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1861,
    344,
    110,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1862,
    343,
    111,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1863,
    367,
    112,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1864,
    371,
    113,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1865,
    343,
    114,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1866,
    344,
    115,
    52,
    NULL,
    NULL,
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1867,
    NULL,
    116,
    52,
    NULL,
    'Tetap semangat semuanya, semoga kita bisa bertemu kembali',
    '2025-08-29 17:29:35',
    '2025-08-29 17:29:35'
  ),
  (
    1868,
    343,
    81,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1869,
    343,
    82,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1870,
    343,
    83,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1871,
    343,
    84,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1872,
    343,
    85,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1873,
    343,
    86,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1874,
    343,
    87,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1875,
    343,
    88,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1876,
    343,
    89,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1877,
    343,
    90,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1878,
    342,
    91,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1879,
    343,
    92,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1880,
    343,
    93,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1881,
    343,
    94,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1882,
    343,
    95,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1883,
    NULL,
    96,
    53,
    NULL,
    'kesan : sangat senang, bahagia, dan mendapat kan teman baru\r\npesan : air kamar mandi di sebelah mawar 1 agak butek, bisa di perbaiki lagi🙏🏻',
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1884,
    343,
    97,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1885,
    348,
    98,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1886,
    343,
    99,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1887,
    343,
    100,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1888,
    343,
    101,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1889,
    343,
    102,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1890,
    351,
    103,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1891,
    355,
    104,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1892,
    353,
    105,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1893,
    359,
    106,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1894,
    NULL,
    107,
    53,
    NULL,
    '-',
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1895,
    343,
    108,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1896,
    363,
    109,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1897,
    343,
    110,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1898,
    343,
    111,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1899,
    368,
    112,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1900,
    372,
    113,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1901,
    343,
    114,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1902,
    343,
    115,
    53,
    NULL,
    NULL,
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1903,
    NULL,
    116,
    53,
    NULL,
    '-',
    '2025-08-29 22:32:02',
    '2025-08-29 22:32:02'
  ),
  (
    1904,
    344,
    81,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1905,
    344,
    82,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1906,
    344,
    83,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1907,
    344,
    84,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1908,
    344,
    85,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1909,
    344,
    86,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1910,
    344,
    87,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1911,
    344,
    88,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1912,
    343,
    89,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1913,
    343,
    90,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1914,
    344,
    91,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1915,
    343,
    92,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1916,
    344,
    93,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1917,
    344,
    94,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1918,
    344,
    95,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1919,
    NULL,
    96,
    54,
    NULL,
    'masyaallah bagus banget tempatnya nyaman bersih luas, pembimbing nya ramah ramah dan peduli',
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1920,
    344,
    97,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1921,
    348,
    98,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1922,
    344,
    99,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1923,
    344,
    100,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1924,
    343,
    101,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1925,
    344,
    102,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1926,
    352,
    103,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1927,
    356,
    104,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1928,
    353,
    105,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1929,
    360,
    106,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1930,
    NULL,
    107,
    54,
    NULL,
    'mesin di bengkel banyak ada beberapa yang bagus banget merek Jack, sedangkan yang Juki menurutku agak kurang ada yah terlalu cepat ada yang eror menurutku itu perlu diperbaiki dan cuman ada 1 mesin aja yang bisa dipakai buat isi sepul',
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1931,
    344,
    108,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1932,
    364,
    109,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1933,
    344,
    110,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1934,
    344,
    111,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1935,
    368,
    112,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1936,
    372,
    113,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1937,
    344,
    114,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1938,
    343,
    115,
    54,
    NULL,
    NULL,
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1939,
    NULL,
    116,
    54,
    NULL,
    'menurutku instruktur sudah bagus apalagi Bu Ita menurutku dia sangat sangat membantu dan menjalankan tugasnya dengan sangat baik',
    '2025-08-29 23:00:49',
    '2025-08-29 23:00:49'
  ),
  (
    1940,
    343,
    81,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1941,
    343,
    82,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1942,
    343,
    83,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1943,
    343,
    84,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1944,
    343,
    85,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1945,
    343,
    86,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1946,
    343,
    87,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1947,
    344,
    88,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1948,
    344,
    89,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1949,
    344,
    90,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1950,
    344,
    91,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1951,
    344,
    92,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1952,
    344,
    93,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1953,
    344,
    94,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1954,
    344,
    95,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1955,
    NULL,
    96,
    55,
    NULL,
    'Saya sangat senang dengan adanya pelatihan ini',
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1956,
    344,
    97,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1957,
    348,
    98,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1958,
    344,
    99,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1959,
    344,
    100,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1960,
    344,
    101,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1961,
    344,
    102,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1962,
    351,
    103,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1963,
    355,
    104,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1964,
    353,
    105,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1965,
    360,
    106,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1966,
    NULL,
    107,
    55,
    NULL,
    'Semoga lebih bagus lagi kedepannya..\r\nSaya sangat senang mengikuti pelatihan',
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1967,
    343,
    108,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1968,
    364,
    109,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1969,
    343,
    110,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1970,
    344,
    111,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1971,
    368,
    112,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1972,
    372,
    113,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1973,
    344,
    114,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1974,
    343,
    115,
    55,
    NULL,
    NULL,
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1975,
    NULL,
    116,
    55,
    NULL,
    'Saya sangat senang',
    '2025-09-01 04:21:36',
    '2025-09-01 04:21:36'
  ),
  (
    1976,
    344,
    81,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1977,
    344,
    82,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1978,
    344,
    83,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1979,
    344,
    84,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1980,
    344,
    85,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1981,
    344,
    86,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1982,
    344,
    87,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1983,
    344,
    88,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1984,
    342,
    89,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1985,
    344,
    91,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1986,
    344,
    92,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1987,
    344,
    93,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1988,
    344,
    94,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1989,
    344,
    95,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1990,
    NULL,
    96,
    56,
    NULL,
    'cukup puas dg fasilitas yang ada',
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1991,
    344,
    97,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1992,
    348,
    98,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1993,
    344,
    99,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1994,
    344,
    100,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1995,
    344,
    101,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1996,
    344,
    102,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1997,
    352,
    103,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1998,
    355,
    104,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    1999,
    353,
    105,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2000,
    360,
    106,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2001,
    NULL,
    107,
    56,
    NULL,
    'saya mendapat hal hal baru',
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2002,
    344,
    108,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2003,
    364,
    109,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2004,
    344,
    110,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2005,
    344,
    111,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2006,
    368,
    112,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2007,
    372,
    113,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2008,
    344,
    114,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2009,
    344,
    115,
    56,
    NULL,
    NULL,
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2010,
    NULL,
    116,
    56,
    NULL,
    'semua baik',
    '2025-09-01 04:26:47',
    '2025-09-01 04:26:47'
  ),
  (
    2011,
    344,
    81,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2012,
    344,
    82,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2013,
    344,
    83,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2014,
    344,
    84,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2015,
    344,
    85,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2016,
    344,
    86,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2017,
    344,
    87,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2018,
    344,
    88,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2019,
    344,
    89,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2020,
    344,
    90,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2021,
    344,
    91,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2022,
    344,
    92,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2023,
    344,
    93,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2024,
    344,
    94,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2025,
    344,
    95,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2026,
    NULL,
    96,
    57,
    NULL,
    'semogaa suksek selalu',
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2027,
    344,
    97,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2028,
    348,
    98,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2029,
    344,
    99,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2030,
    344,
    100,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2031,
    344,
    101,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2032,
    344,
    102,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2033,
    352,
    103,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2034,
    356,
    104,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2035,
    353,
    105,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2036,
    360,
    106,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2037,
    NULL,
    107,
    57,
    NULL,
    'suksek selalu',
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2038,
    344,
    108,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2039,
    364,
    109,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2040,
    344,
    110,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2041,
    344,
    111,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2042,
    368,
    112,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2043,
    372,
    113,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2044,
    344,
    114,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2045,
    344,
    115,
    57,
    NULL,
    NULL,
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2046,
    NULL,
    116,
    57,
    NULL,
    'sukses selalu',
    '2025-09-01 04:27:08',
    '2025-09-01 04:27:08'
  ),
  (
    2047,
    344,
    81,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2048,
    344,
    82,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2049,
    344,
    83,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2050,
    344,
    84,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2051,
    344,
    85,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2052,
    344,
    86,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2053,
    344,
    87,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2054,
    344,
    88,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2055,
    344,
    89,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2056,
    344,
    90,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2057,
    344,
    91,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2058,
    344,
    92,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2059,
    344,
    93,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2060,
    344,
    94,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2061,
    344,
    95,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2062,
    NULL,
    96,
    58,
    NULL,
    'semogaa suksek selalu',
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2063,
    344,
    97,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2064,
    348,
    98,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2065,
    344,
    99,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2066,
    344,
    100,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2067,
    344,
    101,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2068,
    344,
    102,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2069,
    352,
    103,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2070,
    356,
    104,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2071,
    353,
    105,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2072,
    360,
    106,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2073,
    NULL,
    107,
    58,
    NULL,
    'suksek selalu',
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2074,
    344,
    108,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2075,
    364,
    109,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2076,
    344,
    110,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2077,
    344,
    111,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2078,
    368,
    112,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2079,
    372,
    113,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2080,
    344,
    114,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2081,
    344,
    115,
    58,
    NULL,
    NULL,
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2082,
    NULL,
    116,
    58,
    NULL,
    'sukses selalu',
    '2025-09-01 04:27:12',
    '2025-09-01 04:27:12'
  ),
  (
    2083,
    344,
    81,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2084,
    343,
    82,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2085,
    343,
    83,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2086,
    344,
    84,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2087,
    343,
    85,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2088,
    343,
    86,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2089,
    343,
    87,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2090,
    343,
    88,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2091,
    343,
    89,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2092,
    343,
    90,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2093,
    343,
    91,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2094,
    343,
    92,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2095,
    343,
    93,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2096,
    343,
    94,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2097,
    343,
    95,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2098,
    NULL,
    96,
    59,
    NULL,
    'membantu kita untuk belajar ketertiban dan kedisiplinan juga kita jadi banyak belajar apa yang belum di pelajari di sekolah juga',
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2099,
    343,
    97,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2100,
    348,
    98,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2101,
    344,
    99,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2102,
    344,
    100,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2103,
    343,
    101,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2104,
    344,
    102,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2105,
    352,
    103,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2106,
    356,
    104,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2107,
    354,
    105,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2108,
    359,
    106,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2109,
    NULL,
    107,
    59,
    NULL,
    'Jadi lebih banyak belajar yang kita belum ketahui',
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2110,
    343,
    108,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2111,
    363,
    109,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2112,
    343,
    110,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2113,
    343,
    111,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2114,
    367,
    112,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2115,
    371,
    113,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2116,
    343,
    114,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2117,
    343,
    115,
    59,
    NULL,
    NULL,
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2118,
    NULL,
    116,
    59,
    NULL,
    'Sangat keren',
    '2025-09-01 04:31:50',
    '2025-09-01 04:31:50'
  ),
  (
    2119,
    343,
    81,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2120,
    343,
    82,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2121,
    343,
    83,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2122,
    343,
    84,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2123,
    343,
    85,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2124,
    343,
    86,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2125,
    343,
    87,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2126,
    343,
    88,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2127,
    343,
    89,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2128,
    343,
    90,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2129,
    343,
    91,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2130,
    343,
    92,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2131,
    343,
    93,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2132,
    343,
    94,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2133,
    343,
    95,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2134,
    NULL,
    96,
    60,
    NULL,
    'kemungkinan untuk yang saya sampaikan agar UPT PPTK semakin maju dan memberikan peluang lagi untuk siswa dapat mengambil ilmu sebanyak\" di sini',
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2135,
    343,
    97,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2136,
    348,
    98,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2137,
    344,
    99,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2138,
    344,
    100,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2139,
    344,
    101,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2140,
    344,
    102,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2141,
    351,
    103,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2142,
    354,
    104,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2143,
    355,
    105,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2144,
    360,
    106,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2145,
    NULL,
    107,
    60,
    NULL,
    'untuk materi yang di sampaikan sangat jelas dan mudah di pahami namun karena banyaknya materi yang masuk ada beberapa materi yang kurang di pahami',
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2146,
    343,
    108,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2147,
    363,
    109,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2148,
    344,
    110,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2149,
    344,
    111,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2150,
    368,
    112,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2151,
    372,
    113,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2152,
    344,
    114,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2153,
    344,
    115,
    60,
    NULL,
    NULL,
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2154,
    NULL,
    116,
    60,
    NULL,
    'untuk para instruktur dan pelatih di pelatihan UPT PPTK ini terima kasih sudaa memberikan materi yang sangat berguna untuk kedepannya apalagi bila kita mau buka usaha',
    '2025-09-01 04:42:30',
    '2025-09-01 04:42:30'
  ),
  (
    2155,
    344,
    81,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2156,
    343,
    82,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2157,
    344,
    83,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2158,
    344,
    84,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2159,
    344,
    85,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2160,
    344,
    86,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2161,
    344,
    87,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2162,
    344,
    88,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2163,
    344,
    89,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2164,
    344,
    90,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2165,
    344,
    91,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2166,
    344,
    92,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2167,
    344,
    93,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2168,
    344,
    94,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2169,
    344,
    95,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2170,
    NULL,
    96,
    61,
    NULL,
    'kesan : merasa senang dan bangga  atas pengalaman yang didapat selama pelantikan \r\npesan:lebih dikurangin si konsumsi snacknya😁',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2171,
    344,
    97,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2172,
    348,
    98,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2173,
    344,
    99,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2174,
    344,
    100,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2175,
    344,
    101,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2176,
    344,
    102,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2177,
    352,
    103,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2178,
    355,
    104,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2179,
    353,
    105,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2180,
    360,
    106,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2181,
    NULL,
    107,
    61,
    NULL,
    'pesan dan kesan: terimakasi atas semua dukungan dan kerjasama selama pelantikan',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2182,
    344,
    108,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2183,
    364,
    109,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2184,
    344,
    110,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2185,
    344,
    111,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2186,
    368,
    112,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2187,
    372,
    113,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2188,
    344,
    114,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2189,
    344,
    115,
    61,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2190,
    NULL,
    116,
    61,
    NULL,
    'terimakasi banyak atas semua ilmu yang telah diberikan kepada saya',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2191,
    344,
    81,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2192,
    343,
    82,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2193,
    344,
    83,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2194,
    344,
    84,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2195,
    344,
    85,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2196,
    344,
    86,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2197,
    344,
    87,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2198,
    344,
    88,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2199,
    344,
    89,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2200,
    344,
    90,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2201,
    344,
    91,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2202,
    344,
    92,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2203,
    344,
    93,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2204,
    344,
    94,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2205,
    344,
    95,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2206,
    NULL,
    96,
    62,
    NULL,
    'kesan : merasa senang dan bangga  atas pengalaman yang didapat selama pelantikan \r\npesan:lebih dikurangin si konsumsi snacknya😁',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2207,
    344,
    97,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2208,
    348,
    98,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2209,
    344,
    99,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2210,
    344,
    100,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2211,
    344,
    101,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2212,
    344,
    102,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2213,
    352,
    103,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2214,
    355,
    104,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2215,
    353,
    105,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2216,
    360,
    106,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2217,
    NULL,
    107,
    62,
    NULL,
    'pesan dan kesan: terimakasi atas semua dukungan dan kerjasama selama pelantikan',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2218,
    344,
    108,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2219,
    364,
    109,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2220,
    344,
    110,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2221,
    344,
    111,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2222,
    368,
    112,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2223,
    372,
    113,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2224,
    344,
    114,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2225,
    344,
    115,
    62,
    NULL,
    NULL,
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2226,
    NULL,
    116,
    62,
    NULL,
    'terimakasi banyak atas semua ilmu yang telah diberikan kepada saya',
    '2025-09-01 04:48:55',
    '2025-09-01 04:48:55'
  ),
  (
    2227,
    344,
    81,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2228,
    344,
    82,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2229,
    344,
    83,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2230,
    344,
    84,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2231,
    344,
    85,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2232,
    344,
    86,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2233,
    344,
    87,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2234,
    344,
    88,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2235,
    344,
    89,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2236,
    344,
    90,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2237,
    344,
    91,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2238,
    344,
    92,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2239,
    344,
    93,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2240,
    344,
    94,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2241,
    344,
    95,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2242,
    NULL,
    96,
    63,
    NULL,
    'Pesan Snack nya dikasih air\r\nKesan makanan enak lezat',
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2243,
    344,
    97,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2244,
    348,
    98,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2245,
    344,
    99,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2246,
    344,
    100,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2247,
    344,
    101,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2248,
    344,
    102,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2249,
    351,
    103,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2250,
    355,
    104,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2251,
    353,
    105,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2252,
    360,
    106,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2253,
    NULL,
    107,
    63,
    NULL,
    'Pesan lebih baik lagi kedepannya\r\nKesan pembelajarannya enak, dapat ilmu baru, teman baru, dan guru baru',
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2254,
    344,
    108,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2255,
    364,
    109,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2256,
    344,
    110,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2257,
    344,
    111,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2258,
    368,
    112,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2259,
    372,
    113,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2260,
    344,
    114,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2261,
    344,
    115,
    63,
    NULL,
    NULL,
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2262,
    NULL,
    116,
    63,
    NULL,
    'Pesan peserta di kasih selimut soalnya banyak nyamuk\r\nKesan instrukturnya baik,sopan,dan sabar',
    '2025-09-01 04:58:35',
    '2025-09-01 04:58:35'
  ),
  (
    2263,
    344,
    81,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2264,
    344,
    82,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2265,
    344,
    83,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2266,
    344,
    84,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2267,
    344,
    85,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2268,
    344,
    86,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2269,
    344,
    87,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2270,
    344,
    88,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2271,
    344,
    89,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2272,
    344,
    90,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2273,
    344,
    91,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2274,
    344,
    92,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2275,
    344,
    93,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2276,
    344,
    94,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2277,
    344,
    95,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2278,
    NULL,
    96,
    64,
    NULL,
    'Kesan\r\n\r\nSelama mengikuti kegiatan ini, saya merasa sangat senang dan mendapatkan banyak pengalaman berharga. Selain ilmu pengetahuan, saya juga belajar tentang kerja sama, tanggung jawab, dan kekompakan. Semua ini memberikan kesan mendalam yang akan selalu saya ingat.\r\n\r\nPesan\r\n\r\nSemoga kegiatan seperti ini dapat terus dilaksanakan di masa mendatang dengan persiapan yang lebih baik, sehingga manfaat yang diperoleh semakin maksimal. Saya juga berharap hubungan baik yang terjalin di antara guru, teman, maupun panitia dapat selalu terjaga.',
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2279,
    344,
    97,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2280,
    348,
    98,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2281,
    344,
    99,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2282,
    344,
    100,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2283,
    344,
    101,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2284,
    344,
    102,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2285,
    352,
    103,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2286,
    356,
    104,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2287,
    356,
    105,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2288,
    360,
    106,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2289,
    NULL,
    107,
    64,
    NULL,
    'Kesan:\r\nSaya merasa sangat senang dan terbantu ketika dapat belajar bersama dengan seseorang yang ahli di bidangnya. Hal tersebut membuat saya lebih mudah memahami materi dan termotivasi untuk terus belajar.\r\n\r\nKalau mau tetap singkat, versi ringkasnya:\r\nSaya sangat senang dapat belajar bersama orang yang ahli, karena hal itu membuat saya lebih paham dan termotivasi.',
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2290,
    344,
    108,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2291,
    364,
    109,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2292,
    344,
    110,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2293,
    344,
    111,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2294,
    368,
    112,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2295,
    372,
    113,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2296,
    344,
    114,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2297,
    344,
    115,
    64,
    NULL,
    NULL,
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  ),
  (
    2298,
    NULL,
    116,
    64,
    NULL,
    'baik',
    '2025-09-01 05:08:53',
    '2025-09-01 05:08:53'
  );


