@extends('layouts.admin')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

<h2 class="text-2xl font-bold mb-6">

Edit Kelas

</h2>

<form
action="{{ route('classrooms.update',$classroom) }}"
method="POST">

@csrf
@method('PUT')

<div class="mb-5">

<label class="font-semibold">

Nama Kelas

</label>

<input
type="text"
name="name"
value="{{ $classroom->name }}"
class="w-full border rounded-lg p-3">

</div>

<div class="mb-5">

<label class="font-semibold">

Angkatan

</label>

<select
name="academic_year"
class="w-full border rounded-lg p-3">

@for($i=2023;$i<=2028;$i++)

<option
value="{{ $i }}"
@if($classroom->academic_year==$i) selected @endif>

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

<option
value="BMA"
@if($classroom->class_name=='BM4A') selected @endif>

BM4A

</option>

<option
value="BM4B"
@if($classroom->class_name=='BMB') selected @endif>

BM4B

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

<option
value="{{ $lecturer->id }}"
@if($classroom->lecturer_id==$lecturer->id) selected @endif>

{{ $lecturer->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-5">

<label class="font-semibold">

Status

</label>

<select
name="status"
class="w-full border rounded-lg p-3">

<option value="1"
@if($classroom->status) selected @endif>

Aktif

</option>

<option value="0"
@if(!$classroom->status) selected @endif>

Nonaktif

</option>

</select>

</div>

<button
class="bg-blue-600 text-white px-6 py-3 rounded-lg">

Update

</button>

</form>

</div>

@endsection