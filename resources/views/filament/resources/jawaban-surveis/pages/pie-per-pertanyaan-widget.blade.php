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

                    <canvas
                        x-data="{
                            labels: @js($c['labels']),
                            data: @js($c['data']),
                            init() {
                                const create = () => new Chart(this.$el.getContext('2d'), {
                                    type: 'pie',
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            data: this.data,
                                            backgroundColor: [
                                                'rgba(248,113,113,0.7)',
                                                'rgba(251,191,36,0.7)',
                                                'rgba(59,130,246,0.7)',
                                                'rgba(16,185,129,0.7)',
                                            ],
                                            borderColor: [
                                                'rgb(239,68,68)',
                                                'rgb(245,158,11)',
                                                'rgb(59,130,246)',
                                                'rgb(16,185,129)',
                                            ],
                                            borderWidth: 1,
                                        }],
                                    },
                                    options: {
                                        plugins: {
                                            legend: { position: 'bottom' },
                                            tooltip: { mode: 'index', intersect: false },
                                        },
                                    },
                                });

                                if (window.Chart) {
                                    create();
                                } else {
                                    const s = document.createElement('script');
                                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                    s.onload = () => create();
                                    document.head.appendChild(s);
                                }
                            }
                        }"
                        width="400" height="400"
                    ></canvas>
                </div>
            @empty
                <div class="text-sm text-gray-500">Data tidak tersedia.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
