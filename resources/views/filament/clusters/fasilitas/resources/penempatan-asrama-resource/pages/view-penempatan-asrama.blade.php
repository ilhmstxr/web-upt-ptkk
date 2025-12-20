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
        <div class="rounded-xl bg-slate-50 p-3 shadow-sm border border-slate-200">
            <div class="flex flex-wrap items-center justify-between gap-2">
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
                        <span class="h-3 w-3 rounded" style="background-color:#facc15;"></span>
                        Bisa diisi
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded" style="background-color:#ef4444;"></span>
                        Rusak
                    </div>
                </div>
            </div>

            @php
                $asramaBgClasses = [
                    'bg-rose-50',
                    'bg-amber-50',
                    'bg-sky-50',
                    'bg-emerald-50',
                    'bg-indigo-50',
                    'bg-fuchsia-50',
                ];
            @endphp
            <div class="mt-3 grid gap-3 items-start" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                @forelse ($asramaLayouts as $asrama)
                    @php
                        $bgClass = $asramaBgClasses[$loop->index % count($asramaBgClasses)];
                    @endphp
                    <div class="rounded-xl {{ $bgClass }} p-3 border border-slate-200">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $asrama['name'] }}</div>
                                <div class="text-xs text-slate-600">
                                    Kamar aktif: {{ $asrama['active_rooms'] }} |
                                    Bed terisi: {{ $asrama['occupied_beds'] }}/{{ $asrama['total_beds'] }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 grid gap-2">
                            @foreach ($asrama['rooms'] as $room)
                                @php
                                    $roomClass = 'rounded-lg border border-slate-200 bg-white/80 p-2';
                                    $roomTitle = 'Kamar ' . $room['no'];
                                @endphp
                                <div class="{{ $roomClass }} relative">
                                    <div class="text-[11px] font-semibold text-slate-800">{{ $roomTitle }}</div>
                                    @if ($room['is_active'])
                                        @php
                                            $bedCols = min(6, max(1, $room['total_beds']));
                                            $beds = $room['beds'] ?? [];
                                            if (empty($beds) && $room['total_beds'] > 0) {
                                                $beds = array_fill(0, $room['total_beds'], ['state' => 'available']);
                                            }
                                        @endphp
                                        <div class="mt-2 grid gap-1 justify-start"
                                             style="grid-template-columns: repeat({{ $bedCols }}, 12px);">
                                            @foreach ($beds as $bed)
                                                @php
                                                    $bedState = $bed['state'] ?? 'available';
                                                    $bedGender = strtolower((string) ($bed['gender'] ?? ''));
                                                    $isMale = in_array($bedGender, ['laki-laki', 'laki', 'pria', 'l'], true);
                                                    $isFemale = in_array($bedGender, ['perempuan', 'wanita', 'p'], true);
                                                    $classes = 'rounded-sm border border-slate-300';
                                                    $tooltip = 'Bisa diisi';
                                                    if ($bedState === 'rusak' || $bedState === 'broken') {
                                                        $bg = '#ef4444';
                                                        $tooltip = 'Rusak';
                                                    } elseif ($bedState === 'occupied') {
                                                        if ($isMale) {
                                                            $bg = '#3b82f6';
                                                            $tooltip = 'Laki-laki';
                                                        } elseif ($isFemale) {
                                                            $bg = '#ec4899';
                                                            $tooltip = 'Perempuan';
                                                        } else {
                                                            $bg = '#3b82f6';
                                                            $tooltip = 'Terisi';
                                                        }
                                                    } else {
                                                        $bg = '#facc15';
                                                    }
                                                @endphp
                                                <div class="{{ $classes }}" style="background-color:{{ $bg }};width:12px;height:12px;" title="{{ $tooltip }}"></div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="absolute inset-0 rounded-lg bg-slate-300/50 flex items-center justify-center text-[10px] font-medium text-slate-600">
                                            Tidak tersedia
                                        </div>
                                    @endif
                                </div>
                            @endforeach
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
