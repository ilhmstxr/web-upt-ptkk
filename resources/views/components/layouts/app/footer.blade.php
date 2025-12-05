{{-- resources/views/components/layouts/app/footer.blade.php --}}
<footer class="bg-[#0E2A7B] text-white w-full">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] py-8 md:py-10">

    {{-- Grid Layout Custom --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-6">

      {{-- Kolom 1: Logo + Teks + Sosmed (Lebih Lebar) --}}
      <div class="lg:col-span-4 flex flex-col justify-between">
        <div class="flex items-start gap-4 sm:gap-6">
          {{-- Logo kiri --}}
          <img src="{{ asset('images/logo-provinsi-jawa-timur.png') }}"
               alt="Logo Provinsi Jawa Timur"
               class="w-[90px] sm:w-[110px] h-auto object-contain shrink-0 self-start">

          {{-- Blok teks + sosmed --}}
          <div class="flex flex-col justify-between h-full py-1 w-full">
            {{-- Teks --}}
            <div class="flex flex-col text-left">
              <p class="font-[Volkhov] font-extrabold text-[20px] sm:text-[22px] md:text-[24px] text-[#FFFFFF] leading-snug mb-2 sm:mb-3">
                UPT. PTKK
              </p>
              <div class="space-y-0.5 sm:space-y-1">
                <p class="font-[Montserrat] text-[#FFFFFF] text-[14px] sm:text-[15px] md:text-[16px] leading-relaxed font-medium">
                  Dinas Pendidikan Prov.
                </p>
                <p class="font-[Montserrat] text-[#FFFFFF] text-[14px] sm:text-[15px] md:text-[16px] leading-relaxed font-medium">
                  Jawa Timur
                </p>
              </div>
            </div>

            {{-- Sosmed (tetap kuning) --}}
            <div class="flex items-center gap-3 mt-4 sm:mt-6">
              <a href="#"
                 class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-md border-2 border-[#F2C94C]
                        text-[#F2C94C] hover:bg-[#F2C94C] hover:text-[#0E2A7B] transition">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                  <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5a5 5 0 100 10 5 5 0 000-10zm6-1a1 1 0 100 2 1 1 0 000-2zm-6 3a3 3 0 110 6 3 3 0 010-6z"/>
                </svg>
              </a>

              <a href="#"
                 class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-md border-2 border-[#F2C94C]
                        text-[#F2C94C] hover:bg-[#F2C94C] hover:text-[#0E2A7B] transition">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                  <path d="M23.5 6.2c-.2-1.1-1.2-2-2.3-2.2C18.7 3.5 12 3.5 12 3.5s-6.7 0-9.2.5C1.7 4.3.7 5.1.5 6.2 0 8.1 0 12 0 12s0 3.9.5 5.8c.2 1.1 1.2 2 2.3 2.2 2.5.5 9.2.5 9.2.5s6.7 0 9.2-.5c1.1-.2 2-1.1 2.3-2.2.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.8 15.5V8.5L16 12l-6.2 3.5z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Kolom 2: Hubungi Kami --}}
      <div class="lg:col-span-3 flex flex-col mt-2 md:mt-0">
        <h3 class="font-[Volkhov] font-bold text-[18px] md:text-[19px] mb-4 md:mb-5 text-[#FFFFFF] text-left">
          Hubungi Kami
        </h3>
        <div class="space-y-4 font-[Montserrat] font-medium">
          {{-- Alamat --}}
          <div class="flex gap-3 items-start">
            <svg class="w-5 h-5 text-[#FFFFFF] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <p class="text-[13px] sm:text-[14px] text-[#FFFFFF] leading-relaxed">
              Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231
            </p>
          </div>

          {{-- Telepon --}}
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[#FFFFFF] shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
            <p class="text-[13px] sm:text-[14px] text-[#FFFFFF]">-</p>
          </div>

          {{-- Email --}}
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[#FFFFFF] shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <a href="mailto:uptptkk@gmail.com"
               class="text-[13px] sm:text-[14px] text-[#FFFFFF] hover:text-[#F2C94C] transition">
              uptptkk@gmail.com
            </a>
          </div>
        </div>
      </div>

      {{-- Kolom 3: Jam Layanan --}}
      <div class="lg:col-span-2 flex flex-col mt-2 md:mt-0">
        <h3 class="font-[Volkhov] font-bold text-[18px] md:text-[19px] mb-4 md:mb-5 text-[#FFFFFF] text-left">
          Jam Layanan
        </h3>
        <div class="space-y-4 font-[Montserrat] font-medium">
          {{-- Hari --}}
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[#FFFFFF]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
            </svg>
            <p class="text-[13px] sm:text-[14px] text-[#FFFFFF]">Senin - Jumat</p>
          </div>

          {{-- Jam --}}
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[#FFFFFF]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
            </svg>
            <p class="text-[13px] sm:text-[14px] text-[#FFFFFF]">08.00 - 16.00 WIB</p>
          </div>
        </div>
      </div>

      {{-- Kolom 4: Tautan Terkait --}}
      <div class="lg:col-span-3 flex flex-col mt-2 md:mt-0">
        <h3 class="font-[Volkhov] font-bold text-[18px] md:text-[19px] mb-4 md:mb-5 text-[#FFFFFF] text-left">
          Tautan Terkait
        </h3>
        <div class="space-y-3 font-[Montserrat] font-medium">
          <a href="#" class="flex items-center gap-2 group">
            <svg class="w-4 h-4 text-[#FFFFFF] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[13px] sm:text-[14px] text-[#FFFFFF] group-hover:text-[#F2C94C] transition">
              Tentang Kami
            </span>
          </a>

          <a href="#" class="flex items-center gap-2 group">
            <svg class="w-4 h-4 text-[#FFFFFF] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[13px] sm:text-[14px] text-[#FFFFFF] group-hover:text-[#F2C94C] transition">
              FAQ
            </span>
          </a>

          <a href="#" class="flex items-center gap-2 group">
            <svg class="w-4 h-4 text-[#FFFFFF] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[13px] sm:text-[14px] text-[#FFFFFF] group-hover:text-[#F2C94C] transition">
              Portal Alumni
            </span>
          </a>
        </div>
      </div>

    </div>

    {{-- Copyright --}}
    <div class="mt-8 md:mt-10 pt-6 border-t-2 border-[#FFFFFF]/40">
      <div class="flex flex-col sm:flex-row items-center justify-center gap-2 text-center">
        <img src="{{ asset('images/icons/copyright.svg') }}"
             alt="Copyright Icon"
             class="w-4 h-4 object-contain opacity-90 brightness-0 invert">

        <p class="font-[Montserrat] font-medium text-[12px] sm:text-[13px] text-[#FFFFFF] max-w-[32rem]">
          Copyright UPT. Pengembangan Teknis dan Keterampilan Kejuruan
        </p>
      </div>
    </div>

  </div>
</footer>
