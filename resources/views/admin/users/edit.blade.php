@extends('layouts.admin')

@section('title','Edit User')

@section('page-title','Edit User')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

<h2 class="text-2xl font-bold mb-6">

Edit User

</h2>

<form method="POST" action="{{ route('users.update',$user) }}">

@csrf

@method('PUT')

<div class="mb-4">

<label>Nama</label>

<input

type="text"

name="name"

value="{{ $user->name }}"

class="w-full border rounded-lg p-2">

</div>

<div class="mb-4">

<label>Email</label>

<input

type="email"

name="email"

value="{{ $user->email }}"

class="w-full border rounded-lg p-2">

</div>

<div class="mb-4">

<label>NIM / NIP</label>

<input

type="text"

name="nim_nip"

value="{{ $user->nim_nip }}"

class="w-full border rounded-lg p-2">

</div>

<div class="mb-4">

<label>Angkatan</label>

<select

name="angkatan"

class="w-full border rounded-lg p-2">

@for($i=2023;$i<=2028;$i++)

<option

value="{{ $i }}"

{{ $user->angkatan==$i ? 'selected':'' }}>

{{ $i }}

</option>

@endfor

</select>

</div>

<div class="mb-4">

<label>Kelas</label>

<select

name="kelas"

class="w-full border rounded-lg p-2">

<option

{{ $user->kelas=='BM A'?'selected':'' }}>

BM A

</option>

<option

{{ $user->kelas=='BM B'?'selected':'' }}>

BM B

</option>

</select>

</div>

<div class="mb-4">

<label>Role</label>

<select

name="role"

class="w-full border rounded-lg p-2">

@foreach($roles as $role)

<option

value="{{ $role->name }}"

{{ $user->hasRole($role->name)?'selected':'' }}>

{{ $role->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-4">

<label>Status</label>

<select

name="status"

class="w-full border rounded-lg p-2">

<option

value="1"

{{ $user->status?'selected':'' }}>

Aktif

</option>

<option

value="0"

{{ !$user->status?'selected':'' }}>

Nonaktif

</option>

</select>

</div>

<button

class="bg-blue-600 text-white px-5 py-2 rounded">

Update

</button>

<a

href="{{ route('users.index') }}"

class="bg-gray-500 text-white px-5 py-2 rounded">

Kembali

</a>

</form>

</div>

@endsection

