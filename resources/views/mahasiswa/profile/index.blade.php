@extends('layouts.mahasiswa')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">

        Profile Mahasiswa

    </h2>

    <div class="space-y-3 mb-8">

        <p><b>Nama :</b> {{ $user->name }}</p>

        <p><b>NIM :</b> {{ $user->nim_nip }}</p>

        <p><b>Angkatan :</b> {{ $user->angkatan }}</p>

        <p><b>Kelas :</b> {{ $user->kelas }}</p>

    </div>

    @if(session('success'))

        <div class="bg-green-100 text-green-700 p-3 rounded mb-5">

            {{ session('success') }}

        </div>

    @endif

    @if(!$classroom)

        <div class="border rounded-lg p-5">

            <h3 class="font-bold text-lg mb-4">

                Gabung Kelas

            </h3>

            <form
                action="{{ route('mahasiswa.join-class') }}"
                method="POST">

                @csrf

                <input
                    type="text"
                    name="passcode"
                    placeholder="Masukkan Kode Kelas"
                    class="w-full border rounded-lg p-3">

                @error('passcode')

                    <p class="text-red-500 mt-2">

                        {{ $message }}

                    </p>

                @enderror

                <button
                    class="mt-4 bg-blue-600 text-white px-5 py-2 rounded">

                    Gabung

                </button>

            </form>

        </div>

    @else

        <div class="border rounded-lg p-5">

            <h3 class="font-bold text-lg mb-4">

                Informasi Kelas

            </h3>

            <p><b>Nama Kelas :</b> {{ $classroom->name }}</p>

            <p><b>Dosen :</b> {{ $classroom->lecturer->name }}</p>
            <p><b>Passcode :</b> {{ $classroom->passcode }}</p>

            <p class="mt-4 text-green-600 font-semibold">

                ✔ Sudah bergabung ke kelas

            </p>

        </div>

    @endif

</div>

@endsection