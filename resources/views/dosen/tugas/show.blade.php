@extends('layouts.dosen')

@section('title','Detail Tugas')

@section('content')

<div class="bg-white rounded-xl shadow">

    <div class="border-b p-6">

        <h2 class="text-3xl font-bold">

            Detail Tugas

        </h2>

    </div>

    <div class="p-6">

        <div class="grid grid-cols-2 gap-6">

            <div>

                <label class="text-gray-500">Judul</label>

                <h3 class="font-bold text-xl">
                    {{ $assignment->title }}
                </h3>

            </div>

            <div>

                <label class="text-gray-500">Kelas</label>

                <h3 class="font-bold">

                    {{ $assignment->classroom->name }}

                </h3>

            </div>

            <div>

                <label class="text-gray-500">Target</label>

                <h3>

                    {{ ucfirst($assignment->target) }}

                </h3>

            </div>

            <div>

                <label class="text-gray-500">Deadline</label>

                <h3>

                    {{ $assignment->deadline->format('d M Y H:i') }}

                </h3>

            </div>

        </div>

        <hr class="my-8">

        <h3 class="text-2xl font-bold mb-5">

            Pengumpulan Tugas

        </h3>

        <table class="w-full">

            <thead class="bg-gray-100">

            <tr>

                <th class="p-3 text-left">Nama</th>

                <th>Status</th>

                <th>Waktu</th>

                <th>File</th>

                <th>Nilai</th>

                <th>Aksi</th>

            </tr>

            </thead>

            <tbody>

            @forelse($assignment->submissions as $submission)

                <tr class="border-b">

                    <td class="p-3">

                        @if($assignment->target=="group")

                            {{ $submission->group->nama_kelompok }}

                        @else

                            {{ $submission->student->name }}

                        @endif

                    </td>

                    <td>

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded">

                            Sudah

                        </span>

                    </td>

                    <td>

                        {{ $submission->submitted_at }}

                    </td>

                    <td>

                        <a
                        href="{{ asset('storage/'.$submission->file) }}"
                        class="text-blue-600">

                        Download

                        </a>

                    </td>

                    <td>

                        {{ $submission->score ?? '-' }}

                    </td>

                    <td>

                        <a href="#">

                            Nilai

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">

                        Belum ada mahasiswa yang mengumpulkan.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="mt-8">

            <a
            href="{{ route('dosen.tugas.index') }}"
            class="bg-gray-700 text-white px-5 py-3 rounded">

                Kembali

            </a>

        </div>

    </div>

</div>

@endsection