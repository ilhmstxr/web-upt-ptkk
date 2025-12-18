{{-- resources/views/pages/statistik/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Statistik Program Pelatihan - UPT PTKK</title>

  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400&display=swap" rel="stylesheet">


  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    .section-container{max-width:1280px;margin:auto;padding-left:1.5rem;padding-right:1.5rem;}
    @media (min-width:768px){.section-container{padding-left:3rem;padding-right:3rem}}
    @media (min-width:1024px){.section-container{padding-left:80px;padding-right:80px}}

  .text-stroke-yellow {
    -webkit-text-stroke: 1.5px #FACC15;
    text-stroke: 1.5px #FACC15; /* fallback */
  }

  </style>
</head>

<body class="bg-[#F1F9FC] text-[#081526]">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- =========================
      WRAPPER
      ========================= --}}
  <main class="section-container py-6 md:py-8">

    {{-- JUDUL PELATIHAN (dinamis: terbaru) --}}
  <h1 id="judulPelatihan"
    class="font-[Volkhov] font-bold text-[20px] md:text-[26px] text-center leading-snug mb-6
           text-[#1524AF] text-stroke-yellow">
  —
</h1>


    {{-- =========================
        DIAGRAM (dinamis)
        ========================= --}}
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-6">
      <div class="flex items-center justify-between gap-4 mb-3">
        <h2 class="font-[Volkhov] font-bold text-[16px] md:text-[18px]">
          Statistik Diagram per Pelatihan
        </h2>
        <span class="text-[12px] font-[Montserrat] text-slate-500">
          Pre-Test • Post-Test • Praktek • Rata-Rata
        </span>
      </div>

      <div class="w-full">
        <canvas id="chartNilai" class="w-full"></canvas>
      </div>
    </section>

    {{-- =========================
        BAWAH: TABEL + LIST PROGRAM
        sesuai screenshot: tabel kiri, list program kanan
        ========================= --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">

      {{-- TABLE (kiri) --}}
      <div class="lg:col-span-8">
        <div class="bg-white border border-[#B9B9B9] overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
             <thead class="bg-[#00A4F9]">
                <tr class="text-left">
                 <th class="px-4 py-3 border border-[#B9B9B9]
           font-[Montserrat] font-medium text-[#000000]">
  Nama Program Keahlian
</th>
                  <th class="px-4 py-3 border border-[#B9B9B9]
           font-[Montserrat] font-medium text-[#000000]">
Pre-Test</th>
                  <th class="px-4 py-3 border border-[#B9B9B9]
           font-[Montserrat] font-medium text-[#000000]">
Post-Test</th>
                  <th class="px-4 py-3 border border-[#B9B9B9]
           font-[Montserrat] font-medium text-[#000000]">
Praktik</th>
            <th class="px-4 py-3 border border-[#B9B9B9]
           font-[Montserrat] font-medium text-[#000000]">
Rata-Rata</th>
                </tr>
              </thead>

             <tbody id="tableRows">
                {{-- render via JS --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- LIST PROGRAM (kanan bawah) --}}
      <div class="lg:col-span-4">
        <div class="bg-[#DBE7F7] rounded-2xl p-5 shadow-sm border border-slate-200">
          <h3 class="font-[Volkhov] font-bold text-[18px] text-[#1524AF] mb-3">
            Program Pelatihan
          </h3>
          <div class="h-[2px] w-full bg-slate-600/40 mb-3"></div>

          <ul id="listPelatihan" class="space-y-2">
            {{-- render via JS --}}
          </ul>
        </div>
      </div>

    </section>
  </main>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  <script>
  (function () {
    // ===== helper angka "61,00" => 61
    const toNum = (v) => {
      if (v === null || v === undefined) return 0;
      if (typeof v === 'number') return v;
      const s = String(v).trim().replace(/\./g, '').replace(',', '.');
      const n = parseFloat(s);
      return Number.isFinite(n) ? n : 0;
    };
    const round2 = (n) => Math.round(n * 100) / 100;
    const fmt = (n) => round2(n).toFixed(2).replace('.', ',');

    // =========================
    // DATA: PASTE DATA KAMU PERSIS DI SINI (JANGAN DIUBAH)
    // =========================
    const data = {!! json_encode([
      // >>> PASTE BLOK "pelatihans" kamu yang panjang di sini persis <<<
    ]) !!};

    const pelatihans = (data && data.pelatihans) ? data.pelatihans : [];

    const elJudul = document.getElementById('judulPelatihan');
    const elList = document.getElementById('listPelatihan');
    const elTable = document.getElementById('tableRows');
    const ctx = document.getElementById('chartNilai');

    if (!pelatihans.length || !elJudul || !elList || !elTable || !ctx) return;

    // =========================
    // DEFAULT: pelatihan TERBARU = id terbesar
    // =========================
    const latestIndex = pelatihans.reduce((bestIdx, p, idx) => {
      const bestId = pelatihans[bestIdx]?.id ?? 0;
      return (p.id ?? 0) > bestId ? idx : bestIdx;
    }, 0);

    let activeIndex = latestIndex;
    let chartInstance = null;

    function getActive() {
      return pelatihans[activeIndex] || pelatihans[0];
    }

    function renderJudul() {
      const pel = getActive();
      elJudul.textContent = pel.nama || '—';
    }

    // =========================
    // LIST kanan: "judul pelatihan lain"
    // (tetap tampil semua, tapi yang aktif dibold/berbeda)
    // =========================
    function renderList() {
      elList.innerHTML = pelatihans.map((p, idx) => {
        const isActive = idx === activeIndex;
        return `
          <li>
            <button type="button"
              data-idx="${idx}"
              class="w-full text-left px-3 py-2 rounded-xl font-[Montserrat] text-[14px]
                     ${isActive ? 'bg-white/70 font-semibold text-[#1524AF]' : 'bg-white/35 hover:bg-white/55 text-[#081526]'}">
              ${p.nama}
            </button>
          </li>
        `;
      }).join('');
    }

    // =========================
    // TABLE bawah: sesuai kompetensi pelatihan aktif
    // =========================
    function renderTable() {
      const pel = getActive();
      const ks = pel.kompetensis || [];

      elTable.innerHTML = ks.map((k) => `
       <tr class="hover:bg-[#F5F5F5]">
  <td class="px-4 py-3 border border-[#B9B9B9] bg-[#FFFFFF]
             text-[#000000] font-['Fira_Code'] font-normal">
    ${k.nama ?? '-'}
  </td>

  <td class="px-4 py-3 border border-[#B9B9B9] bg-[#FFFFFF]
             text-[#000000] font-['Fira_Code'] font-normal text-center">
    ${fmt(toNum(k.pre))}
  </td>

  <td class="px-4 py-3 border border-[#B9B9B9] bg-[#FFFFFF]
             text-[#000000] font-['Fira_Code'] font-normal text-center">
    ${fmt(toNum(k.post))}
  </td>

  <td class="px-4 py-3 border border-[#B9B9B9] bg-[#FFFFFF]
             text-[#000000] font-['Fira_Code'] font-normal text-center">
    ${fmt(toNum(k.praktek))}
  </td>

  <td class="px-4 py-3 border border-[#B9B9B9] bg-[#FFFFFF]
             text-[#000000] font-['Fira_Code'] font-normal text-center">
    ${fmt(toNum(k.rata))}
  </td>
</tr>
      `).join('');
    }

    // =========================
    // CHART: line chart 4 garis (pre/post/praktek/rata)
    // =========================
    function renderChart() {
      const pel = getActive();
      const ks = pel.kompetensis || [];

      const labels = ks.map(k => (k.nama || '').replace(/\s*\(.*?\)\s*/g,'').trim()); // biar rapi di sumbu X
      const pre = ks.map(k => toNum(k.pre));
      const post = ks.map(k => toNum(k.post));
      const praktek = ks.map(k => toNum(k.praktek));
      const rata = ks.map(k => toNum(k.rata));

      if (chartInstance) chartInstance.destroy();

      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: [
            { label: 'Post-Test', data: post, tension: 0.35, pointRadius: 3, borderWidth: 2 },
            { label: 'Pre-Test',  data: pre,  tension: 0.35, pointRadius: 3, borderWidth: 2 },
            { label: 'Praktek',   data: praktek, tension: 0.35, pointRadius: 3, borderWidth: 2 },
            { label: 'Rata-Rata', data: rata, tension: 0.35, pointRadius: 3, borderWidth: 2 },
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10 } },
            tooltip: { mode: 'index', intersect: false },
          },
          interaction: { mode: 'index', intersect: false },
          scales: {
            y: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } },
            x: { ticks: { maxRotation: 0, autoSkip: true } }
          }
        }
      });

      // kasih tinggi chart biar kayak screenshot
      ctx.parentElement.style.height = '260px';
    }

    function renderAll() {
      renderJudul();
      renderList();
      renderChart();
      renderTable();
    }

    // init: otomatis terbaru
    renderAll();

    // click list kanan
    elList.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-idx]');
      if (!btn) return;
      activeIndex = parseInt(btn.dataset.idx, 10) || 0;
      renderAll();
    });

  })();
  </script>
</body>
</html>
