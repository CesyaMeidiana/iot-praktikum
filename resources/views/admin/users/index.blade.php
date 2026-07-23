@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('page-title', 'Manajemen User')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-5">

        <h2 class="text-2xl font-bold">
            Data User
        </h2>

        <form method="GET" class="flex gap-3 my-5">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari Nama / Email / NIM"
        class="border rounded-lg px-4 py-2 w-72">

    <select
        name="role"
        class="border rounded-lg px-4 py-2">

        <option value="">Semua Role</option>

        @foreach($roles as $role)

            <option
                value="{{ $role->name }}"
                {{ request('role') == $role->name ? 'selected' : '' }}>

                {{ $role->name }}

            </option>

        @endforeach
        
<div class="mt-6">

    {{ $users->links() }}

</div>

    </select>

    <select
        name="status"
        class="border rounded-lg px-4 py-2">

        <option value="">Semua Status</option>

        <option
            value="1"
            {{ request('status') === '1' ? 'selected' : '' }}>

            Aktif

        </option>

        <option
            value="0"
            {{ request('status') === '0' ? 'selected' : '' }}>

            Nonaktif

        </option>

    </select>

    <button
        class="bg-blue-600 text-white px-5 rounded-lg">

        Cari

    </button>

    <a
        href="{{ route('users.index') }}"
        class="bg-gray-500 text-white px-5 rounded-lg flex items-center">

        Reset

    </a>

</form>

        <a href="{{ route('users.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">

            + Tambah User

        </a>

    </div>

    <table class="w-full border">

        <thead class="bg-gray-100">

            <tr>

                <th class="border p-3">No</th>

                <th class="border p-3">Nama</th>

                <th class="border p-3">Email</th>

                <th class="border p-3">NIM / NIP</th>

                <th class="border p-3">Role</th>

                <th class="border p-3">Status</th>

                <th class="border p-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($users as $user)

            <tr>

                <td class="border p-3">
                    {{ $loop->iteration }}
                </td>

                <td class="border p-3">
                    {{ $user->name }}
                </td>

                <td class="border p-3">
                    {{ $user->email }}
                </td>

                <td class="border p-3">
                    {{ $user->nim_nip }}
                </td>

                <td class="border p-3">
                    {{ $user->getRoleNames()->first() }}
                </td>

                <td class="border p-3">

                    @if($user->status)
                        Aktif
                    @else
                        Nonaktif
                    @endif

                </td>

                <td class="border p-3 space-x-2">

                    <a href="{{ route('users.show',$user->id) }}">
                        👁
                    </a>

                    <a href="{{ route('users.edit',$user->id) }}">
                        ✏
                    </a>

                    <form
                        action="{{ route('users.destroy',$user) }}"
                        method="POST"
                        style="display:inline;">

                        @csrf

                        @method('DELETE')

                        <button
                            onclick="return confirm('Yakin ingin menghapus user ini?')">

                            🗑

                        </button>

                    </form>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7" class="text-center p-5">

                    Tidak ada data.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection