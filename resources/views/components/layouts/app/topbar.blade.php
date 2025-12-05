{{-- Font Montserrat (pastikan di <head> layout utama) --}}
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

{{-- TOP BAR (padding responsif, font Montserrat) --}}
<div class="w-full bg-[#F1F9FC]/90 backdrop-blur-sm relative z-50" style="font-family: 'Montserrat', sans-serif;">
 <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px] py-2 flex items-center justify-between">

    {{-- Dropdown Bahasa --}}
    <div class="relative inline-block text-left">
      <button id="langBtn" type="button"
        class="inline-flex items-center gap-1 px-2 py-1 sm:py-0.5 rounded-md hover:bg-slate-100/80 transition-all duration-200
               text-[12px] sm:text-[14px] md:text-[15px] lg:text-[16px] font-medium text-slate-700">
        <span>Indonesia</span>
        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-slate-700 flex-shrink-0"
             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.19l3.71-3.96a.75.75 0 1 1 1.08 1.04l-4.24 4.52a.75.75 0 0 1-1.08 0L5.21 8.27a.75.75 0 0 1 .02-1.06z"
                clip-rule="evenodd" />
        </svg>
      </button>

      {{-- Menu dropdown --}}
      <div id="langMenu"
        class="hidden absolute left-0 mt-2 w-24 sm:w-28 bg-white rounded-lg shadow-md border border-slate-200 z-50
               text-[12px] sm:text-[14px] font-medium text-slate-700">
        <a href="#" class="block px-3 py-1.5 hover:bg-slate-50 rounded-t-lg">Indonesia</a>
        <a href="#" class="block px-3 py-1.5 hover:bg-slate-50 rounded-b-lg">English</a>
      </div>
    </div>

    {{-- Ikon Sosmed --}}
    <div class="flex items-center gap-2 sm:gap-3">
      <a href="#" class="text-slate-700 hover:text-pink-600 transition-colors duration-200 flex-shrink-0" aria-label="Instagram">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 lg:w-[18px] lg:h-[18px]" fill="currentColor" viewBox="0 0 24 24">
          <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm5.25-3.5a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z"/>
        </svg>
      </a>
      <a href="#" class="text-slate-700 hover:text-red-600 transition-colors duration-200 flex-shrink-0" aria-label="YouTube">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 lg:w-[18px] lg:h-[18px]" fill="currentColor" viewBox="0 0 24 24">
          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
      </a>
    </div>
  </div>

  {{-- Garis separator --}}
   <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="h-px bg-black/80"></div>
  </div>
</div>

@push('scripts')
<script>
  const langBtn = document.getElementById('langBtn');
  const langMenu = document.getElementById('langMenu');
  if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      langMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => {
      if (!langMenu.classList.contains('hidden')) langMenu.classList.add('hidden');
    });
  }
</script>
@endpush
