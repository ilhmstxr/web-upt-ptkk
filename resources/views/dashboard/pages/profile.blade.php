@extends('dashboard.layouts.main')
@section('title', 'Profile')
@section('page-title', 'Profil Peserta')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-semibold text-xl mb-4">Data Diri</h2>

    @if(isset($peserta))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Nama:</strong> {{ $peserta->nama }}</div>
            <div><strong>NIK:</strong> {{ $peserta->nik }}</div>
            <div><strong>Tempat/Tanggal Lahir:</strong> {{ $peserta->tempat_lahir }}, {{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d M Y') }}</div>
            <div><strong>Jenis Kelamin:</strong> {{ $peserta->jenis_kelamin }}</div>
            <div><strong>Agama:</strong> {{ $peserta->agama }}</div>
            <div><strong>Alamat:</strong> {{ $peserta->alamat }}</div>
            <div><strong>No HP:</strong> {{ $peserta->no_hp }}</div>
            <div><strong>Email:</strong> {{ $peserta->email }}</div>
        </div>
    @else
        <p class="text-gray-600">Data peserta belum tersedia.</p>
    @endif
</div>
@endsection
