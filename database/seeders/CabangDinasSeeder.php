<?php

namespace Database\Seeders;

use App\Models\CabangDinas;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now();

        $data = [
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Pasuruan',
                'alamat' => 'Jl. Panglima Sudirman No.54, Kecamatan Purworejo, Kota Pasuruan, Jawa Timur',
                'laman' => 'https://pasuruancab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Probolinggo',
                'alamat' => 'Jl. Anggur, Wonoasih, Kecamatan Wonoasih, Kota Probolinggo, Jawa Timur 67232',
                'laman' => 'https://probolinggocab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Bondowoso - Situbondo',
                'alamat' => 'Jl. HOS Cokroaminoto No.121, Gudangmas, Kademangan, Kecamatan Bondowoso, Kabupaten Bondowoso, Jawa Timur 68217',
                'laman' => 'https://bondowosocab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Jember - Lumajang',
                'alamat' => 'Jl. Kalimantan No.42, Tegalboto, Krajan Timur, Kecamatan Sumbersari, Kabupaten Jember 68121',
                'laman' => 'https://cabdindikwilayahjember.com/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Banyuwangi',
                'alamat' => 'Jl. Basuki Rahmat No.46, Lateng, Kecamatan Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68414',
                'laman' => 'https://www.instagram.com/cabdinbanyuwangi/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Kabupaten Malang',
                'alamat' => 'Jl. Simpang Ijen No.2 Oro-oro Dowo, Kecamatan Klojen, Malang, Jawa Timur 65119',
                'laman' => 'https://malangcab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Malang - Batu',
                'alamat' => 'Jl. Anjasmoro No.40, Oro-oro Dowo, Kecamatan Klojen, Kota Malang, Jawa Timur 65119',
                'laman' => 'https://batu-malangkotacab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Blitar',
                'alamat' => 'Jl. Sultan Agung No.66, Sananwetan, Kecamatan Sananwetan, Kota Blitar, Jawa Timur 66137',
                'laman' => null, // Tidak ada laman pada data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Tulungagung - Trenggalek',
                'alamat' => 'Jl. Diponegoro, Tromertan, Krajan, Surodakan, Kecamatan Trenggalek, Kabupaten Trenggalek, Jawa Timur 66316',
                'laman' => null, // Tidak ada laman pada data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Kediri',
                'alamat' => 'Jl. Jaksa Agung Suprapto No.2, Mojoroto, Kecamatan Kediri, Kota Kediri, Jawa Timur 64112',
                'laman' => 'https://kediricab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Nganjuk',
                'alamat' => 'Jl. Brantas, Gg. III, Ngrengket, Kecamatan Sukomoro, Kabupaten Nganjuk, Jawa Timur 64481',
                'laman' => 'https://nganjukcab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Madiun - Ngawi',
                'alamat' => 'Jl. Pahlawan No.31, Kartoharjo, Kecamatan Kartoharjo, Kota Madiun, Jawa Timur 63121',
                'laman' => 'https://www.instagram.com/cabdindik_madiun/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Ponorogo - Magetan',
                'alamat' => 'Jl. Gajah Mada No.40, Pesantren. Surodikraman, Kecamatan Ponorogo, Kabupaten Ponorogo, Jawa Timur 63419',
                'laman' => null, // Tidak ada laman pada data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Pacitan',
                'alamat' => 'Jl. Raden Saleh, Kabupaten Pacitan, Jawa Timur',
                'laman' => 'https://pacitancab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Bojonegoro - Tuban',
                'alamat' => 'Jl. Panglima Sudirman No.36, Kelurahan Baturetno, Kecamatan Tuban, Kabupaten Tuban, Jawa Timur',
                'laman' => 'https://tubancab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Lamongan',
                'alamat' => 'Jl. Kombespol M. Duryat No.7, Kabupaten Lamongan, Jawa Timur',
                'laman' => null, // Tidak ada laman pada data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Gresik',
                'alamat' => 'Jl. DR. Wahidin Sudiro Husodo No.229, Kembangan, Kecamatan Kebomas, Kabupaten Gresik, Jawa Timur 61124',
                'laman' => 'https://gresikcab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Sidoarjo',
                'alamat' => 'Jl. Ponti No.09, Lingkar Barat, Kabupaten Sidoarjo, Provinsi Jawa Timur',
                'laman' => 'https://sidoarjocab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Jombang',
                'alamat' => 'Jl. DR. Wahidin Sudirohusodo No.6, Sengon, Kabupaten Jombang, Jawa Timur 61419',
                'laman' => 'https://www.instagram.com/cabdin_jombang/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Mojokerto',
                'alamat' => 'Jl. Hayam Wuruk No.66, Mergelo, Kecamatan Magersari, Mojokerto, Jawa Timur 61318',
                'laman' => 'https://www.instagram.com/', // URL umum, sesuai data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Bangkalan',
                'alamat' => 'Jl. Soekarno Hatta No.16, Mlajah, Kecamatan Bangkalan, Kabupaten Bangkalan, Jawa Timur 69116',
                'laman' => null, // Tidak ada laman pada data sumber
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Sampang',
                'alamat' => 'Jl. Merpati No.5, Kabupaten Sampang, Jawa Timur 69216',
                'laman' => 'https://www.instagram.com/cabdin_sampang/?hl=id',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Pamekasan',
                'alamat' => 'Jl. Slamet Riyadi No.01, Pamekasan Jawa Timur 69313',
                'laman' => 'https://pamekasancab.dindik.jatimprov.go.id/',
            ],
            [
                'nama' => 'Cabang Dinas Pendidikan Wilayah Sumenep',
                'alamat' => 'Jl. Urip Sumoharjo, Mastasek, Pabian, Kecamatan Kota Sumenep, Kabupaten Sumenep, Jawa Timur 69417',
                'laman' => 'https://www.instagram.com/cabdin_sumenep/',
            ],
        ];

        CabangDinas::insert($data);
    }
}
