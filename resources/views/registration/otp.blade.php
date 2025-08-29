{{-- resources/views/auth/verify-otp.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="text-center text-success fw-bold mb-4">
                        🔒 Verifikasi OTP
                    </h4>
                    <p class="text-center text-muted mb-4">
                        Masukkan kode OTP yang telah kami kirimkan ke <strong>WhatsApp</strong> atau <strong>Email</strong> Anda.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" 
                                class="form-control rounded-pill shadow-sm" 
                                placeholder="nama@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="otp" class="form-label">Kode OTP</label>
                            <input type="text" name="otp" id="otp" maxlength="6" 
                                class="form-control rounded-pill shadow-sm text-center fw-bold fs-4 tracking-widest"
                                placeholder="123456" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 shadow-sm">
                            ✅ Verifikasi
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">Tidak menerima kode? 
                            <a href="#" id="resendOtp" class="text-success fw-semibold">Kirim ulang</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Animasi & Validasi --}}
@push('scripts')
<script>
document.getElementById('otpForm').addEventListener('submit', function(e) {
    const otp = document.getElementById('otp').value;
    if (otp.length !== 6 || isNaN(otp)) {
        e.preventDefault();
        alert('Kode OTP harus 6 digit angka!');
    }
});

// Contoh efek klik kirim ulang
document.getElementById('resendOtp').addEventListener('click', function(e) {
    e.preventDefault();
    alert('Kode OTP baru akan dikirim ke WhatsApp Anda.');
});
</script>
@endpush
@endsection
