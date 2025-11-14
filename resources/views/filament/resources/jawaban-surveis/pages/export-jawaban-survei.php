{{-- minimal blade agar Filament Page tidak error; mount() sudah mengembalikan download --}}
<x-filament::page>
    <div style="padding: 2rem;">
        <h2>Menyiapkan unduhan…</h2>
        <p>Jika file tidak otomatis ter-download, silakan <a href="{{ url()->current() }}">muat ulang</a> halaman ini.</p>
    </div>
</x-filament::page>
