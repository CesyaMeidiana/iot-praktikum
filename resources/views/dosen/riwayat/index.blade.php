@extends('layouts.dosen')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
        Riwayat Praktikum
    </h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-100">

            <tr>

                <th class="px-4 py-3">Tanggal</th>

                <th class="px-4 py-3">Mahasiswa</th>

                <th class="px-4 py-3">Node</th>

                <th class="px-4 py-3">Sensor</th>

                <th class="px-4 py-3">Aktuator</th>

                <th class="px-4 py-3">Delay</th>

                <th class="px-4 py-3">Jitter</th>

                <th class="px-4 py-3">Throughput</th>

                <th class="px-4 py-3">Packet Loss</th>

                <th class="px-4 py-3">Aksi</th>

            </tr>

            </thead>

            <tbody>

            @forelse($sessions as $session)

                <tr class="border-t">

                    <td class="px-4 py-3">
                        {{ $session->created_at }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $session->user->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3">

                        @foreach($session->devices as $device)

                            {{ $device->node }}<br>

                        @endforeach

                    </td>

                    <td class="px-4 py-3">

                        @foreach($session->sensorLogs as $sensor)

                            {{ $sensor->sensor->nama_sensor ?? '-' }}
                            :
                            {{ $sensor->value }}
                            <br>

                        @endforeach

                    </td>

                    <td class="px-4 py-3">

                        @foreach($session->actuatorLogs as $actuator)

                            {{ $actuator->actuator->nama_aktuator ?? '-' }}
                            :
                            {{ $actuator->status }}
                            <br>

                        @endforeach

                    </td>

                    <td class="px-4 py-3">
                        {{ optional($session->qosLogs->first())->delay }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($session->qosLogs->first())->jitter }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($session->qosLogs->first())->throughput }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($session->qosLogs->first())->packet_loss }}
                    </td>

                    <td class="px-4 py-3">

                        <a href="{{ route('admin.riwayat.show',$session) }}"
                           class="text-blue-600 hover:underline">

                            Detail

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="10" class="text-center py-6">

                        Belum ada data.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $sessions->links() }}

    </div>

</div>

@endsection