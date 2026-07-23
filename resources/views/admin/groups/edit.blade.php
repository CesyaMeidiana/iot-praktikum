@extends('layouts.admin')

@section('title','Edit Kelompok')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-bold mb-6">
        Edit Kelompok
    </h2>

    <form action="{{ route('groups.update', $group->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Nama Kelompok</label>

            <input
                type="text"
                name="nama_kelompok"
                value="{{ $group->nama_kelompok }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-4">

            <label>Kelas</label>

            <select
                name="kelas"
                class="w-full border rounded p-2">

                <option value="BM A" {{ $group->kelas == 'BM A' ? 'selected' : '' }}>
                    BM A
                </option>

                <option value="BM B" {{ $group->kelas == 'BM B' ? 'selected' : '' }}>
                    BM B
                </option>

            </select>

        </div>

        <div class="mb-4">

            <label>Dosen</label>

            <select
                name="dosen_id"
                class="w-full border rounded p-2">

                @foreach($dosens as $dosen)

                    <option
                        value="{{ $dosen->id }}"
                        {{ $group->dosen_id == $dosen->id ? 'selected' : '' }}>

                        {{ $dosen->name }}

                    </option>

                @endforeach

            </select>

        </div>

        <button
            class="bg-yellow-500 text-white px-5 py-2 rounded">

            Update

        </button>

    </form>

</div>

@endsection