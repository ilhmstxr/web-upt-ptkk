<!-- Gunakan layout ini untuk struktur halaman -->
<div>
    <!-- Tampilkan form berdasarkan currentStep -->
    @if ($currentStep == 1)
        {{-- Kode HTML untuk Biodata Diri --}}
        {{-- Contoh input: <input type="text" wire:model="name" /> --}}
    @elseif ($currentStep == 2)
        {{-- Kode HTML untuk Biodata Sekolah --}}
        {{-- Contoh input: <input type="text" wire:model="school_name" /> --}}
    @elseif ($currentStep == 3)
        {{-- Kode HTML untuk Lampiran --}}
        {{-- Contoh input: <input type="file" wire:model="ktp_path" /> --}}
        
        <button wire:click="submit" type="button">Kirim</button>
    @endif
</div>