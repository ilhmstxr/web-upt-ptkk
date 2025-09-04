<div>
    @if($peserta->isNotEmpty())
        <ul class="list-disc list-inside space-y-1">
            @foreach($peserta as $p)
                <li>{{ $p->nama }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">Tidak ada data peserta.</p>
    @endif
</div>