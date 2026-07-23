@extends('layouts.mahasiswa')

@section('title','Tugas')

@section('content')

<div class="bg-white rounded-xl shadow">

    <div class="flex justify-between items-center p-6 border-b">

        <h2 class="text-3xl font-bold">
            Tugas Praktikum
        </h2>

    </div>

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>
                <th class="py-4 text-center">No</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Kelas</th>
                <th class="text-center">Deadline</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>

        </thead>

        <tbody>

        @forelse($assignments as $assignment)

        @php
            $submission = $assignment->submissions
                ->where('student_id', auth()->id())
                ->first();
        @endphp

        <tr class="border-t hover:bg-gray-50">

            <td class="text-center py-4">
                {{ $loop->iteration }}
            </td>

            <td class="text-center">
                {{ $assignment->title }}
            </td>

            <td class="text-center">
                {{ $assignment->classroom?->name }}
            </td>

            <td class="text-center">
                {{ optional($assignment->deadline)->format('d M Y H:i') }}
            </td>

            <td class="text-center">

                @if($submission)

                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded">
                        Sudah Mengumpulkan
                    </span>

                @else

                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded">
                        Belum Mengumpulkan
                    </span>

                @endif

            </td>

            <td class="text-center">

                <a href="{{ route('mahasiswa.tugas.show',$assignment) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                    Detail

                </a>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="6" class="text-center py-8 text-gray-500">

                Tidak ada tugas.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection