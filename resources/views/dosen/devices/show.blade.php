@extends('layouts.dosen')

@section('title','Detail Master Device')

@section('page-title','Detail Master Device')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Detail Master Device
        </h2>

        <a href="{{ route('dosen.devices.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>

    </div>

    {{-- INFORMASI DEVICE --}}

    <table class="w-full mb-8">

        <tr>
            <td class="font-semibold py-2 w-56">
                Node
            </td>
            <td>
                : Node {{ $device->node }}
            </td>
        </tr>

        <tr>
            <td class="font-semibold py-2">
                Nama Device
            </td>
            <td>
                : {{ $device->nama_device }}
            </td>
        </tr>

        <tr>
            <td class="font-semibold py-2">
                Keterangan
            </td>
            <td>
                : {{ $device->keterangan ?? '-' }}
            </td>
        </tr>

    </table>

    <hr class="my-6">

    <h3 class="text-2xl font-bold mb-6">
        Daftar Sensor
    </h3>

    @forelse($device->sensors as $sensor)

    <div class="border rounded-lg p-6 mb-6 bg-gray-50">

        <h4 class="text-lg font-bold mb-5">
            Sensor {{ $loop->iteration }}
        </h4>

        <table class="w-full mb-6">

            <tr>
                <td class="font-semibold py-2 w-56">
                    Nama Sensor
                </td>
                <td>
                    : {{ $sensor->nama_sensor }}
                </td>
            </tr>

            <tr>
                <td class="font-semibold py-2">
                    Mengukur
                </td>
                <td>
                    : {{ $sensor->parameter }}
                </td>
            </tr>

            <tr>
                <td class="font-semibold py-2">
                    Satuan
                </td>
                <td>
                    : {{ $sensor->satuan }}
                </td>
            </tr>

            <tr>
                <td class="font-semibold py-2">
                    Keterangan
                </td>
                <td>
                    : {{ $sensor->keterangan ?? '-' }}
                </td>
            </tr>

        </table>

        <h5 class="font-bold text-lg mb-3">
            Daftar Aktuator
        </h5>

        @if($sensor->actuators->count())

        <table class="w-full border">

            <thead class="bg-gray-100">

                <tr>

                    <th class="border p-3">
                        No
                    </th>

                    <th class="border p-3">
                        Nama Aktuator
                    </th>

                    <th class="border p-3">
                        Kondisi ON
                    </th>

                    <th class="border p-3">
                        Kondisi OFF
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($sensor->actuators as $aktuator)

                <tr>

                    <td class="border p-3 text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border p-3">
                        {{ $aktuator->nama_aktuator }}
                    </td>

                    <td class="border p-3">
                        {{ $aktuator->kondisi_on ?? '-' }}
                    </td>

                    <td class="border p-3">
                        {{ $aktuator->kondisi_off ?? '-' }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        @else

        <div class="border rounded-lg p-4 bg-white text-gray-500">

            Sensor ini belum memiliki aktuator.

        </div>

        @endif

    </div>

    @empty

    <div class="border rounded-lg p-6 text-center text-gray-500">

        Belum ada sensor.

    </div>

    @endforelse

</div>

@endsection