<?php

use App\Models\Otp;
use Carbon\Carbon;

private function sendOtpToWhatsapp($user)
{
    $otp = rand(100000, 999999); // OTP 6 digit

    // Simpan ke DB
    Otp::create([
        'user_id' => $user->id,
        'code' => $otp,
        'expires_at' => Carbon::now()->addMinutes(5)
    ]);

    // Kirim ke WhatsApp (pakai Fonnte/Wablas)
    $this->sendWhatsApp(
        $user->phone,
        "Kode OTP login Anda adalah: *$otp* \nBerlaku 5 menit."
    );
}
