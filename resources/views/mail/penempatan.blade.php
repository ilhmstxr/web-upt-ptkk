{{-- resources/views/emails/penempatan.blade.php --}}
<p>Halo {{ $penempatan->pendaftaran->peserta->nama }},</p>
<p>Berikut penempatan asrama Anda:</p>
<ul>
    <li>Asrama: {{ $penempatan->kamar->asrama->name }}</li>
    <li>Kamar: No. {{ $penempatan->kamar->nomor_kamar }}</li>
    <li>Bed: {{ $penempatan->bed_no }}</li>
</ul>
<p>Terima kasih.</p>
