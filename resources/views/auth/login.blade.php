<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('otp_email'))
        <div class="mb-4 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
            Email kamu belum diverifikasi.
            <a href="{{ route('verification.otp.show') }}" class="underline font-semibold">Verifikasi sekarang</a>
        </div>
    @endif

    <h2 class="text-2xl font-bold text-center mb-6 text-slate-800">
        Login
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- NIM / NIP --}}
        <div class="mb-5">
            <label for="nim_nip" class="block text-slate-700 font-medium mb-2">
                NIM / NIP
            </label>
            <div class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 focus-within:border-blue-500 transition">
                <span class="text-slate-400">🪪</span>
                <input id="nim_nip" type="text" name="nim_nip" value="{{ old('nim_nip') }}"
                    placeholder="Masukkan NIM / NIP"
                    class="w-full border-0 focus:outline-none focus:ring-0 p-0 bg-transparent text-slate-700 placeholder-slate-400"
                    required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
        </div>

        {{-- Kata Sandi --}}
<div class="mb-3">
    <label for="password" class="block text-slate-700 font-medium mb-2">
        Kata Sandi
    </label>
    <div class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 focus-within:border-blue-500 transition">
        <span class="text-slate-400">🔒</span>
        <input id="password" type="password" name="password"
            placeholder="Masukkan kata sandi"
            class="w-full border-0 focus:outline-none focus:ring-0 p-0 bg-transparent text-slate-700 placeholder-slate-400"
            required autocomplete="current-password">
        <button type="button" id="togglePassword" class="text-slate-400 hover:text-slate-600">
            👁️
        </button>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

        {{-- Ingat saya --}}
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        {{-- Captcha --}}
        <div class="mb-6">
            <label for="captcha" class="block text-slate-700 font-medium mb-2">
                Captcha
            </label>
            <img id="captcha-img" src="{{ captcha_src('flat') }}" class="mb-2 rounded-lg border border-slate-200 cursor-pointer" onclick="refreshCaptcha()" alt="captcha">
            <a href="javascript:void(0)" onclick="refreshCaptcha()" class="block text-sm text-blue-600 hover:underline mb-2">
                Ganti captcha
            </a>
            <div class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 focus-within:border-blue-500 transition">
                <input id="captcha" type="text" name="captcha"
                    placeholder="Masukkan teks di atas"
                    class="w-full border-0 focus:outline-none focus:ring-0 p-0 bg-transparent text-slate-700 placeholder-slate-400"
                    required autocomplete="off">
            </div>
            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
        </div>

        {{-- Tombol Login --}}
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-blue-200">
            Login
        </button>

    </form>

   <div class="text-center mt-8">
        <span class="text-slate-500">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
            Register
        </a>
    </div>

</x-guest-layout>

<script>
function refreshCaptcha() {
    fetch("{{ route('captcha.refresh') }}")
        .then(res => res.json())
        .then(data => document.getElementById('captcha-img').src = data.captcha);
}

document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    this.textContent = isHidden ? '🙈' : '👁️';
});
</script>