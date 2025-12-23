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

    .table-wrap{border:2px solid #0b83d0;border-radius:14px;overflow:hidden;box-shadow:0 10px 24px rgba(17,24,39,0.08)}
    .table-head{background:linear-gradient(90deg,#0aa3e5 0%,#08b3f2 100%);color:#081526}
    .row-animate{opacity:0;transform:translateY(6px);animation:rowIn 0.45s ease forwards;animation-delay:var(--row-delay,0ms)}
    @keyframes rowIn{to{opacity:1;transform:translateY(0)}}
    .row-hover{transition:background-color .2s ease}
  </style>
</head>

<body class="bg-[#F1F9FC] text-[#081526] font-[Montserrat]">

  {{-- TOPBAR --}}
  @include('components.layouts.app.topbar')

  {{-- NAVBAR --}}
  @include('components.layouts.app.navbarlanding')

  {{-- =========================
      WRAPPER
      ========================= --}}
  <main class="section-container py-6 md:py-8 relative overflow-hidden">
    <div class="absolute -top-24 -right-16 h-64 w-64 rounded-full bg-[#DCE8FF] blur-3xl opacity-70"></div>
    <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-[#E9F7FF] blur-3xl opacity-80"></div>

    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
      <div class="lg:col-span-5">
        <span class="inline-flex items-center
                      px-4 md:px-0 py-1 rounded-md bg-[#F3E8E9] text-[#861D23]
                      font-bold text-base md:text-lg lg:text-[20px] font-[Volkhov] shadow-sm leading-tight">
              Data Statistik
            </span>

            <h2 class="heading-stroke font-[Volkhov] font-bold
                      text-[22px] md:text-[26px] leading-tight
                      text-[#1524AF] mb-3
                      text-center md:text-left">
              Rekapitulasi Rata-Rata<br/>Program Pelatihan
            </h2>

        <p id="judulPelatihan" class="mt-3 font-[Montserrat] text-sm font-semibold text-[#1524AF]">
          Memuat...
        </p>

        <ul id="listPelatihan" class="mt-3 space-y-2">
          {{-- render via JS --}}
        </ul>

        <a href="{{ url('/program-pelatihan') }}"
          class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#1828B3] px-4 py-2 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:translate-y-[-1px] hover:bg-[#14219A]">
          Pelatihan Kami
          <span aria-hidden="true">→</span>
        </a>
      </div>

      <div class="lg:col-span-7 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="rounded-2xl bg-[#DFE9F9] px-4 py-3 text-center shadow-sm">
            <div id="cardPre" class="text-[20px] md:text-[22px] font-bold text-[#081526]">0,00</div>
            <div class="text-[11px] font-[Montserrat] text-slate-600">Rata-Rata Pre-Test</div>
          </div>
          <div class="rounded-2xl bg-[#DFE9F9] px-4 py-3 text-center shadow-sm">
            <div id="cardPraktek" class="text-[20px] md:text-[22px] font-bold text-[#081526]">0,00</div>
            <div class="text-[11px] font-[Montserrat] text-slate-600">Praktek</div>
          </div>
          <div class="rounded-2xl bg-[#DFE9F9] px-4 py-3 text-center shadow-sm">
            <div id="cardPost" class="text-[20px] md:text-[22px] font-bold text-[#081526]">0,00</div>
            <div class="text-[11px] font-[Montserrat] text-slate-600">Rata-Rata Post-Test</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border-2 border-[#2238A8] p-4 md:p-5 shadow-sm">
          <div class="flex items-center justify-between gap-4 mb-3">
            <h2 class="font-[Volkhov] font-bold text-[16px] md:text-[18px] text-[#0F1E7A]">
              Nilai
            </h2>
            <span class="text-[12px] font-[Montserrat] text-slate-500">
              Kompetensi Pelatihan
            </span>
          </div>
          <div class="w-full overflow-x-auto">
            <div id="chartWrap" class="min-w-[560px]">
              <canvas id="chartNilai" class="w-full"></canvas>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#D6DFEF] shadow-sm">
          <h3 class="font-[Volkhov] font-bold text-[18px] text-[#1524AF] mb-3">
            Informasi Pelatihan
          </h3>
          <div
            id="fotoPelatihanWrap"
            class="flex gap-3 overflow-x-auto snap-x snap-mandatory pb-2 rounded-xl border border-slate-200 bg-slate-50"
          >
            <img
              src="/images/gedung-upt.jpg"
              alt="Foto pelatihan"
              class="h-40 w-full min-w-[220px] rounded-lg object-cover snap-center border border-slate-200"
              onerror="this.onerror=null;this.src='/images/gedung-upt.jpg';"
            />
          </div>
          <div class="space-y-2 text-sm font-[Montserrat] text-slate-700">
            <div class="flex items-center justify-between gap-2">
              <span>Jumlah Kompetensi</span>
              <span id="infoJumlah" class="font-semibold text-[#0F1E7A]">-</span>
            </div>
            <div class="flex items-center justify-between gap-2">
              <span>Rata-Rata Nilai</span>
              <span id="infoRata" class="font-semibold text-[#0F1E7A]">-</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#D6DFEF] overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm table-wrap">
              <thead class="table-head">
                <tr class="text-left">
                  <th class="px-4 py-3 border border-[#0b83d0] font-[Montserrat] font-semibold text-[#081526]">
                    Nama Kompetensi
                  </th>
                  <th class="px-4 py-3 border border-[#0b83d0] font-[Montserrat] font-semibold text-[#081526] text-center">
                    Pre-Test
                  </th>
                  <th class="px-4 py-3 border border-[#0b83d0] font-[Montserrat] font-semibold text-[#081526] text-center">
                    Post-Test
                  </th>
                  <th class="px-4 py-3 border border-[#0b83d0] font-[Montserrat] font-semibold text-[#081526] text-center">
                    Praktek
                  </th>
                  <th class="px-4 py-3 border border-[#0b83d0] font-[Montserrat] font-semibold text-[#081526] text-center">
                    Rata-Rata
                  </th>
                </tr>
              </thead>
              <tbody id="tableRows">
                {{-- render via JS --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- FOOTER --}}
  @include('components.layouts.app.footer')

  <script>
  (function () {
    const toNum = (v) => {
      if (v === null || v === undefined) return 0;
      if (typeof v === 'number') return v;
      const s = String(v).trim().replace(/\./g, '').replace(',', '.');
      const n = parseFloat(s);
      return Number.isFinite(n) ? n : 0;
    };
    const round2 = (n) => Math.round(n * 100) / 100;
    const fmt = (n) => round2(n).toFixed(2).replace('.', ',');
    const avg = (arr) => (arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : 0);
    const getPre = (k) => toNum(k?.pre ?? k?.nilai_pre_test);
    const getPost = (k) => toNum(k?.post ?? k?.nilai_post_test);
    const getPraktek = (k) => toNum(k?.praktek ?? k?.nilai_praktek);
    const calcRata = (k) => (getPost(k) + getPraktek(k)) / 2;

    const xLabels = ['Pre-Test', 'Post-Test', 'Praktek', 'Rata-Rata'];
    const palette = [
      '#FF0080', '#00E5FF', '#FFE600', '#FF3B30',
      '#00FF6A', '#FF7A00', '#B800FF', '#00B2FF',
      '#FF2D55', '#C6FF00'
    ];
    const colorFor = (i) => {
      if (i < palette.length) return palette[i];
      const hue = (i * 37) % 360;
      return `hsl(${hue}, 70%, 45%)`;
    };

    const pelatihans = {!! json_encode($pelatihans ?? []) !!};

    const elJudul = document.getElementById('judulPelatihan');
    const elList = document.getElementById('listPelatihan');
    const elTable = document.getElementById('tableRows');
    const elFotoWrap = document.getElementById('fotoPelatihanWrap');
    const elInfoJumlah = document.getElementById('infoJumlah');
    const elInfoRata = document.getElementById('infoRata');
    const elCardPre = document.getElementById('cardPre');
    const elCardPraktek = document.getElementById('cardPraktek');
    const elCardPost = document.getElementById('cardPost');
    const elChartWrap = document.getElementById('chartWrap');
    const ctx = document.getElementById('chartNilai');

    if (!pelatihans.length || !elJudul || !elList || !elTable || !ctx) return;

    const latestIndex = pelatihans.reduce((bestIdx, p, idx) => {
      const bestId = pelatihans[bestIdx]?.id ?? 0;
      return (p.id ?? 0) > bestId ? idx : bestIdx;
    }, 0);

    let activeIndex = latestIndex;
    let chartInstance = null;
    let fotoScrollTimer = null;

    function getActive() {
      return pelatihans[activeIndex] || pelatihans[0];
    }

    function resolveFotoUrl(path) {
      if (!path) return '';
      if (path.startsWith('http') || path.startsWith('/')) return path;
      return `/storage/${path}`;
    }

    function startFotoAutoScroll() {
      if (!elFotoWrap) return;
      if (fotoScrollTimer) {
        clearInterval(fotoScrollTimer);
        fotoScrollTimer = null;
      }

      const maxScroll = elFotoWrap.scrollWidth - elFotoWrap.clientWidth;
      if (maxScroll <= 0) return;

      let dir = 1;
      fotoScrollTimer = setInterval(() => {
        const next = elFotoWrap.scrollLeft + dir * 1.2;
        if (next >= maxScroll) {
          elFotoWrap.scrollLeft = maxScroll;
          dir = -1;
          return;
        }
        if (next <= 0) {
          elFotoWrap.scrollLeft = 0;
          dir = 1;
          return;
        }
        elFotoWrap.scrollLeft = next;
      }, 30);
    }

    function renderJudul() {
      const pel = getActive();
      elJudul.textContent = pel.nama || 'Pelatihan';
    }

    function renderList() {
      elList.innerHTML = pelatihans.map((p, idx) => {
        const isActive = idx === activeIndex;
        return `
          <li>
            <button type="button"
              data-idx="${idx}"
              class="w-full text-left px-2 py-2 border-b border-slate-200 font-[Montserrat] text-[13px] md:text-[14px]
                     ${isActive ? 'text-[#1524AF] font-semibold' : 'text-[#081526] hover:text-[#1524AF]'}">
              <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full ${isActive ? 'bg-[#1524AF]' : 'bg-[#0F1E7A]'}"></span>
                ${p.nama}
              </span>
            </button>
          </li>
        `;
      }).join('');
    }

    function renderTable() {
      const pel = getActive();
      const ks = pel.kompetensis || [];

      elTable.innerHTML = ks.map((k, idx) => `
        <tr class="row-animate row-hover hover:bg-[#EEF7FF]" style="--row-delay:${idx * 60}ms">
          <td class="px-4 py-3 border border-[#D6DFEF] bg-[#FFFFFF]
                     text-[#081526] font-[Montserrat] font-medium">
            ${k.nama ?? '-'}${(pel.id === 2 && k.lokasi) ? ' (' + k.lokasi + ')' : ''}
          </td>
          <td class="px-4 py-3 border border-[#D6DFEF] bg-[#FFFFFF]
                     text-[#081526] font-[Montserrat] font-medium text-center">
            ${fmt(getPre(k))}
          </td>
          <td class="px-4 py-3 border border-[#D6DFEF] bg-[#FFFFFF]
                     text-[#081526] font-[Montserrat] font-medium text-center">
            ${fmt(getPost(k))}
          </td>
          <td class="px-4 py-3 border border-[#D6DFEF] bg-[#FFFFFF]
                     text-[#081526] font-[Montserrat] font-medium text-center">
            ${fmt(getPraktek(k))}
          </td>
          <td class="px-4 py-3 border border-[#D6DFEF] bg-[#FFFFFF]
                     text-[#081526] font-[Montserrat] font-medium text-center">
            ${fmt(calcRata(k))}
          </td>
        </tr>
      `).join('');
    }

    function renderSummary() {
      const pel = getActive();
      const ks = pel.kompetensis || [];
      const preAvg = avg(ks.map(getPre));
      const postAvg = avg(ks.map(getPost));
      const praktekAvg = avg(ks.map(getPraktek));
      const rataAvg = avg(ks.map(calcRata));

      if (elCardPre) elCardPre.textContent = fmt(preAvg);
      if (elCardPost) elCardPost.textContent = fmt(postAvg);
      if (elCardPraktek) elCardPraktek.textContent = fmt(praktekAvg);
      if (elInfoJumlah) elInfoJumlah.textContent = ks.length ? `${ks.length} Kompetensi` : '-';
      if (elInfoRata) elInfoRata.textContent = ks.length ? fmt(rataAvg) : '-';
      if (elFotoWrap) {
        const fotoRaw = Array.isArray(pel.fotos) ? pel.fotos : [];
        const fotoUrls = fotoRaw.map(resolveFotoUrl).filter(Boolean);
        const fallback = `/images/gedung-upt.jpg`;
        const sources = fotoUrls.length ? fotoUrls : [fallback];

        elFotoWrap.innerHTML = sources.map((src, idx) => `
          <img
            src="${src}"
            alt="Foto pelatihan ${idx + 1}"
            class="h-40 w-full min-w-[220px] rounded-lg object-cover snap-center border border-slate-200"
            onerror="this.onerror=null;this.src='${fallback}';"
          />
        `).join('');

        elFotoWrap.scrollLeft = 0;
        startFotoAutoScroll();
      }
    }

    function renderChart() {
      const pel = getActive();
      const ks = pel.kompetensis || [];

      const labels = xLabels;

      if (chartInstance) chartInstance.destroy();

      const minWidth = 560;
      const chartWidth = Math.max(minWidth, labels.length * 140);
      if (elChartWrap) elChartWrap.style.width = `${chartWidth}px`;

      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: ks.map((k, i) => {
            const color = colorFor(i);
            const lokasi = (pel.id === 2 && k.lokasi) ? ` (${k.lokasi})` : '';
            return {
              label: (k.nama || 'Kompetensi') + lokasi,
              data: [
                getPre(k),
                getPost(k),
                getPraktek(k),
                calcRata(k),
              ],
              tension: 0.35,
              pointRadius: 3,
              borderWidth: 2,
              borderColor: color,
              pointBackgroundColor: color
            };
          })
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              align: 'center',
              labels: {
                usePointStyle: true,
                boxWidth: 8,
                boxHeight: 8
              }
            },
            tooltip: { mode: 'index', intersect: false },
          },
          interaction: { mode: 'index', intersect: false },
          scales: {
            y: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } },
            x: {
              ticks: {
                maxRotation: 35,
                minRotation: 0,
                autoSkip: false
              }
            }
          }
        }
      });

      ctx.parentElement.style.height = '280px';
    }

    function renderAll() {
      renderJudul();
      renderList();
      renderSummary();
      renderChart();
      renderTable();
    }

    renderAll();

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
