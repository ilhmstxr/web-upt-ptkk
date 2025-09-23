@php
    /**
     * Props yang diharapkan dari ViewField Filament:
     * - $statePath: path state penuh untuk field yang menyimpan 'path' gambar (wajib).
     * - $folder   : sub-folder di disk public (opsional, default 'pertanyaan/opsi').
     * - $accept   : MIME accept untuk input file (opsional, default 'image/*').
     * - $uploadUrl: endpoint upload (opsional, default route('upload.store')).
     * - $currentUrl: URL gambar saat ini untuk preview (opsional).
     */
    $statePath  = $statePath ?? ($getStatePath() ?? null);
    $folder     = $folder     ?? 'pertanyaan/opsi';
    $accept     = $accept     ?? 'image/*';
    $uploadUrl  = $uploadUrl  ?? (\Illuminate\Support\Facades\Route::has('pertanyaan.update'));
    $value      = $getState ? ($getState() ?? null) : null; // path relatif yang tersimpan di DB
    $currentUrl = $currentUrl ?? ($value ? \Illuminate\Support\Facades\Storage::disk('public')->url($value) : null);
@endphp

<div
    x-data="{
        value: @js($value),
        preview: @js($currentUrl),
        uploading: false,
        error: null,
        async upload(e) {
            this.error = null;
            const file = e.target.files[0];
            if (!file) return;
            this.uploading = true;
            const form = new FormData();
            form.append('file', file);
            form.append('folder', @js($folder));
            try {
                const res = await fetch(@js($uploadUrl), {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '{{ csrf_token() }}',
                    },
                    body: form,
                });
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(text || 'Upload gagal');
                }
                const data = await res.json();
                this.value = data.path;
                this.preview = data.url;
                // sinkronkan ke state Filament
                if ($wire && {{ $statePath ? 'true' : 'false' }}) {
                    $wire.set(@js($statePath), this.value);
                }
            } catch (err) {
                this.error = err?.message || 'Upload gagal';
                console.error(err);
            } finally {
                this.uploading = false;
            }
        }
    }"
    class="space-y-2"
>
    <input type="hidden" :value="value" />

    <div class="flex items-center gap-3">
        <input type="file" accept="{{ $accept }}" x-on:change="upload" class="fi-input fi-file px-3 py-2 border rounded-md" />
        <template x-if="uploading">
            <span class="text-sm">Mengunggah…</span>
        </template>
    </div>

    <template x-if="preview">
        <div class="rounded-md overflow-hidden border w-48">
            <img :src="preview" alt="Preview" class="block w-full h-auto">
        </div>
    </template>

    <template x-if="error">
        <p class="text-sm text-red-600" x-text="error"></p>
    </template>
</div>
