<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - UPT PTKK Jawa Timur</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .font-volkhov { font-family: 'Volkhov', serif; }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(16px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-up { animation: fadeInUp .8s ease-out both; }
        .animate-up-2 { animation: fadeInUp .8s ease-out .12s both; }
        .animate-up-3 { animation: fadeInUp .8s ease-out .24s both; }
        .animate-up-4 { animation: fadeInUp .8s ease-out .36s both; }
    </style>
</head>

<body class="bg-[#F1F9FC] min-h-screen">
@php
    use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable;
    $cpNama = PesertaPelatihanTable::DEFAULT_CP_NAMA;
    $cpPhone = PesertaPelatihanTable::DEFAULT_CP_PHONE;
    $cpDigits = preg_replace('/\D+/', '', $cpPhone);
    if (str_starts_with($cpDigits, '0')) {
        $cpDigits = '62' . substr($cpDigits, 1);
    }
    $waUrl = $cpDigits ? 'https://wa.me/' . $cpDigits : '#';
@endphp

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl border-2 border-[#1524AF] shadow-xl p-6 sm:p-8 md:p-10">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="w-20 h-20 rounded-full bg-[#DBE7F7] flex items-center justify-center animate-up">
                <svg class="w-10 h-10 text-[#1524AF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M8 12l2.5 2.5L16 9"></path>
                </svg>
            </div>

            <h1 class="font-volkhov text-[#1524AF] text-2xl sm:text-3xl font-bold animate-up-2">
                Pendaftaran Berhasil
            </h1>
            <p class="text-[#374151] text-sm sm:text-base leading-relaxed animate-up-3">
                Terima kasih telah mendaftar di UPT PTKK. Simpan nomor registrasi Anda untuk login assessment.
            </p>
        </div>

        <div class="mt-6 bg-[#F3F7FF] border border-[#C9D8F2] rounded-xl p-5 text-center animate-up-3">
            <div class="text-xs uppercase tracking-widest text-[#1524AF] font-semibold">
                Nomor Registrasi
            </div>
            <div class="mt-2 flex flex-wrap items-center justify-center gap-3">
                <div id="regNumber" class="font-volkhov text-[#0F1E7A] text-3xl sm:text-4xl md:text-5xl tracking-wider">
                    {{ $pendaftaran->nomor_registrasi }}
                </div>
                <button type="button" id="copyBtn"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-[#1524AF] text-[#1524AF] text-sm font-semibold hover:bg-[#1524AF] hover:text-white transition">
                    Salin
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                        <rect x="3" y="3" width="13" height="13" rx="2"></rect>
                    </svg>
                </button>
            </div>
            <div id="copyNote" class="text-xs text-[#4B5563] mt-2">Klik untuk menyalin nomor registrasi.</div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 animate-up-4">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs uppercase tracking-wide text-slate-500">Nama Peserta</div>
                <div class="mt-1 font-semibold text-slate-800">{{ $pendaftaran->peserta->nama }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs uppercase tracking-wide text-slate-500">Pelatihan</div>
                <div class="mt-1 font-semibold text-slate-800">
                    {{ $pendaftaran->pelatihan->nama_pelatihan ?? 'Pelatihan Kompetensi Vokasi' }}
                </div>
            </div>
        </div>

        <div class="mt-6 bg-[#FFF7E6] border border-[#F5D48B] rounded-xl p-4 text-sm text-[#7A4B00] animate-up-4">
            <strong>Catatan:</strong> Ingat untuk membuka laman assessment menggunakan nomor registrasi di atas.
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3 animate-up-4">
            <a href="{{ route('assessment.login') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-[#1524AF] text-white text-sm font-semibold shadow hover:bg-[#0F1E7A] transition">
                Buka Laman Assessment
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14"></path>
                    <path d="M15 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-[#1524AF] text-[#1524AF] text-sm font-semibold hover:bg-[#DBE7F7] transition">
                Kembali ke Beranda
            </a>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 p-4 text-sm text-slate-700 animate-up-4">
            <div class="font-semibold text-[#1524AF]">Kontak Panitia</div>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <span class="font-medium">{{ $cpNama }}</span>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-[#25D366] text-white text-xs font-semibold hover:bg-[#1DA851] transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20.5 3.5A11 11 0 0 0 2.8 17.3L2 22l4.8-1.2A11 11 0 1 0 20.5 3.5Zm-8.6 16.2a9.1 9.1 0 0 1-4.6-1.2l-.3-.2-2.8.7.7-2.7-.2-.3a9.1 9.1 0 1 1 7.2 3.7Zm5-6.6c-.3-.1-1.7-.8-2-1s-.5-.1-.7.1-.8 1-.9 1.1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.4 8.4 0 0 1-1.6-2c-.2-.3 0-.5.1-.6.1-.1.3-.3.4-.4.1-.1.2-.3.3-.4.1-.1.1-.3 0-.5s-.7-1.6-1-2.2c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.7.3-.2.2-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.8 4.3.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.3.2-1.4s-.2-.2-.5-.3Z"></path>
                    </svg>
                    {{ $cpPhone }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const copyBtn = document.getElementById('copyBtn');
    const regNumber = document.getElementById('regNumber');
    const copyNote = document.getElementById('copyNote');

    if (copyBtn && regNumber) {
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(regNumber.textContent.trim());
                if (copyNote) copyNote.textContent = 'Nomor registrasi disalin.';
            } catch (e) {
                if (copyNote) copyNote.textContent = 'Gagal menyalin. Silakan salin manual.';
            }
        });
    }
</script>
</body>
</html>
