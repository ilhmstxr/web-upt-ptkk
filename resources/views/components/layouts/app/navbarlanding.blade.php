{{-- resources/views/components/layouts/app/navbarlanding.blade.php --}}
@php use Illuminate\Support\Facades\Route; @endphp
@php
  $items = [
    ['label' => 'Beranda', 'name' => 'home', 'fallback' => url('/'), 'path' => ''],

    // Profil dropdown
    ['label' => 'Profil', 'group' => 'profile', 'children' => [
      ['label' => 'Cerita Kami',           'name' => 'story',      'fallback' => url('/cerita-kami'),            'path' => 'cerita-kami'],
      ['label' => 'Program Pelatihan',     'name' => 'programs',   'fallback' => url('/program-pelatihan'),      'path' => 'program-pelatihan'],
      ['label' => 'Kompetensi Pelatihan',  'name' => 'kompetensi', 'fallback' => url('/kompetensi-pelatihan'),   'path' => 'kompetensi-pelatihan'],
    ]],

    ['label' => 'Berita',   'name' => 'news',    'fallback' => url('/berita'),   'path' => 'berita'],
    ['label' => 'Panduan',  'name' => 'panduan', 'fallback' => url('/panduan'),  'path' => 'panduan'],
  ];

  // resolver active + href
  $resolve = function ($it) {
    $hasRoute = isset($it['name']) && Route::has($it['name']);
    $href     = $hasRoute ? route($it['name']) : ($it['fallback'] ?? '#');

    $isActive =
      ($hasRoute && (request()->routeIs($it['name']) || request()->routeIs($it['name'].'.*')))
      || (isset($it['name']) && $it['name'] === 'home' && request()->routeIs('landing'))
      || (url()->current() === $href)
      || (isset($it['path']) && (trim($it['path']) === ''
            ? url()->current() === url('/')
            : (request()->is($it['path']) || request()->is($it['path'].'/*'))));

    return ['label' => $it['label'], 'href' => $href, 'active' => $isActive];
  };

  $nav = []; $profile = ['children' => [], 'active' => false];
  foreach ($items as $it) {
    if (($it['group'] ?? null) === 'profile') {
      $children = array_map($resolve, $it['children']);
      $profile  = ['label' => 'Profil', 'children' => $children, 'active' => collect($children)->contains(fn($c) => $c['active'])];
    } else {
      $nav[] = $resolve($it);
    }
  }
  $beranda = collect($nav)->firstWhere('label', 'Beranda');
  $others  = collect($nav)->reject(fn($i) => $i['label'] === 'Beranda')->values();
  $loginHref  = Route::has('login') ? route('login') : url('/login');
  $daftarHref = Route::has('register') ? route('register') : url('/daftar');
@endphp

<header id="siteHeader"
  class="sticky top-0 z-50 bg-[#FFFFFF] border-b border-slate-200/40 transition-shadow"
  role="navigation" aria-label="Site">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    <div class="h-[64px] md:h-[72px] flex items-center justify-between gap-4">

      {{-- KIRI: Logo + identitas --}}
      <a href="{{ Route::has('home') ? route('home') : url('/') }}"
         class="flex items-center gap-3 flex-shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30 rounded">
        <img src="{{ asset('images/logo-provinsi-jawa-timur.png') }}"
             alt="Logo Provinsi Jawa Timur"
             class="w-[42px] h-[50px] object-contain" />
        <div class="leading-tight">
          <div class="font-[Volkhov] font-bold text-slate-900 text-[20px] tracking-tight">
            UPT. PTKK
          </div>
          <div class="font-[Montserrat] text-[16px] text-slate-800 -mt-[2px]">
            Dinas Pendidikan Prov. Jawa Timur
          </div>
        </div>
      </a>

      {{-- MENU DESKTOP --}}
      <div class="hidden md:flex items-center gap-6 font-[Montserrat]">
        <nav class="flex items-center gap-8 text-[18px]" aria-label="Main">
          {{-- Beranda --}}
          @if($beranda)
            <a href="{{ $beranda['href'] }}"
               @class([
                 'px-1 py-0.5 rounded transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30',
                 'text-slate-900 hover:text-[#1524AF]' => !$beranda['active'],
                 'text-[#1524AF]' => $beranda['active'],
               ])
               @if($beranda['active']) aria-current="page" @endif>
              {{ $beranda['label'] }}
            </a>
          @endif

          {{-- Profil (dropdown click, vanilla JS) --}}
          <div class="relative">
            <button id="btnProfil"
              aria-expanded="false" aria-haspopup="menu"
              class="inline-flex items-center gap-2 px-1 py-0.5 rounded transition-colors duration-200
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30
                     {{ $profile['active'] ? 'text-[#1524AF]' : 'text-slate-900 hover:text-[#1524AF]' }}">
              Profil
              <svg id="chevronProfil" class="w-4 h-4 transition-transform duration-200"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>

            <div id="panelProfil"
                 class="absolute left-0 mt-2 w-64 rounded-xl border border-slate-200 bg-white shadow-lg p-2 z-40 hidden">
              @foreach ($profile['children'] as $c)
                <a href="{{ $c['href'] }}"
                   class="block rounded-md px-3 py-2 text-[15px] transition
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30
                          {{ $c['active'] ? 'text-[#1524AF] bg-[#F5FBFF]' : 'text-slate-900 hover:text-[#1524AF] hover:bg-slate-50' }}"
                   @if($c['active']) aria-current="page" @endif>
                  {{ $c['label'] }}
                </a>
              @endforeach
            </div>
          </div>

          {{-- Berita & Panduan --}}
          @foreach ($others as $item)
            <a href="{{ $item['href'] }}"
               @class([
                 'px-1 py-0.5 rounded transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30',
                 'text-slate-900 hover:text-[#1524AF]' => !$item['active'],
                 'text-[#1524AF]' => $item['active'],
               ])
               @if($item['active']) aria-current="page" @endif>
              {{ $item['label'] }}
            </a>
          @endforeach
        </nav>

        {{-- Tombol kanan --}}
        <div class="flex items-center gap-3">
          <a href="{{ $loginHref }}"
             class="inline-flex items-center justify-center h-10 px-4 rounded-xl
                    border border-[#1524AF] text-[#1524AF] hover:bg-white/50
                    font-[Montserrat] text-[16px] transition">
            Masuk
          </a>
          <a href="{{ $daftarHref }}"
             class="inline-flex items-center justify-center h-10 px-5 rounded-xl
                    bg-[#1524AF] hover:opacity-90 text-white font-[Montserrat] text-[16px] gap-2 transition">
            Daftar
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
              <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </div>
      </div>

      {{-- HAMBURGER MOBILE --}}
      <button id="navBtn"
              class="md:hidden w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-white/50 transition
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30"
              aria-label="Toggle navigation" aria-controls="navDrawer" aria-expanded="false">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- DRAWER MOBILE --}}
  <div id="navDrawer" class="md:hidden hidden border-t border-slate-200 bg-[#FFFFFF]">
    <div class="px-6 py-4 flex flex-col gap-3 font-[Montserrat] text-[16px]">
      @if($beranda)
        <a href="{{ $beranda['href'] }}"
           @class([
             'py-2 px-4 rounded-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30',
             'text-[#1524AF] bg-[#F5FBFF]' => $beranda['active'],
             'text-slate-900 hover:text-[#1524AF] hover:bg-slate-50' => !$beranda['active'],
           ])
           @if($beranda['active']) aria-current="page" @endif>
          {{ $beranda['label'] }}
        </a>
      @endif

      {{-- Profil (mobile) --}}
      <details class="group border border-slate-200 rounded-lg">
        <summary class="list-none px-4 py-2 cursor-pointer flex items-center justify-between">
          <span class="select-none">Profil</span>
          <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </summary>
        <div class="px-2 pb-2">
          @foreach ($profile['children'] as $c)
            <a href="{{ $c['href'] }}"
               @class([
                 'block py-2 px-3 rounded-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30',
                 'text-[#1524AF] bg-[#F5FBFF]' => $c['active'],
                 'text-slate-900 hover:text-[#1524AF] hover:bg-slate-50' => !$c['active'],
               ])
               @if($c['active']) aria-current="page" @endif>
              {{ $c['label'] }}
            </a>
          @endforeach
        </div>
      </details>

      @foreach ($others as $item)
        <a href="{{ $item['href'] }}"
           @class([
             'py-2 px-4 rounded-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1524AF]/30',
             'text-[#1524AF] bg-[#F5FBFF]' => $item['active'],
             'text-slate-900 hover:text-[#1524AF] hover:bg-slate-50' => !$item['active'],
           ])
           @if($item['active']) aria-current="page" @endif>
          {{ $item['label'] }}
        </a>
      @endforeach

      <div class="flex gap-3 pt-1">
        <a href="{{ $loginHref }}"
           class="flex-1 inline-flex items-center justify-center h-10 px-4 rounded-xl
                  border border-[#1524AF] text-[#1524AF] hover:bg-white/50 transition">
          Masuk
        </a>
        <a href="{{ $daftarHref }}"
           class="flex-1 inline-flex items-center justify-center h-10 px-5 rounded-xl
                  bg-[#1524AF] hover:opacity-90 text-white gap-2 transition">
          Daftar
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </div>
  </div>
</header>

@push('scripts')
<script>
  (function () {
    const header = document.getElementById('siteHeader');
    const navBtn = document.getElementById('navBtn');
    const navDrawer = document.getElementById('navDrawer');

    // Drawer mobile
    function toggleDrawer(forceOpen) {
      if (!navBtn || !navDrawer) return;
      const willOpen = (typeof forceOpen === 'boolean') ? forceOpen : navDrawer.classList.contains('hidden');
      navDrawer.classList.toggle('hidden', !willOpen);
      navBtn.setAttribute('aria-expanded', String(willOpen));
      document.body.classList.toggle('overflow-hidden', willOpen);
    }
    navBtn && navBtn.addEventListener('click', () => toggleDrawer());
    if (navDrawer) navDrawer.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleDrawer(false)));
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !navDrawer.classList.contains('hidden')) toggleDrawer(false); }, { passive: true });

    // Shadow saat scroll
    function onScroll(){ if (window.scrollY > 4) header.classList.add('shadow-[0_6px_20px_rgba(0,0,0,.08)]'); else header.classList.remove('shadow-[0_6px_20px_rgba(0,0,0,.08)]'); }
    onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

    // CSS var --header-h untuk offset sticky lain
    function setHeaderH(){ document.documentElement.style.setProperty('--header-h', header.getBoundingClientRect().height + 'px'); }
    setHeaderH(); new ResizeObserver(setHeaderH).observe(header);

    // Dropdown Profil (desktop)
    const btnProfil = document.getElementById('btnProfil');
    const panelProfil = document.getElementById('panelProfil');
    const chevProfil = document.getElementById('chevronProfil');

    function setProfilOpen(open) {
      if (!btnProfil || !panelProfil || !chevProfil) return;
      panelProfil.classList.toggle('hidden', !open);
      btnProfil.setAttribute('aria-expanded', String(open));
      chevProfil.classList.toggle('rotate-180', open);
    }
    setProfilOpen(false);
    btnProfil && btnProfil.addEventListener('click', (e) => {
      e.stopPropagation();
      const willOpen = panelProfil.classList.contains('hidden');
      setProfilOpen(willOpen);
    });
    document.addEventListener('click', (e) => {
      if (!panelProfil || panelProfil.classList.contains('hidden')) return;
      if (!panelProfil.contains(e.target) && !btnProfil.contains(e.target)) setProfilOpen(false);
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setProfilOpen(false); });
    window.addEventListener('resize', () => setProfilOpen(false));
  })();
</script>
@endpush
