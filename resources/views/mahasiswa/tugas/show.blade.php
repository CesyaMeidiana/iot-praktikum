@extends('layouts.mahasiswa')

@section('title','Detail Tugas')

@section('content')

<div class="bg-white rounded-xl shadow">

<div class="p-6 border-b">

<h2 class="text-3xl font-bold">

{{ $assignment->title }}

</h2>

</div>

<div class="p-8">

<div class="grid grid-cols-2 gap-6">

<div>

<b>Kelas</b>

<p>{{ $assignment->classroom?->name }}</p>

</div>

<div>

<b>Deadline</b>

<p>{{ $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline)->format('d M Y H:i') : '-' }}</p>

</div>

<div class="col-span-2">

<b>Deskripsi</b>

<p>

{{ $assignment->description }}

</p>

</div>

@if($assignment->attachment)

<div>

<a
href="{{ asset('storage/'.$assignment->attachment) }}"
target="_blank"
class="bg-green-600 text-white px-5 py-2 rounded">

Download Lampiran

</a>

</div>

@endif

</div>

<hr class="my-8">

<h3 class="text-2xl font-bold mb-5">

Upload Jawaban

</h3>

<form
action="{{ route('mahasiswa.tugas.submit',$assignment->id) }}"
method="POST"
enctype="multipart/form-data">

@csrf

    <div class="mb-6">

        <label class="font-semibold">

            Upload Jawaban

        </label>

        <input
            type="file"
            name="file"
            class="w-full border rounded-lg p-3 mt-2"
            required>

    </div>

    <div class="flex gap-3">

        <a
        href="{{ route('mahasiswa.tugas.index') }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">

            Kembali

        </a>

        <button
        type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

            Kumpulkan

        </button>

    </div>

</form>

</div>

</div>

@endsection