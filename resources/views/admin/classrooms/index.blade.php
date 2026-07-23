@extends('layouts.admin')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Manajemen Kelas
        </h2>

        <a href="{{ route('classrooms.create') }}"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg">

            + Tambah Kelas

        </a>

    </div>

    <table class="w-full border">

        <thead>

<tr class="bg-gray-100">

    <th class="p-3 text-center">No</th>
    <th class="p-3 text-center">Nama Kelas</th>
    <th class="p-3 text-center">Dosen</th>
    <th class="p-3 text-center">Passcode</th>
    <th class="p-3 text-center">Mahasiswa</th>
    <th class="p-3 text-center">Kelompok</th>
    <th class="p-3 text-center">Aksi</th>

</tr>

</thead>

        <tbody>

        @forelse($classrooms as $classroom)

        <tr class="border-t">

            <td class="p-3 text-center">
                {{ $loop->iteration }}
            </td>

            <td class="p-3 text-center">
    {{ $classroom->name }}
</td>

<td class="p-3 text-center">
    {{ $classroom->lecturer->name }}
</td>

<td class="p-3 text-center">
    {{ $classroom->passcode }}
</td>

<td class="p-3 text-center">
    {{ $classroom->students->count() }}
</td>

<td class="p-3 text-center">
    {{ $classroom->groups->count() }}
</td>

            <td class="p-3 text-center">

    <div class="flex justify-center gap-2">

        <a
            href="{{ route('classrooms.show',$classroom) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm">

            Show

        </a>

        <a
            href="{{ route('classrooms.edit',$classroom) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm">

            Edit

        </a>

        <form
            action="{{ route('classrooms.destroy',$classroom) }}"
            method="POST">

            @csrf
            @method('DELETE')

            <button
                onclick="return confirm('Yakin ingin menghapus kelas ini?')"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm">

                Delete

            </button>

        </form>

    </div>

</td>

        </tr>

        @empty

        <tr class="border-t">

            <td colspan="7" class="text-center py-6">

                Belum ada kelas.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection