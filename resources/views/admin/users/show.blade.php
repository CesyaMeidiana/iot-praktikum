@extends('layouts.admin')

@section('title','Detail User')

@section('page-title','Detail User')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Detail User
    </h2>

    <div class="grid grid-cols-2 gap-6">

        <div>
            <label class="font-semibold">Nama</label>
            <p>{{ $user->name }}</p>
        </div>

        <div>
            <label class="font-semibold">Email</label>
            <p>{{ $user->email }}</p>
        </div>

        <div>
            <label class="font-semibold">NIM / NIP</label>
            <p>{{ $user->nim_nip }}</p>
        </div>

        <div>
            <label class="font-semibold">Role</label>
            <p>{{ $user->roles->first()->name }}</p>
        </div>

        <div>
            <label class="font-semibold">Angkatan</label>
            <p>{{ $user->angkatan }}</p>
        </div>

        <div>
            <label class="font-semibold">Kelas</label>
            <p>{{ $user->kelas }}</p>
        </div>

        <div>
            <label class="font-semibold">Nomor HP</label>
            <p>{{ $user->phone }}</p>
        </div>

        <div>
            <label class="font-semibold">Status</label>

            @if($user->status)

                Aktif

            @else

                Nonaktif

            @endif

        </div>

    </div>

    <div class="mt-8">

        <a href="{{ route('users.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded">

            Kembali

        </a>

    </div>

</div>

@endsection