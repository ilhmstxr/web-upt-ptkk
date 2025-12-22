<x-filament-panels::page
    @class([
        'fi-resource-view-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
@php
    /* =====================================================
     | KONFIGURASI GLOBAL
     ===================================================== */
    $relationManagers = $this->getRelationManagers();
    $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent();

    /* =====================================================
     | KONFIGURASI GRID BED (BESAR + KOMPLEKS)
     ===================================================== */
    $BED_COLS  = 12;

    // bed lebih besar
    $BED_SIZE  = 30;

    // jarak antar kotak
    $BED_GAP   = 6;

    // ruang untuk label kamar di atas grid
    $LABEL_PAD = 20;

    // space antar kamar (jumlah cell kosong disisipkan setelah tiap kamar)
    $ROOM_GAP_CELLS = 2;

    /* =====================================================
     | WARNA BACKGROUND ASRAMA
     ===================================================== */
    $asramaColorMap = [
        'mawar'         => 'bg-violet-50',
        'melati bawah'  => 'bg-sky-50',
        'melati atas'   => 'bg-emerald-50',
        'tulip bawah'   => 'bg-pink-50',
        'tulip atas'    => 'bg-pink-50',
    ];

    $pesertaNameMap = [];
    foreach ($pesertaRows ?? [] as $row) {
        if (!empty($row['peserta_id'])) {
            $pesertaNameMap[$row['peserta_id']] = $row['nama'] ?? null;
        }
    }

    $genderTotals = [
        'laki_laki' => 0,
        'perempuan' => 0,
    ];
    foreach ($pesertaRows ?? [] as $row) {
        $gender = strtolower((string) ($row['gender'] ?? ''));
        if (in_array($gender, ['laki-laki', 'laki', 'pria', 'l'], true)) {
            $genderTotals['laki_laki']++;
        } elseif (in_array($gender, ['perempuan', 'wanita', 'p'], true)) {
            $genderTotals['perempuan']++;
        }
    }
@endphp

@once
<style>
  .denah-wrap { position: relative; }
  .denah-layer { position:absolute; left:0; right:0; top:0; bottom:0; pointer-events:none; }

  /* layering: bed (0) < border (10) < label (20) < tooltip (50) */
  .denah-beds   { position:relative; z-index:0; }
  .denah-border { z-index:10; }
  .denah-label  { z-index:20; }

  .denah-bed {
    outline: none;
    border-radius: 6px;
    transition: transform .08s ease, box-shadow .08s ease;
  }
  .denah-bed:hover { transform: translateY(-1px); }

  .denah-bed:focus-visible {
    box-shadow: 0 0 0 3px rgba(59,130,246,.35);
  }

  .denah-bed.is-selected {
    box-shadow: 0 0 0 2px rgba(22,163,74,.6);
  }

  .denah-bed.is-target {
    box-shadow: 0 0 0 2px rgba(22,163,74,.7) inset;
  }

  .peserta-row.is-selected {
    background: #ecfeff !important;
  }

  .peserta-row.is-dragging {
    opacity: .6;
  }

  .denah-tooltip {
    position: absolute;
    z-index: 50;
    pointer-events: none;
    padding: 7px 9px;
    font-size: 11px;
    line-height: 1.25;
    background: rgba(15,23,42,.94);
    color: white;
    border-radius: 10px;
    border: 1px solid rgba(148,163,184,.25);
    transform: translate(10px, -10px);
    white-space: nowrap;
    display: none;
  }

  .denah-label-badge {
    display:inline-block;
    padding: 1px 6px;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.2;
    border-radius: 8px;
    background: rgba(255,255,255,.92);
    color: rgb(51,65,85);
    border: 1px solid rgba(148,163,184,.65);
    transform: translateY(-6px);
    white-space: nowrap;
  }

  /* Spacer cell (gap antar kamar) */
  .denah-spacer {
    background: transparent !important;
    border-color: transparent !important;
  }
</style>
@endonce

@once
<script>
(function () {
  const livewireDispatch = (name, payload) => {
    if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
      window.Livewire.dispatch(name, payload || {});
    }
  };

  const clearBedSelection = () => {
    document.querySelectorAll('.denah-bed.is-selected').forEach((el) => {
      el.classList.remove('is-selected');
    });
  };

  const clearRowSelection = () => {
    document.querySelectorAll('.peserta-row.is-selected').forEach((el) => {
      el.classList.remove('is-selected');
    });
  };

  const selectRowById = (pesertaId) => {
    if (!pesertaId) return;
    clearRowSelection();
    const row = document.querySelector(`[data-peserta-id="${pesertaId}"]`);
    if (row) {
      row.classList.add('is-selected');
      row.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }
  };

  const selectBedById = (bedId) => {
    if (!bedId) return;
    clearBedSelection();
    const bed = document.querySelector(`[data-bed-id="${bedId}"]`);
    if (bed) {
      bed.classList.add('is-selected');
      bed.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }
  };

  const readPayload = (event) => {
    const raw =
      event.dataTransfer.getData('application/json') ||
      event.dataTransfer.getData('text/plain') ||
      '';
    if (!raw) return null;
    try {
      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  };

  function initDenah(root) {
    if (root.dataset.dnInit === '1') return;
    root.dataset.dnInit = '1';

    const grid = root.querySelector('[data-denah-grid]');
    const tooltip = root.querySelector('[data-denah-tooltip]');
    if (!grid || !tooltip) return;

    const cols = parseInt(grid.getAttribute('data-cols') || '12', 10);
    const cells = Array.from(grid.children);

    const isFocusable = (el) => el && el.getAttribute('data-bed') === '1';
    const isAvailable = (el) => el && el.getAttribute('data-state') === 'available';
    const isOccupied = (el) => el && el.getAttribute('data-state') === 'occupied';

    function showTooltipFor(el) {
      const text = el.getAttribute('data-tooltip') || '';
      if (!text) {
        tooltip.style.display = 'none';
        return;
      }

      tooltip.textContent = text;
      tooltip.style.display = 'block';

      const gridRect = grid.getBoundingClientRect();
      const r = el.getBoundingClientRect();
      const tRect = tooltip.getBoundingClientRect();

      let left = (r.right - gridRect.left);
      if (left + tRect.width > gridRect.width) {
        left = (r.left - gridRect.left) - tRect.width;
      }
      if (left < 0) left = 0;

      let top = (r.top - gridRect.top);
      if (top + tRect.height > gridRect.height) {
        top = gridRect.height - tRect.height;
      }
      if (top < 0) top = 0;

      tooltip.style.left = left + 'px';
      tooltip.style.top = top + 'px';
    }

    function hideTooltip() {
      tooltip.style.display = 'none';
    }

    function focusIndex(startIndex, step) {
      let i = startIndex;
      for (let guard = 0; guard < cells.length; guard++) {
        if (i < 0 || i >= cells.length) return false;
        const el = cells[i];
        if (isFocusable(el)) {
          el.focus();
          return true;
        }
        i += step;
      }
      return false;
    }

    // Hover tooltip
    grid.addEventListener('mouseover', (e) => {
      const el = e.target.closest('[data-bed="1"]');
      if (!el) return;
      showTooltipFor(el);
    });
    grid.addEventListener('mouseout', (e) => {
      const el = e.target.closest('[data-bed="1"]');
      if (!el) return;
      hideTooltip();
    });

    // Focus tooltip (keyboard)
    grid.addEventListener('focusin', (e) => {
      const el = e.target.closest('[data-bed="1"]');
      if (!el) return;
      showTooltipFor(el);

      const pesertaId = el.getAttribute('data-peserta-id');
      if (pesertaId) selectRowById(pesertaId);
      clearBedSelection();
      el.classList.add('is-selected');
      livewireDispatch('select-bed', { bedId: el.getAttribute('data-bed-id') });
    });
    grid.addEventListener('focusout', () => {
      hideTooltip();
    });

    // Click selection
    grid.addEventListener('click', (e) => {
      const el = e.target.closest('[data-bed="1"]');
      if (!el) return;
      const bedId = el.getAttribute('data-bed-id');
      clearBedSelection();
      el.classList.add('is-selected');
      const pesertaId = el.getAttribute('data-peserta-id');
      if (pesertaId) selectRowById(pesertaId);
      livewireDispatch('select-bed', { bedId });
    });

    // Keyboard navigation
    grid.addEventListener('keydown', (e) => {
      const cur = e.target.closest('[data-bed="1"]');
      if (!cur) return;

      const idx = parseInt(cur.getAttribute('data-index') || '0', 10);

      if (e.key === 'ArrowRight') {
        e.preventDefault();
        focusIndex(idx + 1, +1);
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        focusIndex(idx - 1, -1);
      } else if (e.key === 'ArrowDown') {
        e.preventDefault();
        focusIndex(idx + cols, +1);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        focusIndex(idx - cols, -1);
      }
    });

    // Drag start from bed (occupied only)
    grid.addEventListener('dragstart', (e) => {
      const el = e.target.closest('[data-bed="1"]');
      if (!el) return;
      if (el.getAttribute('draggable') !== 'true') return;
      const bedId = el.getAttribute('data-bed-id');
      const pesertaId = el.getAttribute('data-peserta-id');
      const gender = el.getAttribute('data-gender');
      if (!bedId || !pesertaId) return;
      const payload = { type: 'bed', bedId, pesertaId, gender };
      e.dataTransfer.setData('application/json', JSON.stringify(payload));
      e.dataTransfer.setData('text/plain', JSON.stringify(payload));
      e.dataTransfer.effectAllowed = 'move';
    });

    // Drag enter/over bed target
    grid.addEventListener('dragenter', (e) => {
      const target = e.target.closest('[data-bed="1"]');
      if (!target) return;
      const payload = readPayload(e);
      if (!payload || !payload.type) return;
      const canDrop =
        (payload.type === 'peserta' && (isAvailable(target) || (payload.bedId && isOccupied(target)))) ||
        (payload.type === 'bed' && (isAvailable(target) || isOccupied(target)));
      if (!canDrop) return;
      e.preventDefault();
    });

    grid.addEventListener('dragover', (e) => {
      const target = e.target.closest('[data-bed="1"]');
      if (!target) return;
      const payload = readPayload(e);
      if (!payload || !payload.type) return;

      const canDrop =
        (payload.type === 'peserta' && (isAvailable(target) || (payload.bedId && isOccupied(target)))) ||
        (payload.type === 'bed' && (isAvailable(target) || isOccupied(target)));

      if (!canDrop) return;
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
      target.classList.add('is-target');
    });

    grid.addEventListener('dragleave', (e) => {
      const target = e.target.closest('[data-bed="1"]');
      if (!target) return;
      target.classList.remove('is-target');
    });

    grid.addEventListener('drop', (e) => {
      const target = e.target.closest('[data-bed="1"]');
      if (!target) return;
      const payload = readPayload(e);
      if (!payload || !payload.type) return;
      const canDrop =
        (payload.type === 'peserta' && (isAvailable(target) || (payload.bedId && isOccupied(target)))) ||
        (payload.type === 'bed' && (isAvailable(target) || isOccupied(target)));
      if (!canDrop) return;
      e.preventDefault();
      target.classList.remove('is-target');

      const toBedId = target.getAttribute('data-bed-id');
      if (payload.type === 'peserta') {
        if (payload.bedId && isOccupied(target)) {
          livewireDispatch('swap-peserta-bed', {
            bedIdA: payload.bedId,
            bedIdB: toBedId,
          });
        } else {
          livewireDispatch('assign-peserta-to-bed', {
            pesertaId: payload.pesertaId,
            bedId: toBedId,
          });
        }
      } else if (payload.type === 'bed') {
        if (!payload.bedId || payload.bedId === toBedId) return;
        if (isOccupied(target)) {
          livewireDispatch('swap-peserta-bed', {
            bedIdA: payload.bedId,
            bedIdB: toBedId,
          });
        } else {
          livewireDispatch('move-peserta-bed', {
            fromBedId: payload.bedId,
            toBedId,
          });
        }
      }
    });
  }

  function initTable(wrapper) {
    if (wrapper.dataset.tbInit === '1') return;
    wrapper.dataset.tbInit = '1';

    wrapper.addEventListener('click', (e) => {
      const row = e.target.closest('[data-peserta-id]');
      if (!row) return;
      const pesertaId = row.getAttribute('data-peserta-id');
      const bedId = row.getAttribute('data-bed-id');
      clearRowSelection();
      row.classList.add('is-selected');
      if (bedId) selectBedById(bedId);
      livewireDispatch('select-peserta', { pesertaId });
    });

    wrapper.addEventListener('dragstart', (e) => {
      const row = e.target.closest('[data-peserta-id]');
      if (!row) return;
      row.classList.add('is-dragging');
      const pesertaId = row.getAttribute('data-peserta-id');
      const bedId = row.getAttribute('data-bed-id');
      const gender = row.getAttribute('data-gender');
      const payload = { type: 'peserta', pesertaId, bedId, gender };
      e.dataTransfer.setData('application/json', JSON.stringify(payload));
      e.dataTransfer.setData('text/plain', JSON.stringify(payload));
      e.dataTransfer.effectAllowed = 'move';
    });

    wrapper.addEventListener('dragend', (e) => {
      const row = e.target.closest('[data-peserta-id]');
      if (!row) return;
      row.classList.remove('is-dragging');
    });

    // Drop to unassign
    wrapper.addEventListener('dragenter', (e) => {
      const payload = readPayload(e);
      if (!payload) return;
      e.preventDefault();
    });

    wrapper.addEventListener('dragover', (e) => {
      const payload = readPayload(e);
      if (!payload) return;
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
    });

    wrapper.addEventListener('drop', (e) => {
      const payload = readPayload(e);
      if (!payload || !payload.type) return;
      e.preventDefault();
      if (payload.type === 'bed') {
        livewireDispatch('unassign-peserta-from-bed', {
          bedId: payload.bedId,
        });
      } else if (payload.type === 'peserta') {
        livewireDispatch('unassign-peserta', {
          pesertaId: payload.pesertaId,
        });
      }
    });
  }

  function initAll() {
    document.querySelectorAll('.denah-wrap').forEach(initDenah);
    document.querySelectorAll('[data-peserta-table]').forEach(initTable);
  }

  document.addEventListener('DOMContentLoaded', initAll);
  document.addEventListener('livewire:navigated', initAll);
  if (window.Livewire && typeof window.Livewire.hook === 'function') {
    window.Livewire.hook('morph.updated', () => {
      initAll();
    });
  }
})();
</script>
@endonce

<div class="space-y-6">

  {{-- =====================================================
   | HEADER DENAH
   ===================================================== --}}
  <div class="rounded-xl bg-white p-4 border border-slate-200">
    <div class="flex flex-wrap gap-4 text-sm text-slate-700">
      <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
        <span class="font-semibold text-slate-900">Laki-laki:</span>
        <span class="ml-2">{{ $genderTotals['laki_laki'] }}</span>
      </div>
      <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
        <span class="font-semibold text-slate-900">Perempuan:</span>
        <span class="ml-2">{{ $genderTotals['perempuan'] }}</span>
      </div>
    </div>

    <div>
      <div class="text-base font-semibold text-slate-900">
        Denah Penempatan Asrama
      </div>
      <div class="text-xs text-slate-500">
        Denah visual padat berbasis grid bed (kompleks + navigasi panah)
      </div>
    </div>

    {{-- =====================================================
     | GRID ASRAMA
     ===================================================== --}}
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-start">

      @forelse ($asramaLayouts as $asrama)
        @php
          $asramaKey = strtolower($asrama['name'] ?? '');
          $bgClass   = $asramaColorMap[$asramaKey] ?? 'bg-slate-50';

          // reset per-asrama
          $bedsFlat = [];
          $bedItems = [];
          $roomSegments = [];
          $cursor = 0;
          $asramaGenders = [];
        @endphp

        <div class="rounded-xl {{ $bgClass }} p-4 border border-slate-200">
          {{-- HEADER ASRAMA --}}
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-2 text-[11px] font-semibold text-slate-700">
            <span class="uppercase tracking-wide">
              {{ $asrama['name'] }}
            </span>
            <span class="text-slate-600">
              Bed terisi {{ $asrama['occupied_beds'] ?? 0 }}/{{ $asrama['total_beds'] ?? 0 }}
            </span>
          </div>

          {{-- =================================================
            PREPROCESS KAMAR + BED + SEGMENT + SPACE PER KAMAR
          ================================================== --}}
          @php
            $rooms = $asrama['rooms'] ?? [];
            $roomCount = count($rooms);
          @endphp

          @foreach ($rooms as $idx => $room)
            @php
              $roomStatus = strtolower($room['status'] ?? '');
              $bedCount   = max((int) ($room['total_beds'] ?? 0), 4);
              $beds       = $room['beds'] ?? [];

              // Normalisasi jumlah bed
              if (count($beds) < $bedCount) {
                $beds = array_merge(
                  $beds,
                  array_fill(0, $bedCount - count($beds), ['state' => 'available'])
                );
              } else {
                $beds = array_slice($beds, 0, $bedCount);
              }

              $roomNo = $room['no'] ?? '-';
              $roomKey = (string) ($room['no'] ?? '-');

              // Push bed ke bedItems + bedsFlat (bed asli saja)
              foreach ($beds as $i => $b) {
                $state = $b['state'] ?? 'available';
                if ($roomStatus === 'rusak') {
                  $state = 'rusak';
                } elseif ($roomStatus === 'tidak tersedia') {
                  $state = 'unavailable';
                }

                $bedSlot = $i + 1;
                  $bedId = $b['bed_id']
                  ?? $b['id']
                  ?? $b['uuid']
                  // TODO: backend sebaiknya kirim bed_id stabil.
                  ?? ('bed-' . $asramaKey . '-' . $roomKey . '-' . $bedSlot);

                $occupant =
                  $b['name']
                  ?? $b['santri_name']
                  ?? $b['student_name']
                  ?? $b['penghuni']
                  ?? null;
                if (!$occupant && !empty($b['peserta_id'])) {
                  $occupant = $pesertaNameMap[$b['peserta_id']] ?? null;
                }

                if ($state === 'occupied' && !empty($b['gender'])) {
                  $asramaGenders[] = $b['gender'];
                }

                $bedEntry = [
                  'type' => 'bed',
                  'bed_id' => $bedId,
                  'room_no' => $roomNo,
                  'bed_slot' => $bedSlot,
                  'state' => $state,
                  'gender' => $b['gender'] ?? null,
                  'peserta_id' => $b['peserta_id'] ?? null,
                  'occupant' => $occupant,
                ];

                $bedsFlat[] = $bedEntry;
                $bedItems[] = $bedEntry;
              }

              // Hitung boundary kamar (segment) berdasarkan cursor
              $remaining = $bedCount;
              $start     = $cursor;
              $isFirst   = true;

              while ($remaining > 0) {
                $row      = intdiv($start, $BED_COLS);
                $colStart = $start % $BED_COLS;
                $cells    = min($remaining, $BED_COLS - $colStart);

                $roomSegments[] = [
                  'row'       => $row + 1,
                  'col_start' => $colStart + 1,
                  'col_end'   => $colStart + $cells + 1,
                  'label'     => $isFirst ? ('Kamar ' . ($room['no'] ?? '')) : null,
                ];

                $start     += $cells;
                $remaining -= $cells;
                $isFirst    = false;
              }

              // geser cursor setelah bed kamar
              $cursor += $bedCount;

              // Sisipkan space antar kamar (kecuali kamar terakhir)
              if ($idx < $roomCount - 1 && $ROOM_GAP_CELLS > 0) {
                for ($s = 0; $s < $ROOM_GAP_CELLS; $s++) {
                  $bedItems[] = ['type' => 'spacer', 'state' => 'spacer']; // cell kosong
                }
                $cursor += $ROOM_GAP_CELLS;
              }
            @endphp
          @endforeach

          {{-- =================================================
            RENDER DENAH (NO TUMPUK + TOOLTIP + ARROW NAV)
          ================================================== --}}
          @php
            $asramaGenderUnique = array_values(array_unique($asramaGenders));
            $asramaGender = count($asramaGenderUnique) === 1 ? $asramaGenderUnique[0] : null;
          @endphp
          <div class="denah-wrap mt-3" style="padding-top: {{ $LABEL_PAD }}px;">
            {{-- Tooltip --}}
            <div class="denah-tooltip" data-denah-tooltip></div>

            {{-- GRID BEDS --}}
            <div
              class="denah-beds grid"
              data-denah-grid
              data-cols="{{ $BED_COLS }}"
              style="
                grid-template-columns: repeat({{ $BED_COLS }}, {{ $BED_SIZE }}px);
                grid-auto-rows: {{ $BED_SIZE }}px;
                gap: {{ $BED_GAP }}px;
              "
            >
              @foreach ($bedItems as $i => $bed)
                @php
                  $type = $bed['type'] ?? 'bed';
                  $state = $bed['state'] ?? 'available';
                  $gender = strtolower((string) ($bed['gender'] ?? ''));
                  $bedId = $bed['bed_id'] ?? null;
                  $roomNo = $bed['room_no'] ?? null;
                  $pesertaId = $bed['peserta_id'] ?? null;
                  $occupant = $bed['occupant'] ?? null;
                  $asramaGenderAttr = strtolower((string) ($asramaGender ?? ''));

                  if ($state === 'spacer') {
                    $color = 'transparent';
                  } elseif ($state === 'unavailable') {
                    $color = '#94a3b8';
                  } elseif ($state === 'rusak') {
                    $color = '#ef4444';
                  } elseif ($state === 'occupied') {
                    $color = in_array($gender, ['perempuan','wanita','p'])
                      ? '#ec4899'
                      : '#3b82f6';
                  } else {
                    $color = '#facc15';
                  }

                  $isInteractive = $type === 'bed' && $state !== 'spacer';
                  $isDraggable = $isInteractive && $state === 'occupied';

                  $tooltipTitle = $state === 'spacer'
                    ? ''
                    : trim(implode(' | ', array_filter([
                        $roomNo ? 'Kamar '.$roomNo : null,
                        $state === 'occupied' ? ($occupant ? $occupant : 'Terisi') : null,
                        $state === 'available' ? 'Bisa diisi' : null,
                        $state === 'rusak' ? 'Rusak' : null,
                        $state === 'unavailable' ? 'Tidak tersedia' : null,
                      ])));
                @endphp

                <div
                  class="denah-bed border border-slate-300 {{ $state === 'spacer' ? 'denah-spacer' : '' }}"
                  style="
                    width: {{ $BED_SIZE }}px;
                    height: {{ $BED_SIZE }}px;
                    background-color: {{ $color }};
                  "
                  data-index="{{ $i }}"
                  @if($isInteractive)
                    tabindex="0"
                    role="button"
                    aria-label="{{ $tooltipTitle ?: 'Bed' }}"
                    data-bed="1"
                  data-bed-id="{{ $bedId }}"
                  data-room-no="{{ $roomNo }}"
                  data-state="{{ $state }}"
                  data-peserta-id="{{ $pesertaId }}"
                  data-gender="{{ $gender }}"
                  data-asrama-gender="{{ $asramaGenderAttr }}"
                  data-tooltip="{{ e($tooltipTitle) }}"
                    @if($isDraggable) draggable="true" @endif
                  @else
                    data-skip="1"
                  @endif
                ></div>
              @endforeach
            </div>

            {{-- BORDER KAMAR --}}
            <div
              class="denah-layer denah-border grid"
              style="
                top: {{ $LABEL_PAD }}px;
                grid-template-columns: repeat({{ $BED_COLS }}, {{ $BED_SIZE }}px);
                grid-auto-rows: {{ $BED_SIZE }}px;
                gap: {{ $BED_GAP }}px;
              "
            >
              @foreach ($roomSegments as $seg)
                <div
                  class="rounded-md border-2 border-slate-700/70"
                  style="
                    grid-column: {{ $seg['col_start'] }} / {{ $seg['col_end'] }};
                    grid-row: {{ $seg['row'] }};
                  "
                ></div>
              @endforeach
            </div>

            {{-- LABEL KAMAR --}}
            <div
              class="denah-layer denah-label grid"
              style="
                top: {{ $LABEL_PAD }}px;
                grid-template-columns: repeat({{ $BED_COLS }}, {{ $BED_SIZE }}px);
                grid-auto-rows: {{ $BED_SIZE }}px;
                gap: {{ $BED_GAP }}px;
              "
            >
              @foreach ($roomSegments as $seg)
                @if (!empty($seg['label']))
                  <div
                    style="
                      grid-column: {{ $seg['col_start'] }} / {{ $seg['col_end'] }};
                      grid-row: {{ $seg['row'] }};
                    "
                  >
                    <span class="denah-label-badge">{{ $seg['label'] }}</span>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </div>

      @empty
        <div class="text-sm text-slate-500">
          Konfigurasi asrama belum tersedia.
        </div>
      @endforelse

    </div>

    {{-- =====================================================
      LEGEND
    ===================================================== --}}
    <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-600">
      @foreach ([
        ['#3b82f6','Laki-laki'],
        ['#ec4899','Perempuan'],
        ['#facc15','Bisa diisi'],
        ['#ef4444','Rusak'],
        ['#94a3b8','Tidak tersedia'],
      ] as [$color, $label])
        <div class="flex items-center gap-2">
          <span class="h-3 w-3 rounded" style="background:{{ $color }}"></span>
          {{ $label }}
        </div>
      @endforeach
    </div>

  </div>

  <div class="rounded-xl bg-white p-4 border border-slate-200">
    <div class="text-sm font-semibold text-slate-900">Daftar Peserta</div>
    <div class="mt-3 overflow-x-auto" data-peserta-table="1" data-unassign-drop="1">
      <table class="min-w-full text-sm text-slate-700 border border-slate-200">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
          <tr>
            <th class="px-3 py-2 text-left border-b border-slate-200">Nomor Registrasi</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Nama</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Nomor Handphone</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Asal Instansi</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Kompetensi</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Gender</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Asrama</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Kamar</th>
            <th class="px-3 py-2 text-left border-b border-slate-200">Bed</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pesertaRows as $row)
            <tr
              class="peserta-row odd:bg-white even:bg-slate-50"
              data-peserta-id="{{ $row['peserta_id'] ?? '' }}"
              data-bed-id="{{ $row['bed_id'] ?? '' }}"
              data-gender="{{ strtolower((string) ($row['gender'] ?? '')) }}"
              data-payload="{{ e(json_encode(['pesertaId' => $row['peserta_id'] ?? null, 'bedId' => $row['bed_id'] ?? null, 'gender' => $row['gender'] ?? null])) }}"
              draggable="true"
            >
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['nomor_registrasi'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['nama'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['no_hp'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['instansi'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['kompetensi'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['gender'] ? ucfirst($row['gender']) : '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['asrama'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['kamar'] ?? '-' }}</td>
              <td class="px-3 py-2 border-b border-slate-200" draggable="true">{{ $row['bed'] ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-3 py-3 text-center text-slate-500">Belum ada data peserta.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- =====================================================
   | RELATION MANAGERS DEFAULT
   ===================================================== --}}
  @if ((! $hasCombinedRelationManagerTabsWithContent) || (! count($relationManagers)))
    {{ $this->infolist ?? $this->form }}
  @endif

</div>
</x-filament-panels::page>
