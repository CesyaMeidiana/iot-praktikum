<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show(): View|RedirectResponse
    {
        if (! session('otp_email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => session('otp_email'),
        ]);
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('register')->withErrors([
                'otp_code' => 'Akun tidak ditemukan, silakan daftar ulang.',
            ]);
        }

        if ($user->otp_code !== $request->otp_code) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP salah.',
            ]);
        }

        if (! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode.',
            ]);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('otp_email');

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan login.');
    }

    /**
     * Resend a new OTP code to the user's email.
     */
    public function resend(): RedirectResponse
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('register');
        }

        $otpCode = (string) random_int(100000, 999999);

        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpMail($otpCode, $user->name));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}