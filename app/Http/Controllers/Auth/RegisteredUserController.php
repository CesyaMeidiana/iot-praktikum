<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Classroom;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    // Kalau email yang didaftarkan sudah ada TAPI belum diverifikasi,
    // hapus data lama supaya bisa daftar ulang pakai email yang sama.
    $existing = User::where('email', $request->email)
        ->whereNull('email_verified_at')
        ->first();

    if ($existing) {
        $existing->delete();
    }

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'nim_nip' => ['required', 'string', 'unique:users,nim_nip'],
        'angkatan' => ['nullable'],
        'kelas' => ['nullable'],
        'passcode' => ['nullable','string'],
        'no_passcode' => ['nullable'],
        'phone' => ['required', 'string', 'max:20'],
        'captcha' => ['required', 'captcha'],
        'password' => [
            'required',
            'confirmed',
            Rules\Password::defaults(),
        ],
    ]);

    Log::info('VALIDASI LOLOS, lanjut cek role'); // LOG

    $prefix = substr($request->nim_nip, 0, 1);
    $role = null;

    if ($prefix == '2') {

    $role = 'Mahasiswa';

    $classroom = null;

    if (!$request->boolean('no_passcode')) {

        if (!$request->filled('passcode')) {

            return back()
                ->withErrors([
                    'passcode' => 'Kode kelas wajib diisi atau centang "Saya belum memiliki kode kelas".'
                ])
                ->withInput();

        }

        $classroom = Classroom::where(
            'passcode',
            strtoupper($request->passcode)
        )->first();

        if (!$classroom) {

            return back()
                ->withErrors([
                    'passcode' => 'Kode kelas tidak valid.'
                ])
                ->withInput();

        }

    }

}

    elseif ($prefix == '1') {

        if ($request->passcode_dosen != env('DOSEN_PASSCODE')) {

            return back()
                ->withErrors([
                    'passcode' => 'Passcode Dosen salah.'
                ])
                ->withInput();

        }

        $role = 'Dosen';

    } elseif ($prefix == '3') {

        if ($request->passcode_kajur != env('KAJUR_PASSCODE')) {

            return back()
                ->withErrors([
                    'passcode' => 'Passcode Kajur salah.'
                ])
                ->withInput();

        }

        $role = 'Kajur';

    } else {

        return back()
            ->withErrors([
                'nim_nip' => 'Digit awal NIM/NIP tidak valid.'
            ])
            ->withInput();

    }

    Log::info('ROLE DITENTUKAN: ' . $role);

    $user = User::create([

        'name' => $request->name,

        'email' => $request->email,

        'nim_nip' => $request->nim_nip,

        'angkatan' => $request->angkatan,

        'kelas' => $request->kelas,

        'phone' => $request->phone,

        'status' => true,

        'password' => Hash::make($request->password),

    ]);

    Log::info('USER BERHASIL DIBUAT', ['id' => $user->id]);

    $user->assignRole($role);

    if ($role == 'Mahasiswa' && isset($classroom)) {

    $classroom->students()->attach($user->id);

}

    event(new Registered($user));

    $otpCode = (string) random_int(100000, 999999);

    $user->update([
        'otp_code' => $otpCode,
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    try {
        Mail::to($user->email)->send(new OtpMail($otpCode, $user->name));
        Log::info('EMAIL OTP BERHASIL DIKIRIM ke ' . $user->email); // LOG
    } catch (\Exception $e) {
        Log::error('EMAIL OTP GAGAL DIKIRIM: ' . $e->getMessage()); // LOG
    }
    session(['otp_email' => $user->email]);

    Log::info('REDIRECT KE HALAMAN VERIFIKASI');

    return redirect()->route('verification.otp.show');
}
}