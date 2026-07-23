@extends('layouts.kajur')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
        Detail Riwayat Praktikum
    </h1>

    <div class="bg-white rounded-lg shadow p-6">

        <div class="grid grid-cols-2 gap-4">

            <div>
                <b>Mahasiswa</b><br>
                {{ $riwayat->user->name }}
            </div>

            <div>
                <b>Tanggal</b><br>
                {{ $riwayat->created_at }}
            </div>

            <div>
                <b>Topologi</b><br>
                {{ $riwayat->topology }}
            </div>

            <div>
                <b>Jarak</b><br>
                {{ $riwayat->distance }}
            </div>

        </div>

    </div>


    <div class="bg-white rounded-lg shadow mt-6 p-6">

        <h3 class="font-bold mb-4">
            Device
        </h3>

        <table class="w-full">

            <thead>

                <tr>

                    <th>Node</th>

                    <th>Device</th>

                </tr>

            </thead>

            <tbody>

            @foreach($riwayat->devices as $device)

                <tr>

                    <td>{{ $device->node }}</td>

                    <td>{{ $device->nama_device }}</td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>


    <div class="bg-white rounded-lg shadow mt-6 p-6">

        <h3 class="font-bold mb-4">
            Sensor
        </h3>

        <table class="w-full">

            <thead>

                <tr>

                    <th>Sensor</th>

                    <th>Value</th>

                </tr>

            </thead>

            <tbody>

            @foreach($riwayat->sensorLogs as $sensor)

                <tr>

                    <td>{{ $sensor->sensor->nama_sensor }}</td>

                    <td>{{ $sensor->value }}</td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>


    <div class="bg-white rounded-lg shadow mt-6 p-6">

        <h3 class="font-bold mb-4">
            Aktuator
        </h3>

        <table class="w-full">

            <thead>

                <tr>

                    <th>Aktuator</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

            @foreach($riwayat->actuatorLogs as $actuator)

                <tr>

                    <td>{{ $actuator->actuator->nama_aktuator }}</td>

                    <td>{{ $actuator->status }}</td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>


    <div class="bg-white rounded-lg shadow mt-6 p-6">

        <h3 class="font-bold mb-4">
            QoS
        </h3>

        <table class="w-full">

            <thead>

                <tr>

                    <th>Packet</th>

                    <th>Delay</th>

                    <th>Jitter</th>

                    <th>Throughput</th>

                    <th>Packet Loss</th>

                </tr>

            </thead>

            <tbody>

            @foreach($riwayat->qosLogs as $qos)

                <tr>

                    <td>{{ $qos->packet }}</td>

                    <td>{{ $qos->delay }}</td>

                    <td>{{ $qos->jitter }}</td>

                    <td>{{ $qos->throughput }}</td>

                    <td>{{ $qos->packet_loss }}</td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection