@extends('layouts.dosen')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Buat Kelas
    </h2>

    <form action="{{ route('dosen.classrooms.store') }}" method="POST">

        @csrf

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Nama Praktikum
            </label>

            <input
                type="text"
                name="name"
                class="w-full border rounded-lg p-3"
                required>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Angkatan
            </label>

            <select
                name="academic_year"
                class="w-full border rounded-lg p-3"
                required>

                <option value="">Pilih Angkatan</option>

                @for($i=2023;$i<=2028;$i++)

                    <option value="{{ $i }}">

                        {{ $i }}

                    </option>

                @endfor

            </select>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Kelas
            </label>

            <select
                name="class_name"
                class="w-full border rounded-lg p-3"
                required>

                <option value="">Pilih Kelas</option>

                <option value="BMA">BMA</option>

                <option value="BMB">BMB</option>

            </select>

        </div>

        <button
            class="bg-blue-600 text-white px-6 py-3 rounded-lg">

            Simpan

        </button>

    </form>

</div>

@endsection