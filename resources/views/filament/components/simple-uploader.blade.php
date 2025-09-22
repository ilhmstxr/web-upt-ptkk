@php
    /** @var string $targetPath */
    /** @var string|null $existingUrl */
@endphp

<div x-data="{
    uploading: false,
    preview: @js($existingUrl),
    error: null,
    async upload(e) {
        this.error = null;
        const f = e.target.files[0];
        if (!f) return;

        this.uploading = true;
        const fd = new FormData();
        fd.append('file', f);

        const res = await fetch('{{ route('admin.uploads.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: fd
        });

        const data = await res.json();
        this.uploading = false;

        if (!res.ok) {
            this.error = data?.message ?? 'Upload gagal';
            return;
        }

        this.preview = data.url;

        // set nilai ke field target (kolom 'gambar' di repeater item ini)
        $wire.set(@js($targetPath), data.path); // Livewire v3
    }
}" class="space-y-2">
    <input type="file" accept="image/*" @change="upload" class="fi-input fi-input-file">
    <div x-show="uploading" class="text-sm text-gray-500">Uploading…</div>

    <template x-if="preview">
        <img :src="preview" alt="preview" class="h-24 rounded-md object-cover">
    </template>

    <p x-show="error" class="text-sm text-danger-600" x-text="error"></p>
</div>
