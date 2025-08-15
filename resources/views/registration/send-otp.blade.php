@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="text-center mb-4">Login / OTP</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('otp.send') }}" method="POST" class="mx-auto" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Nomor WA" value="{{ old('phone') }}">
        </div>
        <button type="submit" class="btn btn-success w-100">Kirim OTP</button>
    </form>
</div>
@endsection
