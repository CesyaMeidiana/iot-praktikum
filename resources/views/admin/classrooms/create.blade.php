@extends('layouts.admin')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

<h2 class="text-2xl font-bold mb-6">

Tambah Kelas

</h2>

<form action="{{ route('classrooms.store') }}" method="POST">

@csrf

<div class="mb-5">

<label class="font-semibold">

Nama Kelas

</label>

<input
type="text"
name="name"
class="w-full border rounded-lg p-3"
required>

</div>

<div class="mb-5">

<label class="font-semibold">

Angkatan

</label>

<select
name="academic_year"
class="w-full border rounded-lg p-3">

@for($i=2023;$i<=2028;$i++)

<option value="{{ $i }}">

{{ $i }}

</option>

@endfor

</select>

</div>

<div class="mb-5">

<label class="font-semibold">

Kelas

</label>

<select
name="class_name"
class="w-full border rounded-lg p-3">

<option value="BMA">

BMA

</option>

<option value="BMB">

BMB

</option>

</select>

</div>

<div class="mb-5">

<label class="font-semibold">

Dosen

</label>

<select
name="lecturer_id"
class="w-full border rounded-lg p-3">

@foreach($lecturers as $lecturer)

<option value="{{ $lecturer->id }}">

{{ $lecturer->name }}

</option>

@endforeach

</select>

</div>

<div class="flex justify-end">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Simpan

</button>

</div>

</form>

</div>

@endsection