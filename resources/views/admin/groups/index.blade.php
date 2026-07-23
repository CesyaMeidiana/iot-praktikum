@extends('layouts.admin')

@section('title', 'Manajemen Kelompok')

@section('page-title', 'Manajemen Kelompok')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-5">

        <h2 class="text-2xl font-bold">
            Data Kelompok
        </h2>

        <a href="{{ route('groups.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">

            + Tambah Kelompok

        </a>

    </div>

    <table class="w-full border">

        <thead class="bg-gray-100">

            <tr>

                <th class="border p-3">No</th>

                <th class="border p-3">Nama Kelompok</th>

                <th class="border p-3">Angkatan</th>

                <th class="border p-3">Kelas</th>

                <th class="border p-3">Dosen</th>

                <th class="border p-3">Kode Kelompok</th>

                <th class="border p-3">Anggota</th>

                <th class="border p-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($groups as $group)

            <tr>

                <td class="border p-3">
                    {{ $groups->firstItem() + $loop->index }}
                </td>

                <td class="border p-3">
                    {{ $group->nama_kelompok }}
                </td>

                <td class="border p-3">
                    {{ $group->angkatan }}
                </td>

                <td class="border p-3">
                    {{ $group->kelas }}
                </td>

                <td class="border p-3">
                    {{ $group->dosen->name ?? '-' }}
                </td>

                <td class="border p-3">

    <div class="flex items-center justify-between">

        <span class="font-mono">

            {{ $group->join_code }}

        </span>

        <button
            onclick="navigator.clipboard.writeText('{{ $group->join_code }}')"
            class="text-blue-600">

            📋

        </button>

    </div>

</td>

                <td class="border p-3">
                    {{ $group->members->count() }} Mahasiswa
                </td>

                <td class="border p-3 space-x-2">

                    <a href="{{ route('groups.show',$group->id) }}">
                        👁
                    </a>

                    <a href="{{ route('groups.edit',$group->id) }}">
                        ✏
                    </a>

                    <form
                        action="{{ route('groups.destroy',$group->id) }}"
                        method="POST"
                        style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Yakin ingin menghapus kelompok ini?')">

                            🗑

                        </button>

                    </form>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7" class="text-center p-5">

                    Belum ada data kelompok.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

    <div class="mt-6">

        {{ $groups->links() }}

    </div>

</div>

@endsection