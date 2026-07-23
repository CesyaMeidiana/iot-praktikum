@extends('layouts.admin')

@section('title', 'Tambah User')

@section('page-title', 'Tambah User')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Tambah User
    </h2>

    <form action="{{ route('users.store') }}" method="POST">

        @csrf

        <div class="grid grid-cols-2 gap-6">

            <div>

                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="name"
                    class="w-full border rounded-lg p-2 mt-2"
                    required>

            </div>

            <div>

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-lg p-2 mt-2"
                    required>

            </div>

            <div>

                <label>NIM / NIP</label>

                <input
                    type="text"
                    name="nim_nip"
                    class="w-full border rounded-lg p-2 mt-2"
                    required>

            </div>

            <div>

                <label>Angkatan</label>

                <select
                    name="angkatan"
                    class="w-full border rounded-lg p-2 mt-2">

                    @for($i = 2023; $i <= 2028; $i++)

                        <option value="{{ $i }}">

                            {{ $i }}

                        </option>

                    @endfor

                </select>

            </div>

            <div>

                <label>Kelas</label>

                <select
                    name="kelas"
                    class="w-full border rounded-lg p-2 mt-2">

                    <option value="BM A">BM A</option>

                    <option value="BM B">BM B</option>

                </select>

            </div>

            <div>

                <label>No HP</label>

                <input
                    type="text"
                    name="phone"
                    class="w-full border rounded-lg p-2 mt-2">

            </div>

            <div>

                <label>Role</label>

                <select
                    name="role"
                    class="w-full border rounded-lg p-2 mt-2">

                    @foreach($roles as $role)

                        <option value="{{ $role->name }}">

                            {{ $role->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label>Status</label>

                <select
                    name="status"
                    class="w-full border rounded-lg p-2 mt-2">

                    <option value="1">
                        Aktif
                    </option>

                    <option value="0">
                        Nonaktif
                    </option>

                </select>

            </div>

        </div>

        <div class="mt-8 flex gap-3">

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg">

                Simpan

            </button>

            <a href="{{ route('users.index') }}"
                class="bg-gray-400 text-white px-5 py-2 rounded-lg">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection