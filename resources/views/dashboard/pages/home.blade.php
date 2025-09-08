@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

{{-- Konten Dashboard dari Desain Baru --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- BARIS PERTAMA: KARTU AKSI UTAMA -->

    <!-- Card Pre-Test -->
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
        <div>
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
            </div>
            <p class="text-gray-500 mt-2 mb-4">Cek kesiapanmu sebelum mengikuti materi.</p>
        </div>
        <a href="{{ route('dashboard.pretest.index') }}" class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
            Kerjakan Pre-Test
        </a>
    </div>

    <!-- Card Post-Test -->
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
        <div>
             <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800">Post-Test</h3>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
            </div>
            <p class="text-gray-500 mt-2 mb-4">Evaluasi hasil belajarmu untuk peningkatan.</p>
        </div>
        <a href="{{ route('dashboard.posttest.index') }}" class="w-full block text-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors">
            Mulai Post-Test
        </a>
    </div>


    <!-- Card Survey Hari Ini -->
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
       <div>
         <div class="flex justify-between items-start">
             <h3 class="text-xl font-bold text-gray-800">MONEV</h3>
             <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
         </div>
         <p class="text-gray-500 mt-2">Akses Monitoring dan Evaluasi Selama Mengikuti Pelatihan. </p>
       </div>
       <a href="{{ route('dashboard.materi') }}" class="mt-6 block text-center w-full lg:w-auto bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
            Mulai Survey
       </a>
    </div>


    <!-- BARIS KEDUA: KARTU PROGRES -->


    <!-- Card Progress Pre-Test -->
     <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
        <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
         <div class="flex items-baseline mt-2">
            {{-- DATA DINAMIS BISA DITARUH DI SINI --}}
            <span class="text-3xl font-bold text-yellow-600">2</span>
            <span class="text-lg text-gray-500 ml-1">/ 3 dikerjakan</span>
        </div>
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
             {{-- Ganti 'width' dengan persentase progres pre-test --}}
            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 66%"></div>
        </div>
    </div>

    <!-- Card Progress Post-Test -->
    <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
       <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
        <div class="flex items-center mt-2">
           {{-- Ganti dengan logic @if, contoh: --}}
           {{-- @if($postTestDone) --}}
           {{--    <span class="text-lg font-semibold text-green-600">Sudah dikerjakan ✔️</span> --}}
           {{-- @else --}}
           <span class="text-lg font-semibold text-red-600">Belum dikerjakan</span>
           {{-- @endif --}}
       </div>
       <!-- Progress Bar -->
       <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
            {{-- Ganti 'width' dengan 0% atau 100% berdasarkan status --}}
            {{-- style="width: {{ $postTestDone ? '100%' : '0%' }}" --}}
           <div class="bg-green-500 h-2.5 rounded-full" style="width: 0%"></div>
       </div>
   </div>

  <!-- Card Progress Survey -->
    <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:transform hover:-translate-y-1 hover:shadow-lg">
       <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
        <div class="flex items-center mt-2">
           {{-- Ganti dengan logic @if, contoh: --}}
           {{-- @if($postTestDone) --}}
           {{--    <span class="text-lg font-semibold text-green-600">Sudah dikerjakan ✔️</span> --}}
           {{-- @else --}}
           <span class="text-lg font-semibold text-red-600">Belum dikerjakan</span>
           {{-- @endif --}}
       </div>
       <!-- Progress Bar -->
       <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
            {{-- Ganti 'width' dengan 0% atau 100% berdasarkan status --}}
            {{-- style="width: {{ $postTestDone ? '100%' : '0%' }}" --}}
           <div class="bg-green-500 h-2.5 rounded-full" style="width: 0%"></div>
       </div>
   </div>
</div>

@endsection

