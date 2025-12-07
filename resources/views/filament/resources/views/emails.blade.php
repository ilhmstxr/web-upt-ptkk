<h3>Halo, {{ $pendaftaran->peserta->nama }}</h3>

<p>Selamat, status pendaftaran kamu sudah <b>DITERIMA</b>.</p>

<p>
    <b>Token / Nomor Registrasi Assessment:</b><br>
    {{ $pendaftaran->assessment_token }}
</p>

<p>
    <b>Password login:</b> tanggal lahir kamu (format <b>ddmmyyyy</b>)<br>
    contoh: 01052007
</p>

<p>Silakan login di halaman dashboard assessment.</p>
