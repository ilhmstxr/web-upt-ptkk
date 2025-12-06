<x-filament-panels::page>
    <div class="space-y-6">

        {{-- 1. Statistik Token --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-filament::card class="text-center">
                <p class="text-sm text-gray-500 font-medium">Total Pendaftaran</p>
                <h2 class="text-3xl font-bold text-indigo-600 mt-1">{{ $totalPendaftaran }}</h2>
            </x-filament::card>
            
            <x-filament::card class="text-center">
                <p class="text-sm text-gray-500 font-medium">Token Sudah Tergenerate</p>
                <h2 class="text-3xl font-bold text-green-600 mt-1">{{ $tokensGenerated }}</h2>
            </x-filament::card>
            
            <x-filament::card class="text-center">
                <p class="text-sm text-gray-500 font-medium">Token Belum Tergenerate (Pending)</p>
                <h2 class="text-3xl font-bold text-danger-600 mt-1">{{ $tokensPending }}</h2>
            </x-filament::card>
        </div>

        <hr class="border-gray-200">
        
        {{-- 2. Aksi Generate & Download --}}
        <x-filament::card>
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Aksi Generate & Download</h2>
            
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <x-filament::button 
                    wire:click="generateTokens" 
                    color="primary"
                    :disabled="$tokensPending === 0"
                >
                    Generate {{ $tokensPending }} Token Pending Sekarang
                </x-filament::button>
                
                <x-filament::button 
                    tag="a" 
                    href="{{ route('admin.download.tokens') }}" 
                    color="success" 
                    target="_blank"
                >
                    Download Semua Daftar Token
                </x-filament::button>
            </div>
        </x-filament::card>


        {{-- 3. Daftar Pendaftaran Tanpa Token --}}
        <x-filament::card>
            <h2 class="text-xl font-bold mb-4 text-gray-800">Daftar Pendaftaran Tanpa Token (Pending)</h2>

            @if($pendingPendaftarans->isEmpty())
                <p class="text-gray-500">Semua pendaftaran yang terverifikasi sudah memiliki Token Assessment.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4">ID Daftar</th>
                                <th class="py-3 px-4">Nama Peserta</th>
                                <th class="py-3 px-4">Pelatihan</th>
                                <th class="py-3 px-4">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingPendaftarans as $p)
                            <tr>
                                <td class="py-4 px-4">{{ $p->id }}</td>
                                <td class="py-4 px-4">{{ $p->peserta->nama ?? 'N/A' }}</td>
                                <td class="py-4 px-4">{{ $p->pelatihan->nama_pelatihan ?? 'N/A' }}</td>
                                <td class="py-4 px-4">{{ \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::card>

    </div>
</x-filament-panels::page>
