<x-filament-panels::page
    @class([
        'fi-resource-view-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
    @php
        $relationManagers = $this->getRelationManagers();
        $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent();
    @endphp

    <div class="space-y-6">
        <div class="rounded-xl bg-gradient-to-br from-sky-50 to-indigo-50 p-4 shadow-sm border border-sky-100">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="text-base font-semibold text-slate-900">Visualisasi Penempatan Asrama</div>
                    <div class="text-xs text-slate-600">Kotak menampilkan kapasitas kamar dan bed per asrama.</div>
                </div>
                <div class="flex flex-wrap gap-3 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded" style="background-color:#3b82f6;"></span>
                        Laki-laki
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded" style="background-color:#ec4899;"></span>
                        Perempuan
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded" style="background-color:#f59e0b;"></span>
                        Terisi
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded" style="background-color:#e2e8f0;"></span>
                        Kosong
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded border" style="background-color:#f1f5f9;border-color:#e2e8f0;"></span>
                        Tidak tersedia
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                @forelse ($asramaLayouts as $asrama)
                    <div class="rounded-xl bg-white/90 p-4 border border-slate-200">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $asrama['name'] }}</div>
                                <div class="text-xs text-slate-500">
                                    Kamar aktif: {{ $asrama['active_rooms'] }} |
                                    Bed terisi: {{ $asrama['occupied_beds'] }}/{{ $asrama['total_beds'] }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg border border-slate-200 overflow-hidden">
                            <div class="grid grid-cols-[120px_1fr] bg-indigo-50 text-[11px] text-indigo-800 font-semibold">
                                <div class="px-3 py-2 border-r border-indigo-100">Kamar</div>
                                <div class="px-3 py-2">Bed</div>
                            </div>
                            <div class="divide-y divide-slate-200">
                            @foreach ($asrama['rooms'] as $room)
                                @php
                                    $ratio = $room['total_beds'] > 0 ? ($room['occupied_beds'] / $room['total_beds']) : 0;
                                    if (!$room['is_active']) {
                                        $roomClass = 'rounded-lg border border-slate-200 bg-slate-50 p-3';
                                        $badgeClass = 'bg-slate-100 text-slate-500';
                                    } elseif ($ratio >= 0.75) {
                                        $roomClass = 'rounded-lg border border-rose-200 bg-rose-50 p-3';
                                        $badgeClass = 'bg-rose-100 text-rose-700';
                                    } elseif ($ratio >= 0.4) {
                                        $roomClass = 'rounded-lg border border-amber-200 bg-amber-50 p-3';
                                        $badgeClass = 'bg-amber-100 text-amber-700';
                                    } else {
                                        $roomClass = 'rounded-lg border border-emerald-200 bg-emerald-50 p-3';
                                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                                    }
                                @endphp
                                <div class="grid grid-cols-[120px_1fr] {{ $room['is_active'] ? 'bg-white' : 'bg-slate-50' }}">
                                    <div class="px-3 py-2 border-r border-slate-200">
                                        <div class="text-xs font-semibold text-slate-800">Kamar {{ $room['no'] }}</div>
                                        <span class="mt-1 inline-flex text-[9px] px-2 py-0.5 rounded-full {{ $badgeClass }}">
                                            @if ($room['is_active'])
                                                {{ $room['occupied_beds'] }}/{{ $room['total_beds'] }} terisi
                                            @else
                                                {{ $room['status'] }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="px-3 py-2">
                                        @if ($room['is_active'])
                                            @php
                                                $bedCols = min(6, max(1, $room['total_beds']));
                                                $beds = $room['beds'] ?? [];
                                                if (empty($beds) && $room['total_beds'] > 0) {
                                                    $beds = array_fill(0, $room['total_beds'], ['state' => 'available']);
                                                }
                                            @endphp
                                            <div class="grid gap-1"
                                                 style="grid-template-columns: repeat({{ $bedCols }}, minmax(0, 1fr));">
                                                @foreach ($beds as $bed)
                                                    @php
                                                        $bedState = $bed['state'] ?? 'available';
                                                        $classes = 'h-3 w-full rounded-sm';
                                                        $bg = $bedState === 'available' ? '#f87171' : '#22c55e';
                                                    @endphp
                                                    <div class="{{ $classes }}" style="background-color:{{ $bg }};"></div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="h-5 w-full rounded bg-slate-100 border border-slate-200
                                                        text-[10px] text-slate-500 flex items-center justify-center">
                                                Tidak tersedia
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-200 text-sm text-slate-600">
                        Konfigurasi asrama belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>

        @if ((! $hasCombinedRelationManagerTabsWithContent) || (! count($relationManagers)))
            @if ($this->hasInfolist())
                {{ $this->infolist }}
            @else
                <div wire:key="{{ $this->getId() }}.forms.{{ $this->getFormStatePath() }}">
                    {{ $this->form }}
                </div>
            @endif
        @endif

        @if (count($relationManagers))
            <x-filament-panels::resources.relation-managers
                :active-locale="isset($activeLocale) ? $activeLocale : null"
                :active-manager="$this->activeRelationManager ?? ($hasCombinedRelationManagerTabsWithContent ? null : array_key_first($relationManagers))"
                :content-tab-label="$this->getContentTabLabel()"
                :content-tab-icon="$this->getContentTabIcon()"
                :content-tab-position="$this->getContentTabPosition()"
                :managers="$relationManagers"
                :owner-record="$record"
                :page-class="static::class"
            >
                @if ($hasCombinedRelationManagerTabsWithContent)
                    <x-slot name="content">
                        @if ($this->hasInfolist())
                            {{ $this->infolist }}
                        @else
                            {{ $this->form }}
                        @endif
                    </x-slot>
                @endif
            </x-filament-panels::resources.relation-managers>
        @endif
    </div>
</x-filament-panels::page>
