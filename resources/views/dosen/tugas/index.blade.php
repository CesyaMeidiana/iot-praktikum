@extends('layouts.dosen')

@section('title','Tugas')

@section('content')

<div class="bg-white rounded-xl shadow">

    <div class="flex justify-between items-center p-6 border-b">

        <h2 class="text-3xl font-bold">

            Manajemen Tugas

        </h2>

        <a href="{{ route('dosen.tugas.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

            + Buat Tugas

        </a>

    </div>

    <table class="w-full">

        <thead class="bg-gray-100">

        <tr>

            <th class="py-4 text-center">No</th>

            <th class="text-center">Judul</th>

            <th class="text-center">Kelas</th>

            <th class="text-center">Target</th>

            <th class="text-center">Deadline</th>

            <th class="text-center">Aksi</th>

        </tr>

        </thead>

        <tbody>

        @forelse($assignments as $assignment)

        <tr class="border-t hover:bg-gray-50">

            <td class="py-4 text-center">

                {{ $loop->iteration }}

            </td>

            <td class="text-center">

                {{ $assignment->title }}

            </td>

            <td class="text-center">

                {{ $assignment->classroom->name }}

            </td>

            <td class="text-center">

                {{ ucfirst($assignment->target) }}

            </td>

            <td class="text-center">

                {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y H:i') }}

            </td>

           <td class="text-center">

    <a href="{{ route('dosen.tugas.show',['tuga'=>$assignment->id]) }}"
        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

        Show

    </a>

    <a href="{{ route('dosen.tugas.edit',['tuga'=>$assignment->id]) }}"
        class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded mx-2">

        Edit

    </a>

    <form
        action="{{ route('dosen.tugas.destroy',['tuga'=>$assignment->id]) }}"
        method="POST"
        class="inline">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Yakin ingin menghapus tugas ini?')"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">

            Delete

        </button>

    </form>

</td>

        </tr>

        @empty

        <tr>

            <td colspan="6"
                class="py-8 text-center text-gray-500">

                Belum ada tugas.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection