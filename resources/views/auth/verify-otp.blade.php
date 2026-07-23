<x-guest-layout>

    <h2 class="text-2xl font-bold text-center mb-4 text-slate-800">
        Verifikasi Email
    </h2>

    <p class="text-sm text-slate-600 text-center mb-6">
        Kode OTP telah dikirim ke <strong>{{ $email }}</strong>.
        Masukkan 6 digit kode tersebut untuk mengaktifkan akun Anda.
    </p>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.otp.verify') }}">
        @csrf

        <div class="mb-5">
            <label for="otp_code" class="block text-slate-700 font-medium mb-2 text-center">
                Kode OTP
            </label>
            <input id="otp_code" type="text" name="otp_code" maxlength="6" inputmode="numeric" autocomplete="one-time-code"
                placeholder="123456"
                class="w-full text-center tracking-[0.5em] text-2xl font-bold border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition"
                required autofocus>
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-blue-200">
            Verifikasi
        </button>
    </form>

    <form method="POST" action="{{ route('verification.otp.resend') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-blue-600 hover:underline">
            Kirim ulang kode OTP
        </button>
    </form>

    <div class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:underline">
            Kembali ke halaman login
        </a>
    </div>

</x-guest-layout>