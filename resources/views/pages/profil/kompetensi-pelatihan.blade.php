{{-- resources/views/pages/profil/bidang-pelatihan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Program Pelatihan - UPT PTKK Dinas Pendidikan Prov. Jawa Timur</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Font Volkhov --}}
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">

  {{-- Font Montserrat --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <style>
    .upt-stroke {
      text-shadow:
        -1px -1px 0 #861D23,
         1px -1px 0 #861D23,
        -1px  1px 0 #861D23,
         1px  1px 0 #861D23;
    }
/* stroke kuning penuh (aktif) */
.yellow-stroke {
  text-shadow:
    -0.5px -0.5px 0 #FFDE59,
     0.5px -0.5px 0 #FFDE59,
    -0.5px  0.5px 0 #FFDE59,
     0.5px  0.5px 0 #FFDE59,
     0     0    0.5px #FFDE59;
}

/* stroke kuning 50% (nonaktif) */
.yellow-stroke-50 {
  text-shadow:
    -0.5px -0.5px 0 rgba(255, 222, 89, 0.5),
     0.5px -0.5px 0 rgba(255, 222, 89, 0.5),
    -0.5px  0.5px 0 rgba(255, 222, 89, 0.5),
     0.5px  0.5px 0 rgba(255, 222, 89, 0.5),
     0     0    0.5px rgba(255, 222, 89, 0.5);
}


    .tujuan-card {
      background: #FEFEFE;
      box-shadow:
        0 2px 4px rgba(0, 0, 0, .06),
        0 12px 24px rgba(0, 0, 0, .08),
        0 40px 80px rgba(0, 0, 0, .08);
    }
  </style>
</head>

<body class="bg-[#FEFEFE] antialiased">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

 {{-- HERO (komponen reusable) --}}
<x-layouts.app.profile-hero
  title="Bidang Pelatihan"
  :crumbs="[
    ['label' => 'Beranda', 'route' => 'landing'],
    ['label' => 'Profil'],
    ['label' => 'Bidang Pelatihan'],
  ]"
  image="images/profil/profil-upt.JPG"   {{-- Gambar latar belakang hero --}}
  height="h-[368px]"              {{-- Tinggi hero --}}
/>
{{-- /HERO --}}
{{-- SECTION: Bidang Pelatihan (menu kiri sticky, konten kanan scrollable tanpa scrollbar) --}}
<section id="kelas-keterampilan" class="w-full bg-[#F5FBFF] py-8 md:py-10">
  <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-[80px]">
    @php($active = request('tab','keterampilan')) {{-- 'keterampilan' | 'mjc' --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start"
         x-data
         data-active="{{ $active }}">

      {{-- KIRI: Badge + Menu (sticky) --}}
      <aside class="lg:col-span-4">
        {{-- Badge kecil pas teks --}}
        <div class="inline-flex items-center px-3 py-1 rounded-md bg-[#F3E8E9] mb-5 shadow-sm">
          <span class="font-[Volkhov] font-bold text-[#861D23] text-[16px] md:text-[17px] leading-none">
            Bidang Pelatihan
          </span>
        </div>

        {{-- Menu (sticky; atur top sesuai tinggi topbar+navbar kamu) --}}
        <div class="lg:sticky lg:top-[96px]">
          <ul class="space-y-3">

           {{-- Item 1: Kelas Keterampilan dan Teknik --}}
<li class="flex items-center gap-2">
  <span id="dot-ktr" class="w-2.5 h-2.5 rounded-full flex-shrink-0 bg-[#1524AF]"></span>
  <button id="btn-ktr"
    class="text-left block font-[Volkhov] font-bold leading-tight md:whitespace-nowrap pb-[3px] border-b border-[#1524AF] yellow-stroke">
    Kelas Keterampilan dan Teknik
  </button>
</li>

{{-- Item 2: Milenial Job Center --}}
<li class="flex items-center gap-2">
  <span id="dot-mjc" class="w-2.5 h-2.5 rounded-full flex-shrink-0 bg-[#1524AF]/50"></span>
  <button id="btn-mjc"
    class="text-left block font-[Volkhov] font-bold leading-tight md:whitespace-nowrap pb-[3px] border-b border-[#1524AF]/50 yellow-stroke-50">
    Milenial Job Center
  </button>
</li>

          </ul>
        </div>
      </aside>

      {{-- KANAN: Konten scrollable tanpa scrollbar --}}
      <div class="lg:col-span-8">
        <div id="scroll-pane"
             class="max-h-[calc(100vh-160px)] overflow-y-auto pr-1 md:pr-2 scroll-smooth"
             style="scrollbar-width: none; -ms-overflow-style: none;">
          {{-- ===== TAB: KETERAMPILAN (7 kartu, satu kolom ke bawah) ===== --}}
<div id="tab-ktr" class="grid grid-cols-1 gap-8">
  @foreach ([
    ['tata-busana.svg',      'Tata Busana', 'Kelas Tata Busana membekali peserta dengan keterampilan dasar menjahit, pengenalan bahan dan alat, pembuatan pola, serta finishing profesional.'],
    ['tata-boga.svg',        'Tata Boga',   'Menguasai teknik pengolahan makanan, higienitas, dekorasi, dan penyajian standar industri.'],
    ['tata-kecantikan.svg',  'Tata Kecantikan', 'Makeup dasar–lanjutan, skincare, hair-do, dan etika layanan.'],
    ['teknik-pemesinan.svg', 'Teknik Pemesinan', 'Pengoperasian mesin, alat ukur, CNC dasar, serta K3.'],
    ['teknik-pendingin.svg', 'Teknik Pendingin dan Tata Udara', 'Sistem AC/kulkas, instalasi, perawatan, dan perbaikan.'],
    ['teknik-otomotif.svg',  'Teknik Otomotif', 'Sistem kendaraan, perawatan mesin, kelistrikan, dan injeksi.'],
    ['teknik-pengelasan.svg','Teknik Pengelasan', 'Las SMAW, GMAW, GTAW dan keselamatan kerja.'],
  ] as [$img, $judul, $desc])

<figure
  class="group relative overflow-hidden rounded-2xl
         opacity-0 translate-y-4 transition-all duration-700 ease-out"
  data-reveal>
  <button type="button" class="absolute inset-0 z-10" aria-label="Tampilkan deskripsi"></button>

  {{-- Frame gambar: border biru tebal tipis + padding kecil (gambar tampak lebih kecil, layout tetap) --}}
  <div class="aspect-[16/9] w-full relative overflow-hidden rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF]">
  {{-- Gambar bidang --}}
  <img src="{{ asset('images/profil/' . $img) }}"
       alt="{{ $judul }}"
       class="w-full h-full object-cover select-none transition-transform duration-500 group-hover:scale-[1.02]" />

  {{-- Nama bidang (stroke biru & fill kuning) --}}
  <span class="absolute bottom-4 left-8 font-[Volkhov] font-bold text-[22px] md:text-[26px] leading-none select-none">
    <span class="absolute inset-0 text-[#1524AF]"
          style="-webkit-text-stroke:2px #1524AF; color:transparent;">
      {{ $judul }}
    </span>
    <span class="relative text-[#FFDE59]"
          style="text-shadow:
            0 0 2px #1524AF,
            0 0 4px #1524AF,
            0 0 8px rgba(255,222,89,0.85),
            0 0 16px rgba(255,222,89,0.7);">
      {{ $judul }}
    </span>
  </span>
</div>


  {{-- Overlay deskripsi --}}
  <figcaption
    class="pointer-events-none absolute inset-x-0 bottom-0 m-3 md:m-4 bg-[#1524AF] text-white
           rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg
           translate-y-6 md:translate-y-8 opacity-0
           transition-all duration-500 ease-out
           group-hover:translate-y-0 group-hover:opacity-100"
    data-overlay>
    <h3 class="font-[Volkhov] text-[20px] md:text-[22px] font-bold text-[#FFDE59] mb-2">{{ $judul }}</h3>
    <p class="font-[Montserrat] text-[14px] md:text-[15px] leading-relaxed text-white/95">{{ $desc }}</p>
  </figcaption>
</figure>

  @endforeach
</div>

{{-- ===== TAB: MJC (5 kartu, satu kolom ke bawah) ===== --}}
<div id="tab-mjc" class="grid grid-cols-1 gap-8 hidden">
  @foreach ([
    ['mjc-fotografi.svg',    'Fotografi',      'Komposisi, pencahayaan, kamera & lensa, hingga dasar editing.'],
    ['mjc-videografi.svg',   'Videografi',     'Sinematografi, framing, audio, basic editing & storytelling.'],
    ['mjc-desain-grafis.svg','Desain Grafis',  'Tipografi, warna, layout, dan praktik desain untuk media digital.'],
    ['mjc-animasi.svg',      'Animasi',        'Prinsip animasi, storyboard, motion dasar, dan asset simple.'],
    ['mjc-web-desain.svg',   'Web Desain',     'Grid, UI dasar, responsif, dan prototyping antarmuka.'],
  ] as [$img, $judul, $desc])
 <figure
  class="group relative overflow-hidden rounded-2xl
         opacity-0 translate-y-4 transition-all duration-700 ease-out"
  data-reveal>
  <button type="button" class="absolute inset-0 z-10" aria-label="Tampilkan deskripsi"></button>

 <div class="aspect-[16/9] w-full relative overflow-hidden rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF]">
  {{-- Gambar bidang --}}
  <img src="{{ asset('images/profil/' . $img) }}"
       alt="{{ $judul }}"
       class="w-full h-full object-cover select-none transition-transform duration-500 group-hover:scale-[1.02]" />

  {{-- Nama bidang (stroke biru & fill kuning) --}}
  <span class="absolute bottom-4 left-8 font-[Volkhov] font-bold text-[22px] md:text-[26px] leading-none select-none">
    <span class="absolute inset-0 text-[#1524AF]"
          style="-webkit-text-stroke:2px #1524AF; color:transparent;">
      {{ $judul }}
    </span>
    <span class="relative text-[#FFDE59]"
          style="text-shadow:
            0 0 2px #1524AF,
            0 0 4px #1524AF,
            0 0 8px rgba(255,222,89,0.85),
            0 0 16px rgba(255,222,89,0.7);">
      {{ $judul }}
    </span>
  </span>
</div>


  <figcaption
    class="pointer-events-none absolute inset-x-0 bottom-0 m-3 md:m-4 bg-[#1524AF] text-white
           rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg
           translate-y-6 md:translate-y-8 opacity-0
           transition-all duration-500 ease-out
           group-hover:translate-y-0 group-hover:opacity-100"
    data-overlay>
    <h3 class="font-[Volkhov] text-[20px] md:text-[22px] font-bold text-[#FFDE59] mb-2">{{ $judul }}</h3>
    <p class="font-[Montserrat] text-[14px] md:text-[15px] leading-relaxed text-white/95">{{ $desc }}</p>
  </figcaption>
</figure>

  @endforeach
</div>

        </div>
      </div>

    </div>
  </div>
</section>

{{-- CSS untuk menyembunyikan scrollbar --}}
<style>
  #scroll-pane::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
  }
</style>

{{-- JS: toggle tab + reveal + inisialisasi dari query ?tab= --}}
<script>
(function(){
  const url = new URL(window.location.href);
  const qsTab = url.searchParams.get('tab') || document.querySelector('[data-active]')?.getAttribute('data-active') || 'keterampilan';

  const btnKtr = document.getElementById('btn-ktr');
  const btnMjc = document.getElementById('btn-mjc');
  const tabKtr = document.getElementById('tab-ktr');
  const tabMjc = document.getElementById('tab-mjc');
  const dotKtr = document.getElementById('dot-ktr');
  const dotMjc = document.getElementById('dot-mjc');
  const pane   = document.getElementById('scroll-pane');

  function setActive(tab){
    const activeKtr = tab === 'keterampilan';
    tabKtr.classList.toggle('hidden', !activeKtr);
    tabMjc.classList.toggle('hidden',  activeKtr);

   // style menu aktif/nonaktif (tetap stroke kuning; border biru tipis)
btnKtr.className =
  'text-left block font-[Volkhov] font-bold leading-tight md:whitespace-nowrap pb-[3px] border-b ' +
  (activeKtr
    ? 'text-[#1524AF] border-[#1524AF] yellow-stroke'
    : 'text-[#1524AF]/50 border-[#1524AF]/50 yellow-stroke-50 hover:text-[#1524AF] hover:border-[#1524AF] hover:yellow-stroke');

btnMjc.className =
  'text-left block font-[Volkhov] font-bold leading-tight md:whitespace-nowrap pb-[3px] border-b ' +
  (!activeKtr
    ? 'text-[#1524AF] border-[#1524AF] yellow-stroke'
    : 'text-[#1524AF]/50 border-[#1524AF]/50 yellow-stroke-50 hover:text-[#1524AF] hover:border-[#1524AF] hover:yellow-stroke');

    dotKtr.className = 'w-2.5 h-2.5 rounded-full flex-shrink-0 ' + (activeKtr ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50');
    dotMjc.className = 'w-2.5 h-2.5 rounded-full flex-shrink-0 ' + (!activeKtr ? 'bg-[#1524AF]' : 'bg-[#1524AF]/50');

    // update URL query tanpa reload
    const u = new URL(window.location);
    u.searchParams.set('tab', tab);
    window.history.replaceState({}, '', u);

    // reset scroll kanan ke atas saat ganti tab
    pane.scrollTo({top: 0, behavior: 'smooth'});
  }

  btnKtr?.addEventListener('click', () => setActive('keterampilan'));
  btnMjc?.addEventListener('click', () => setActive('mjc'));

  // inisialisasi awal
  setActive(qsTab);

  // reveal on scroll (di dalam pane)
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.remove('opacity-0','translate-y-4');
        e.target.classList.add('opacity-100','translate-y-0');
        io.unobserve(e.target);
      }
    });
  }, { root: pane, threshold: 0.15 });
  document.querySelectorAll('#scroll-pane [data-reveal]').forEach(el => io.observe(el));
})();
</script>

 {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  @stack('scripts')
</body>
</html>

