@extends('layouts.dosen')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h2 class="text-3xl font-bold">

                {{ $group->nama_kelompok }}

            </h2>

            <p class="text-gray-500">

                {{ $group->classroom->name }}

            </p>

        </div>

    </div>

    <div class="border rounded-lg p-5">

        <h3 class="text-xl font-bold mb-4">

            Anggota Kelompok

        </h3>

        @forelse($group->members as $member)

            <div class="border-b py-3">

                {{ $member->name }}

            </div>

        @empty

            <p>

                Belum ada anggota.

            </p>

        @endforelse

    </div>

</div>

@endsection