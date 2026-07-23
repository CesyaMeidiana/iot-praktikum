@extends('layouts.dosen')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">

        Buat Kelompok

    </h2>

    <form action="{{ route('dosen.groups.store', $classroom) }}" method="POST">

        @csrf

        <div class="mb-5">

            <label class="block font-semibold mb-2">

                Nama Kelompok

            </label>

            <input
                type="text"
                name="nama_kelompok"
                class="w-full border rounded-lg p-3"
                required>

        </div>

        <div class="mb-6">

            <label class="block font-semibold mb-3">

                Pilih Mahasiswa

            </label>

            @foreach($students as $student)

                <label class="flex items-center mb-3">

                    <input
                        type="checkbox"
                        name="members[]"
                        value="{{ $student->id }}"
                        class="mr-3">

                    {{ $student->name }}

                </label>

            @endforeach

        </div>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

            Simpan Kelompok

        </button>

    </form>

</div>

@endsection