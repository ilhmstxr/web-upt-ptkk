<table>
    <thead>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">No</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Nama Lengkap</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Email</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Asal Lembaga (Sekolah)</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Kelas</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Cabdin</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Jenis Pelatihan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Kompetensi Pelatihan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Nama Angkatan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">No HP</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f2f2f2;">Pas Foto</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendaftaran as $index => $item)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $item->peserta->nama ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->peserta->user->email ?? $item->peserta->email ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->peserta->instansi->asal_instansi ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->kelas ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->peserta->instansi->cabangDinas->nama ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->pelatihan->jenis_program ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->kompetensi->nama_kompetensi ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->pelatihan->angkatan ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->peserta->no_hp ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">
                    @php
                        $path = null;
                        if($item->peserta->lampiran && $item->peserta->lampiran->pas_foto) {
                            if(\Illuminate\Support\Facades\Storage::disk('public')->exists($item->peserta->lampiran->pas_foto)) {
                                $path = \Illuminate\Support\Facades\Storage::disk('public')->path($item->peserta->lampiran->pas_foto);
                            }
                        }
                    @endphp
                    @if($path)
                        <img src="{{ $path }}" height="80" />
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
