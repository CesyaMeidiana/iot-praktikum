@extends('layouts.dosen')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Kelas Saya
        </h2>

        <a href="{{ route('dosen.classrooms.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

            + Buat Kelas

        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border border-gray-200">

        <thead>

            <tr class="bg-gray-100">

                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Angkatan</th>
                <th class="p-3 text-left">Kelas</th>
                <th class="p-3 text-left">Kode Akses</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-center">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($classrooms as $classroom)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $classroom->name }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->academic_year }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->class_name }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->passcode }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->status ? 'Aktif' : 'Nonaktif' }}
                    </td>

                    <td class="p-3 text-center">

                        <a href="{{ route('dosen.classrooms.show', $classroom) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">

                            Detail

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">

                        Belum ada kelas.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection