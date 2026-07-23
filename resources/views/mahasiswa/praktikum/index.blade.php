@extends('layouts.mahasiswa')

@section('title', 'Praktikum')

@section('content')

<div class="flex justify-between items-center mb-8">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            Praktikum

        </h2>

        <p class="text-gray-500 mt-2">

            Lakukan praktikum baru atau lihat riwayat praktikum.

        </p>

    </div>

    <a href="{{ route('mahasiswa.praktikum.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

        + Praktikum Baru

    </a>

</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="px-6 py-4 text-left">Praktikum</th>

                <th class="px-6 py-4 text-left">Topologi</th>

                <th class="px-6 py-4 text-left">Skema</th>

                <th class="px-6 py-4 text-left">Jarak</th>

                <th class="px-6 py-4 text-left">Status</th>

                <th class="px-6 py-4 text-left">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($sessions as $session)

            <tr class="border-b">

                <td class="px-6 py-4">

                    Praktikum #{{ $session->id }}

                </td>

                <td class="px-6">

                    {{ $session->topology }}

                </td>

                <td class="px-6">

                    {{ $session->scenario }}

                </td>

                <td class="px-6">

                    {{ $session->distance }} Meter

                </td>

                <td class="px-6">

                    @if($session->status == 'running')

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded">

                            Berjalan

                        </span>

                    @else

                        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded">

                            Selesai

                        </span>

                    @endif

                </td>

                <td class="px-6">

                    <a
                        href="{{ route('mahasiswa.praktikum.show',$session->id) }}"
                        class="text-blue-600 hover:underline">

                        @if($session->status=='running')

<a
class="text-green-600 font-semibold"
href="{{ route('mahasiswa.praktikum.show',$session->id) }}">

Lanjut

</a>

@else

<a
class="text-blue-600"
href="{{ route('mahasiswa.praktikum.show',$session->id) }}">

Detail

</a>

@endif

                    </a>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="6" class="text-center py-10 text-gray-500">

                    Belum ada praktikum.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection