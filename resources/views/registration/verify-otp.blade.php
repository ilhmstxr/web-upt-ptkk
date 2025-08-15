@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-lg rounded-4">
                <h4 class="text-center text-success fw-bold mb-4">🔒 Verifikasi OTP</h4>
                <p class="text-center text-muted mb-4">
                    Masukkan kode OTP yang telah dikirim ke Email atau WhatsApp Anda.
                </p>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success message --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Form OTP --}}
                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                    <div class="mb-3">
                        <input type="text" name="otp" maxlength="6" class="form-control text-center fw-bold fs-4" placeholder="123456" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                        ✅ Verifikasi & Login
                    </button>
                </form>

                {{-- Kirim ulang OTP --}}
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Tidak menerima kode? 
                        <a href="{{ route('otp.send.form') }}" class="text-success fw-semibold">Kirim ulang</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
