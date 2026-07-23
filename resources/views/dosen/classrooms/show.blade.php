@extends('layouts.dosen')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-3xl font-bold">

            {{ $classroom->name }}

        </h2>

        <div class="grid grid-cols-2 gap-5 mt-6">

            <div>

                <p class="text-gray-500">
                    Angkatan
                </p>

                <h4 class="font-semibold">

                    {{ $classroom->academic_year }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">
                    Kelas
                </p>

                <h4 class="font-semibold">

                    {{ $classroom->class_name }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">
                    Passcode
                </p>

                <h4 class="font-bold text-blue-600 text-xl">

                    {{ $classroom->passcode }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">
                    Status
                </p>

                <h4>

                    {{ $classroom->status ? 'Aktif' : 'Tidak Aktif' }}

                </h4>

            </div>

        </div>

    </div>

    <div class="grid grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <h3 class="text-xl font-bold">

                    Mahasiswa

                </h3>

            </div>

            <div class="mt-5">

                @forelse($classroom->students as $student)

                    <div class="border-b py-3">

                        {{ $student->name }}

                    </div>

                @empty

                    <p class="text-gray-400">

                        Belum ada mahasiswa.

                    </p>

                @endforelse

            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between items-center">

                <h3 class="text-xl font-bold">

                    Kelompok

                </h3>

                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                    <a
                        href="{{ route('dosen.groups.create', $classroom) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                        + Buat Kelompok

                    </a>

                </button>

            </div>

            <div class="mt-5">

                @forelse($classroom->groups as $group)

                    <div class="border-b py-3">

                        @forelse($classroom->groups as $group)

<div class="border rounded-lg p-4 mb-4">

    <div class="flex justify-between items-center">

        <div>

            <h4 class="font-bold">

                {{ $group->nama_kelompok }}

            </h4>

            <small class="text-gray-500">

                {{ $group->members->count() }} Anggota

            </small>

        </div>

        <a
            href="{{ route('dosen.groups.show',$group) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded">

            Detail

        </a>

    </div>

</div>

@empty

<p class="text-gray-400">

Belum ada kelompok.

</p>

@endforelse

                    </div>

                @empty

                    <p class="text-gray-400">

                        Belum ada kelompok.

                    </p>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection