@extends('layouts.admin')

@section('title','Detail Kelompok')

@section('page-title','Detail Kelompok')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-5">
        {{ $group->nama_kelompok }}
    </h2>

    <table class="table-auto">

        <tr>
            <td class="pr-5 font-semibold">Angkatan</td>
            <td>{{ $group->angkatan }}</td>
        </tr>

        <tr>
            <td class="pr-5 font-semibold">Kelas</td>
            <td>{{ $group->kelas }}</td>
        </tr>

        <tr>
            <td class="pr-5 font-semibold">Dosen</td>
            <td>{{ $group->dosen->name }}</td>
        </tr>

    </table>

    <hr class="my-6">

    <h3 class="font-bold mb-3">

        Anggota Kelompok

    </h3>

    <ul class="list-disc ml-6">

        @forelse($group->members as $member)

            <li>{{ $member->name }}</li>

        @empty

            <li>Belum ada anggota.</li>

        @endforelse

    </ul>

</div>

@endsection