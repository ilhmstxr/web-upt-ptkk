{{-- resources/views/filament/resources/jawaban-survei/widgets/pie-per-pertanyaan-widget.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">{{ static::$heading }}</x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($this->charts as $c)
                <div class="space-y-3">
                    <div class="text-sm font-medium leading-snug">
                        {{ $c['question_label'] }}
                    </div>

                    <div x-data="{
                        labels: @js($c['labels']),
                        data: @js($c['data']),
                        colorsBg: [
                            'rgba(248,113,113,0.7)',
                            'rgba(251,191,36,0.7)',
                            'rgba(59,130,246,0.7)',
                            'rgba(16,185,129,0.7)',
                        ],
                        colorsBorder: [
                            'rgb(239,68,68)',
                            'rgb(245,158,11)',
                            'rgb(59,130,246)',
                            'rgb(16,185,129)',
                        ],
                        pct: [],
                        init() {
                            const total = (this.data ?? []).reduce((a, b) => a + (Number(b) || 0), 0);
                            this.pct = (this.data ?? []).map(v => {
                                const p = total ? (Number(v) / total * 100) : 0;
                                return Number.isFinite(p) ? p : 0;
                            });
                    
                            const create = () => {
                                const ctx = this.$refs.canvas.getContext('2d');
                                new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            data: this.data,
                                            backgroundColor: this.colorsBg,
                                            borderColor: this.colorsBorder,
                                            borderWidth: 1,
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false }, // legend Chart.js dimatikan
                                            tooltip: {
                                                callbacks: {
                                                    label: (item) => {
                                                        const idx = item.dataIndex;
                                                        const name = this.labels?.[idx] ?? '';
                                                        const val = this.data?.[idx] ?? 0;
                                                        const p = (this.pct?.[idx] ?? 0).toFixed(1);
                                                        return `${name}: ${val} (${p}%)`;
                                                    },
                                                },
                                            },
                                        },
                                    },
                                });
                            };
                    
                            if (window.Chart) {
                                create();
                            } else {
                                const s = document.createElement('script');
                                s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                s.onload = () => create();
                                document.head.appendChild(s);
                            }
                        },
                        percentStr(i) {
                            const p = (this.pct?.[i] ?? 0);
                            return p.toFixed(1).replace('.', ',') + '%';
                        }
                    }" class="flex gap-6 items-start">
                        <div class="grow min-h-[260px]">
                            <canvas x-ref="canvas" width="320" height="320" class="w-full h-[260px]"></canvas>
                        </div>

                        {{-- Legend kustom di kanan --}}
                        <ul class="w-64 shrink-0 space-y-2" role="list">
                            <template x-for="(label, i) in labels" :key="i">
                                <li class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block h-3 w-3 rounded"
                                            :style="`background:${colorsBg[i]}`"></span>
                                        <span x-text="label"></span>
                                    </div>
                                    <span class="font-medium" x-text="percentStr(i)"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Data tidak tersedia.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
