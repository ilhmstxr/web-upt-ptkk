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

<body class="bg-[#F1F9FC] antialiased">

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
<section id="kelas-keterampilan" class="w-full bg-[#F1F9FC] py-8 md:py-10">
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
    ['tata-busana.svg',      'Tata Busana', 'Kelas Tata Busana dirancang untuk membekali peserta dengan beberapa keterampilan mulai dari pengenalan bahan dan alat, dasar-dasar menjahit, mendesain pakaian, teknik pembuatan pola, hingga finishing yang rapi dan profesional. Peserta juga akan mempelajari tren mode, perpaduan warna, dan kreativitas desain agar mampu menghasilkan karya busana yang unik dan berkualitas.'],
    ['tata-boga.svg',        'Tata Boga',   'Kelas Tata Boga dirancang untuk membekali peserta dengan pengetahuan dan keterampilan mengolah berbagai jenis masakan. Peserta akan mempelajari teknik dasar seperti persiapan bahan, pemotongan, pengolahan, penyajian, hingga dekorasi hidangan. Peserta juga akan dibekali pengetahuan tentang keamanan pangan, pengaturan porsi, dan inovasi menu, sehingga dapat menghasilkan hidangan yang sehat, higienis, dan bercita rasa tinggi.'],
    ['tata-kecantikan.svg',  'Tata Kecantikan', 'Kelas Tata Kecantikan dirancang untuk membekali peserta dengan keterampilan dasar dan lanjutan di bidang perawatan wajah, tata rias (make-up), hingga perawatan rambut. Kegiatan ini peserta diharapkan mampu mengembangkan kreativitas, menjaga etika profesi, dan memanfaatkan peluang usaha.'],
    ['teknik-pemesinan.svg', 'Teknik Pemesinan', 'Peserta pelatihan akan diperkenalkan pada berbagai mesin vital seperti mesin bubut untuk membentuk benda silinder, mesin frais untuk membuat permukaan datar dan alur, untuk menghasilkan produk yang sangat presisi. Peserta pelatihan juga akan mengasah kemampuan dalam membaca gambar teknik, dan merancang komponen, mulai dari mur dan baut hingga bagian-bagian rumit dari sebuah mesin besar.'],
    ['teknik-pendingin.svg', 'Teknik Pendingin dan Tata Udara', 'Kelas TPTU ini dirancang untuk membekali peserta dengan pengetahuan dan keterampilan teknis mulai dari prinsip kerja sistem pendingin, komponen utama, teknik instalasi, hingga perawatan dan perbaikan peralatan pendingin. Peserta juga akan diajarkan cara mendiagnosis kerusakan, menghitung beban pendingin, serta memahami prosedur keselamatan kerja dan penggunaan alat ukur khusus.'],
    ['teknik-otomotif.svg',  'Teknik Otomotif', 'Kelas otomotif mempelajari segala hal, mulai dari bagaimana bensin diubah menjadi tenaga, hingga bagaimana sensor-sensor canggih pada mobil modern bekerja. Di sini, tidak hanya duduk mendengarkan teori, melainkan langsung turun ke bengkel, memegang kunci pas, dan merasakan langsung setiap komponen.'],
    ['teknik-pengelasan.svg','Teknik Pengelasan', 'Peserta akan mempelajari cara menggunakan berbagai teknik las, mulai dari Las SMAW (Shielded Metal Arc Welding) yang paling umum, Las GMAW (Gas Metal Arc Welding) untuk kecepatan dan efisiensi, hingga Las GTAW (Gas Tungsten Arc Welding) yang dikenal karena hasilnya yang sangat rapi. Peserta juga akan diajarkan cara mempersiapkan material, memilih kawat las yang sesuai, mengatur arus listrik dengan benar, serta memastikan keselamatan saat bekerja.'],
  ] as [$img, $judul, $desc])

<figure
  class="group relative overflow-hidden rounded-2xl
         opacity-0 translate-y-4 transition-all duration-700 ease-out"
  data-reveal>

  <div class="aspect-[16/9] w-full relative overflow-hidden rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF]">

    {{-- Gambar --}}
    <img src="{{ asset('images/profil/' . $img) }}"
         alt="{{ $judul }}"
         class="w-full h-full object-cover select-none transition-transform duration-500 group-hover:scale-[1.02]" />

     {{-- Blok judul + deskripsi dinamis di dalam gambar --}}
  <div class="absolute inset-x-0 bottom-6 md:bottom-8 px-8">
    <div class="max-w-[82%]">
      {{-- Judul (stroke 1524AF, fill FFDE59) --}}
      <div class="relative font-[Volkhov] font-bold text-[22px] md:text-[26px] leading-tight select-none">
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
      </div>

      {{-- Teks penjelasan (Montserrat medium, putih) --}}
      <p class="mt-3 font-[Montserrat] font-medium
                text-[14px] md:text-[15px] leading-relaxed text-white
                drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
        {{ $desc }}
      </p>
    </div>
  </div>

  </div>
</figure>

  @endforeach
</div>

{{-- ===== TAB: MJC (5 kartu, satu kolom ke bawah) ===== --}}
<div id="tab-mjc" class="grid grid-cols-1 gap-8 hidden">
  @foreach ([
    ['mjc-fotografi.svg',    'Fotografi',      'Kelas Fotografi dirancang untuk memberikan pemahaman menyeluruh mulai dari teknik dasar pengambilan gambar, pengeturan kamera, komposisi, hingga pencahayaan. Selain itu, peserta juga akan mempelajari proses editing menggunakan software seperti Adobe Lightroom atau Photoshop, untuk memaksimalkan hasil foto agar terlihat lebih tajam, indah, dan profesional.'],
    ['mjc-videografi.svg',   'Videografi',     'Kelas videografi menjadi tempat ideal untuk mengubah minat menjadi keahlian profesional. Di dalamnya, peserta tidak hanya belajar teori, tetapi juga praktik langsung untuk menguasai setiap tahapan produksi video. Dengan bimbingan mentor dan praktik langsung, peserta bisa mengubah ide menjadi karya video yang tidak hanya indah, tetapi juga mampu menyentuh hati penonton.'],
    ['mjc-desain-grafis.svg','Desain Grafis',  'Kelas Desain Grafis dirancang untuk membekali peserta dengan pengetahuan dan keterampilan mulai dari konsep dasar desain, teori warna, tipografi, hingga teknik layout yang menarik. Peserta akan diperkenalkan pada berbagai software desain populer seperti Adobe Photoshop, Illustrator, dan aplikasi desain berbasis web seperti Canva atau Figma, sehingga dapat menghasilkan karya yang profesional.'],
    ['mjc-animasi.svg',      'Animasi',        'Kelas animasi adalah tempat di mana peserta bisa belajar membuat gambar bergerak, mulai dari konsep dasar hingga teknik profesional. Kelas ini bisa membantu peserta mewujudkan ide menjadi sebuah karya visual, baik untuk film, video game, iklan, maupun media lainnya.'],
    ['mjc-web-desain.svg',   'Web Desain',     'Pelatihan ini dirancang untuk memberikan pemahaman menyeluruh mengenai prinsip desain web, mulai dari dasar-dasar HTML, CSS, dan JavaScript hingga penerapan desain responsif yang ramah pengguna. Peserta juga akan diajak untuk menguasai tools pendukung seperti Figma atau Canva, serta teknik optimasi website agar lebih cepat, menarik, dan mudah ditemukan di mesin pencari.'],
  ] as [$img, $judul, $desc])
<figure
  class="group relative overflow-hidden rounded-2xl
         opacity-0 translate-y-4 transition-all duration-700 ease-out"
  data-reveal>

  <div class="aspect-[16/9] w-full relative overflow-hidden rounded-2xl border-[2.5px] md:border-[3px] border-[#1524AF]">

    {{-- Gambar --}}
    <img src="{{ asset('images/profil/' . $img) }}"
         alt="{{ $judul }}"
         class="w-full h-full object-cover select-none transition-transform duration-500 group-hover:scale-[1.02]" />

      {{-- Blok judul + deskripsi dinamis di dalam gambar --}}
  <div class="absolute inset-x-0 bottom-6 md:bottom-8 px-8">
    <div class="max-w-[82%]">
      {{-- Judul (stroke 1524AF, fill FFDE59) --}}
      <div class="relative font-[Volkhov] font-bold text-[22px] md:text-[26px] leading-tight select-none">
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
      </div>

      {{-- Teks penjelasan (Montserrat medium, putih) --}}
      <p class="mt-3 font-[Montserrat] font-medium
                text-[14px] md:text-[15px] leading-relaxed text-white
                drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
        {{ $desc }}
      </p>
    </div>
  </div>

  </div>
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

