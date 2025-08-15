@component('mail::message')
# Selamat Datang, {{ $user->name }}

Pendaftaran akun kamu telah berhasil! 🎉

**Detail Akun:**
- Email: {{ $user->email }}
- Tanggal Daftar: {{ $user->created_at->format('d M Y') }}

@component('mail::button', ['url' => url('/')])
Masuk ke Aplikasi
@endcomponent

Terima kasih,  
{{ config('app.name') }}
@endcomponent
