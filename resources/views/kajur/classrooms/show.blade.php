@extends('layouts.kajur')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-3xl font-bold mb-6">

            {{ $classroom->name }}

        </h2>

        <div class="grid grid-cols-2 gap-6">

            <div>

                <p class="text-gray-500">Dosen</p>

                <h4 class="font-semibold">

                    {{ $classroom->lecturer->name }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">Passcode</p>

                <h4 class="font-semibold">

                    {{ $classroom->passcode }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">Mahasiswa</p>

                <h4 class="font-semibold">

                    {{ $classroom->students->count() }}

                </h4>

            </div>

            <div>

                <p class="text-gray-500">Kelompok</p>

                <h4 class="font-semibold">

                    {{ $classroom->groups->count() }}

                </h4>

            </div>

        </div>

    </div>

    <div class="grid grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-xl font-bold mb-4">

                Mahasiswa

            </h3>

            @forelse($classroom->students as $student)

                <div class="border-b py-3">

                    {{ $student->name }}

                </div>

            @empty

                <p>Belum ada mahasiswa.</p>

            @endforelse

        </div>

        <div class="bg-white rounded-xl shadow p-6">

            <h3 class="text-xl font-bold mb-4">

                Kelompok

            </h3>

            @forelse($classroom->groups as $group)

                <div class="border-b py-3">

                    <strong>{{ $group->nama_kelompok }}</strong>

                    <div class="text-sm text-gray-500">

                        {{ $group->members->count() }} anggota

                    </div>

                </div>

            @empty

                <p>Belum ada kelompok.</p>

            @endforelse

        </div>

    </div>

</div>

@endsection