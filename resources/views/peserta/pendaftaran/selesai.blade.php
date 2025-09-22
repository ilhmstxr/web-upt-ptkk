<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - UPT PTKK Jawa Timur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe, #bae6fd, #f0f9ff);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            min-height: 100vh;
            margin: 0;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(720deg) translateX(30px);
                opacity: 0;
            }
        }

        .checkmark-draw {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: drawCheck 1s ease-out forwards;
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* animasi scale card */
        @keyframes fadeScale {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeScale {
            animation: fadeScale 0.8s ease-out forwards;
        }

        /* animasi fade-up */
        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 1s ease-out forwards;
        }
    </style>
</head>

<body class="flex items-center justify-center p-4 sm:p-6 md:p-8">

    <div
        class="animate-fadeScale bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl
              p-6 sm:p-8 md:p-10 lg:p-12
              border border-white/20
              w-full max-w-md md:max-w-lg lg:max-w-xl text-center">
        <!-- Ikon sukses dengan centang besar & animasi -->
        <div class="mb-6">
            <div
                class="relative w-28 h-28 mx-auto flex items-center justify-center rounded-full bg-green-100 animate-pulse">
                <svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" stroke-width="3" fill="none"></circle>
                    <path class="checkmark-draw" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M9 12l2 2 4-4"></path>
                </svg>
            </div>
        </div>

        <!-- Judul -->
        <h1 class="fade-up text-2xl sm:text-3xl font-bold text-slate-800 mb-3">Pendaftaran Berhasil 🎉</h1>
        <p class="fade-up text-slate-600 text-sm sm:text-base leading-relaxed mb-6" style="animation-delay:.2s">
            Terima kasih telah melakukan pendaftaran pelatihan di UPT PTKK Dinas Pendidikan Jawa Timur. Data Anda telah
            kami terima dan akan segera kami proses untuk verifikasi.
        </p>

        <!-- Kartu info langkah berikut -->
        <div class="fade-up bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg text-left text-sm mb-6"
            style="animation-delay:.4s">
            <h4 class="font-bold mb-1 flex items-center gap-2">
                Apa langkah selanjutnya? <span class="animate-bounce">📧</span>
            </h4>
            <p>
                Mohon periksa email Anda secara berkala. Info jadwal pelatihan & tautan grup WhatsApp akan kami kirim ke
                email yang didaftarkan.
            </p>
        </div>

        <!-- Detail pendaftaran -->
        <div class="fade-up bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm"
            style="animation-delay:.6s">
            <div class="flex items-center gap-2 mb-3 justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-semibold text-slate-700">Detail Pendaftaran</span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center flex-wrap gap-1">
                    <span class="text-slate-600">Nomor Registrasi:</span>
                    <span class="font-bold text-blue-600">{{ $pendaftaran->nomor_registrasi }}</span>
                    {{-- <span class="font-bold text-blue-600">2-TTBG-001</span> --}}
                </div>
                <div class="flex justify-between items-start flex-wrap gap-1">
                    <span class="text-slate-600 flex-shrink-0">Nama Peserta:</span>
                    <span class="font-semibold text-slate-800 text-right">{{ $pendaftaran->peserta->name }}</span>
                    {{-- <span class="font-semibold text-slate-800 text-right">Ilham Bintang Herliambang</span> --}}
                </div>
            </div>
            <div class="mt-3 p-3 bg-white/70 rounded border border-blue-100 text-slate-600 text-xs">
                <strong>Pelatihan:</strong> Kegiatan Pelatihan Kompetensi Vokasi bagi Siswa SMK/SMA (MILEA) melalui
                Mobile Training Unit (MTU) Angkatan II Tahun 2025
            </div>
        </div>

        <!-- Tombol -->
        <a href="{{ url('/') }}"
            class="fade-up inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition-all transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm sm:text-base"
            style="animation-delay:.8s">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Beranda
        </a>

    </div>

    <script>
        src = "https://cdn.tailwindcss.com"
        // efek confetti
        function createConfetti() {
            const colors = ['#60a5fa', '#3b82f6', '#1d4ed8', '#2563eb', '#1e40af'];
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = confetti.style.height = (Math.random() * 8 + 4) + 'px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-20px';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '1000';
                    confetti.style.borderRadius = '50%';
                    confetti.style.animation = `confettiFall ${3 + Math.random() * 3}s ease-out forwards`;
                    document.body.appendChild(confetti);
                    setTimeout(() => confetti.remove(), 6000);
                }, i * 50);
            }
        }
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 800);
        });
    </script>

</body>

</html>
