<x-guest-layout>
    @if ($errors->any())
    <div style="background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:16px; border-radius:8px; margin-bottom:20px;">
        <strong>Registrasi gagal, periksa kembali:</strong>
        <ul style="margin:8px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form method="POST" action="{{ route('register') }}">

        @csrf

        <h2 class="text-2xl font-bold text-center mb-6">
            Register Account
        </h2>

        {{-- Nama --}}
        <div>

            <x-input-label for="name" value="Nama Lengkap"/>

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
            />

        </div>

        {{-- Email --}}
        <div class="mt-4">

            <x-input-label for="email" value="Email"/>

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
            />

        </div>

        {{-- NIM NIP --}}
        <div class="mt-4">

            <x-input-label
                for="nim_nip"
                value="NIM / NIP"
            />

            <x-text-input
                id="nim_nip"
                class="block mt-1 w-full"
                type="text"
                name="nim_nip"
                :value="old('nim_nip')"
                required
            />

            <p
    id="roleDetected"
    class="hidden mt-3 px-3 py-2 rounded-lg text-sm font-semibold">
</p>

{{-- FORM MAHASISWA --}}
<div id="mahasiswaFields" class="hidden mt-5 space-y-4">

    {{-- Angkatan --}}
    <div>

        <x-input-label for="angkatan" value="Angkatan" />

        <select
            name="angkatan"
            id="angkatan"
            class="block w-full mt-1 rounded-md border-gray-300">

            <option value="">-- Pilih Angkatan --</option>

            <option>2023</option>
            <option>2024</option>
            <option>2025</option>
            <option>2026</option>
            <option>2027</option>
            <option>2028</option>

        </select>

    </div>

    {{-- Kelas --}}
    <div>

        <x-input-label for="kelas" value="Kelas" />

        <select
            name="kelas"
            id="kelas"
            class="block w-full mt-1 rounded-md border-gray-300">

            <option value="">-- Pilih Kelas --</option>

            <option>BM A</option>
            <option>BM B</option>

        </select>

    </div>

    {{-- Kode Kelas --}}
    <div>

        <x-input-label for="passcode" value="Kode Kelas" />

        <x-text-input
            id="passcode"
            class="block mt-1 w-full"
            type="text"
            name="passcode"
            :value="old('passcode')" />

        <x-input-error
            :messages="$errors->get('passcode')"
            class="mt-2"/>

    </div>

    {{-- Belum punya kode --}}
    <div class="flex items-center">

        <input
            id="no_passcode"
            type="checkbox"
            name="no_passcode"
            value="1"
            class="rounded border-gray-300">

        <label
            for="no_passcode"
            class="ml-2 text-sm text-gray-600">

            Saya belum memiliki kode kelas

        </label>

    </div>

</div>
        </div>

       

        {{-- FORM DOSEN --}}
<div id="dosenFields" class="hidden mt-5 space-y-4">

    {{-- Passcode Dosen --}}
    <div>

        <x-input-label for="passcode" value="Passcode Dosen" />

        <x-text-input
            id="passcode_dosen"
            class="block mt-1 w-full"
            type="password"
            name="passcode_dosen" />

    <x-input-error
    :messages="$errors->get('passcode_dosen')"
    class="mt-2"/>

    </div>


</div>

        {{-- FORM KAJUR --}}
<div id="kajurFields" class="hidden mt-5 space-y-4">

    {{-- Passcode Kajur --}}
    <div>

        <x-input-label for="passcode_kajur" value="Passcode Kajur" />

        <x-text-input
            id="passcode_kajur"
            class="block mt-1 w-full"
            type="password"
            name="passcode_kajur" />

    <x-input-error
    :messages="$errors->get('passcode_kajur')"
    class="mt-2"/>

    </div>

</div>

{{-- Nomor HP --}}
<div class="mt-5">

    <x-input-label
        for="phone"
        value="Nomor HP"/>

    <x-text-input
        id="phone"
        class="block mt-1 w-full"
        type="text"
        name="phone"
        required/>

</div>
{{-- Password --}}
<div class="mt-5">
    <x-input-label for="password" value="Password"/>
    <div class="flex items-center gap-2">
        <x-text-input
            id="password"
            class="block mt-1 w-full"
            type="password"
            name="password"
            required/>
        <button type="button" onclick="togglePw('password', this)" class="mt-1 text-gray-500">👁️</button>
    </div>
</div>

{{-- Konfirmasi Password --}}
<div class="mt-5">
    <x-input-label for="password_confirmation" value="Konfirmasi Password"/>
    <div class="flex items-center gap-2">
        <x-text-input
            id="password_confirmation"
            class="block mt-1 w-full"
            type="password"
            name="password_confirmation"
            required/>
        <button type="button" onclick="togglePw('password_confirmation', this)" class="mt-1 text-gray-500">👁️</button>
    </div>
</div>


<div class="mt-6">

    <x-input-label for="captcha" value="Captcha" />

    <img id="captcha-img" src="{{ captcha_src('flat') }}" class="mb-2 mt-1 rounded-lg border border-gray-200 cursor-pointer" onclick="refreshCaptcha()" alt="captcha">
    <a href="javascript:void(0)" onclick="refreshCaptcha()" class="block text-sm text-blue-600 hover:underline mb-2">
        Ganti captcha
    </a>

    <x-text-input
        id="captcha"
        class="block mt-1 w-full"
        type="text"
        name="captcha"
        autocomplete="off"
        required/>

    <x-input-error
        :messages="$errors->get('captcha')"
        class="mt-2"/>

</div>

<div class="flex items-center justify-end mt-6">

    <a
        href="{{ route('login') }}"
        class="underline text-sm text-gray-600">

        Sudah punya akun?

    </a>

    <x-primary-button class="ms-4">

        Register

    </x-primary-button>

</div>

    </form>
<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.textContent = isHidden ? '🙈' : '👁️';
}
</script>

<script>
function refreshCaptcha() {
    fetch("{{ route('captcha.refresh') }}")
        .then(res => res.json())
        .then(data => document.getElementById('captcha-img').src = data.captcha);
}
</script>

    <script>

const nim = document.getElementById('nim_nip');
const role = document.getElementById('roleDetected');
const mahasiswaFields = document.getElementById('mahasiswaFields');
const dosenFields = document.getElementById('dosenFields');
const kajurFields = document.getElementById('kajurFields');
const passcode = document.getElementById('passcode');
const noPasscode = document.getElementById('no_passcode');

if (passcode && noPasscode) {

    noPasscode.addEventListener('change', function () {

        passcode.disabled = this.checked;

        if (this.checked) {
            passcode.value = '';
        }

    });

}

nim.addEventListener('input', function(){

    let prefix = this.value.charAt(0);

    role.classList.remove('hidden');

    if(prefix=="2"){

    mahasiswaFields.classList.remove('hidden');
    dosenFields.classList.add('hidden');
    kajurFields.classList.add('hidden');

    role.className="mt-3 px-3 py-2 rounded-lg bg-green-100 text-green-700";

    role.innerHTML="🟢 Role : Mahasiswa";

}

    else if(prefix=="1"){

    mahasiswaFields.classList.add('hidden');
    dosenFields.classList.remove('hidden');
    kajurFields.classList.add('hidden');

    role.className="mt-3 px-3 py-2 rounded-lg bg-blue-100 text-blue-700";

    role.innerHTML="🔵 Role : Dosen";

}

    else if(prefix=="3"){

    mahasiswaFields.classList.add('hidden');
    dosenFields.classList.add('hidden');
    kajurFields.classList.remove('hidden');

    role.className="mt-3 px-3 py-2 rounded-lg bg-purple-100 text-purple-700";

    role.innerHTML="🟣 Role : Kajur";

}

    else{

    mahasiswaFields.classList.add('hidden');
    dosenFields.classList.add('hidden');
    kajurFields.classList.add('hidden');

    role.className="mt-3 px-3 py-2 rounded-lg bg-red-100 text-red-700";

    role.innerHTML="❌ NIM / NIP tidak dikenali";

}

});
if (nim.value) {
    nim.dispatchEvent(new Event('input'));
}
</script>

</x-guest-layout>