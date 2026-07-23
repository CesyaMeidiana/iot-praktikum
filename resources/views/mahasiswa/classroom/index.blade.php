@extends('layouts.mahasiswa')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

@if(!$classroom)

<h2 class="text-2xl font-bold mb-6">

Gabung Kelas

</h2>

@if(session('error'))

<div class="bg-red-100 text-red-700 p-3 rounded mb-4">

{{ session('error') }}

</div>

@endif

<form
action="{{ route('mahasiswa.classroom.join') }}"
method="POST">

@csrf

<input
type="text"
name="passcode"
placeholder="Masukkan Passcode"
class="w-full border rounded-lg p-3 mb-5">

<button
class="bg-blue-600 text-white px-6 py-3 rounded">

Gabung

</button>

</form>

@else

<h2 class="text-2xl font-bold">

{{ $classroom->name }}

</h2>

<div class="mt-6">

<p>

<b>Angkatan :</b>

{{ $classroom->academic_year }}

</p>

<p>

<b>Kelas :</b>

{{ $classroom->class_name }}

</p>

<p>

<b>Dosen :</b>

{{ $classroom->lecturer->name }}

</p>

</div>

@endif

</div>

@endsection