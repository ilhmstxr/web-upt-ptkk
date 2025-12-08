<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(async function(){
  const res = await fetch('/api/statistik-pelatihan');
  const data = await res.json();

  // 1) isi list pelatihan kiri
  const list = document.getElementById('listPelatihan');
  list.innerHTML = data.pelatihans.map((p, i) => `
    <li>
      <button type="button" class="pel-btn w-full flex items-center gap-2 py-1.5 text-left" data-index="${i}">
        <span class="dot w-2 h-2 rounded-full ${i===0?'bg-[#1524AF]':'bg-[#000000]'}"></span>
        <span class="label flex-1 text-[14px] font-[Montserrat] font-medium ${i===0?'text-[#1524AF]':'text-[#000000]'}">${p.nama}</span>
      </button>
      <div class="divider h-[1px] ${i===0?'bg-[#1524AF]':'bg-[#000000]'}"></div>
    </li>
  `).join('');

  // 2) isi summary cards
  document.querySelector('#preAvgCard')?.textContent = data.summary.pre_avg;
  document.querySelector('#praktekAvgCard')?.textContent = data.summary.praktek_avg;
  document.querySelector('#postAvgCard')?.textContent = data.summary.post_avg;

  // 3) chart
  const ctx = document.getElementById('statistikChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.labels,
      datasets: [
        { label: 'Pre-Test',  data: data.datasets.pre,     borderColor:'#FF6107', pointBackgroundColor:'#FF6107', borderWidth:2, tension:.35, pointRadius:4 },
        { label: 'Post-Test', data: data.datasets.post,    borderColor:'#2F4BFF', pointBackgroundColor:'#2F4BFF', borderWidth:2, tension:.35, pointRadius:4 },
        { label: 'Praktek',   data: data.datasets.praktek, borderColor:'#6B2C47', pointBackgroundColor:'#6B2C47', borderWidth:2, tension:.35, pointRadius:4 },
        { label: 'Rata-Rata', data: data.datasets.rata,    borderColor:'#DBCC8F', pointBackgroundColor:'#DBCC8F', borderWidth:2, tension:.35, pointRadius:4 },
      ]
    },
    options: { /* options kamu tetap */ }
  });

  // 4) toggle active state kamu tetap jalan
})();
</script>
