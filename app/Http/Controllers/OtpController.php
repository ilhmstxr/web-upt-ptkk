<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    public function showSendForm()
    {
        return view('auth.send-otp');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|string',
        ]);

        // Cari user berdasarkan email atau phone
        $user = User::where('email', $request->email)
                    ->orWhere('phone', $request->phone)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan']);
        }

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan di session (atau bisa juga di DB)
        Session::put('otp', $otp);
        Session::put('otp_user_id', $user->id);
        Session::put('otp_expire', now()->addMinutes(5));

        // Kirim OTP via email
        if ($request->email) {
            Mail::raw("Kode OTP Anda: $otp", function($message) use ($request){
                $message->to($request->email)
                        ->subject('Kode OTP Login');
            });
        }

        // Kirim OTP via WhatsApp (Twilio)
        if ($request->phone) {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $from = env('TWILIO_WHATSAPP_FROM'); // misal: 'whatsapp:+14155238886'
            $twilio = new Client($sid, $token);

            $twilio->messages->create(
                'whatsapp:'.$request->phone,
                [
                    'from' => $from,
                    'body' => "Kode OTP Anda: $otp"
                ]
            );
        }

        // Redirect ke halaman verifikasi
        return redirect()->route('otp.verify.form')->with('success', 'Kode OTP telah dikirim.');
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpSession = Session::get('otp');
        $otpUserId = Session::get('otp_user_id');
        $otpExpire = Session::get('otp_expire');

        if (!$otpSession || now()->greaterThan($otpExpire)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
        }

        if ($request->otp != $otpSession) {
            return back()->withErrors(['otp' => 'Kode OTP salah']);
        }

        // Login user otomatis
        $user = User::find($otpUserId);
        auth()->login($user);

        // Hapus session OTP
        Session::forget(['otp', 'otp_user_id', 'otp_expire']);

        return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }
}
