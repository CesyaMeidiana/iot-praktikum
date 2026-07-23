<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nim_nip' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'captcha'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    $user = \App\Models\User::where('nim_nip', $this->input('nim_nip'))->first();

    if (! $user) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'nim_nip' => 'NIM/NIP tidak ditemukan.',
        ]);
    }

    if (! Auth::attempt($this->only('nim_nip', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'password' => 'Kata sandi yang Anda masukkan salah.',
        ]);
    }

    if (is_null(Auth::user()->email_verified_at)) {
        $email = Auth::user()->email;

        Auth::logout();

        RateLimiter::hit($this->throttleKey());

        session(['otp_email' => $email]);

        throw ValidationException::withMessages([
            'nim_nip' => 'Email belum diverifikasi. Silakan cek email untuk kode OTP, atau klik "Verifikasi sekarang" di bawah.',
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}
    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nim_nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
    Str::lower($this->string('nim_nip')).'|'.$this->ip()
);
    }
}