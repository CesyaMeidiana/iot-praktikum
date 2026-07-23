<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body {
        font-family: DejaVu Sans;
        font-size: 11px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    table-layout: fixed;
}

td, th {
    border: 1px solid black;
    padding: 6px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

    td, th {
        border: 1px solid black;
        padding: 6px;
    }

    .center {
        text-align: center;
    }

    .logo {
        width: 70px;
    }

    h3 {
        margin-bottom: 4px;
    }
</style>
</head>
<body>

    {{-- HEADER --}}
    <table border="0">
        <tr>
            <td width="15%" class="center">
                <img src="{{ public_path('images/pnj.png') }}" width="70">
            </td>
            <td width="70%" class="center">
                <h2>LAPORAN PRAKTIKUM</h2>
                <h4>Sistem Monitoring Smart Home Berbasis ZigBee</h4>
            </td>
            <td width="15%" class="center">
                <img src="{{ public_path('images/bm.png') }}" width="70">
            </td>
        </tr>
    </table>

    <hr>

    {{-- DATA MAHASISWA & SESI --}}
    <table>
        <tr>
            <th width="35%">Nama Mahasiswa</th>
            <td>{{ $session->user->name }}</td>
        </tr>
        <tr>
            <th>NIM</th>
            <td>{{ $session->user->nim_nip }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $session->user->kelas }}</td>
        </tr>
        <tr>
            <th>Praktikum</th>
            <td>{{ $session->praktikum->title ?? '-' }}</td>
        </tr>
        <tr>
            <th>Topologi</th>
            <td>{{ $session->topology }}</td>
        </tr>
        <tr>
            <th>Skema</th>
            <td>{{ $session->scenario }}</td>
        </tr>
        <tr>
            <th>Jarak</th>
            <td>{{ $session->distance }} Meter</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ strtoupper($session->status) }}</td>
        </tr>
        <tr>
            <th>Mulai</th>
            <td>{{ $session->started_at }}</td>
        </tr>
        <tr>
            <th>Selesai</th>
            <td>{{ $session->finished_at }}</td>
        </tr>
    </table>

    <br>

    {{-- DATA MONITORING (gabungan sensor, aktuator, dan QoS) --}}
    <h3>DATA MONITORING</h3>

    <table>
        <tr>
            <th>Packet</th>
            <th>Waktu</th>

            @foreach ($sensorColumns as $col)
                <th>{{ $col }}</th>
            @endforeach

            @foreach ($actuatorColumns as $col)
                <th>{{ $col }}</th>
            @endforeach

            <th>Delay (ms)</th>
            <th>Jitter (ms)</th>
            <th>Throughput</th>
            <th>Loss (%)</th>
        </tr>

        @forelse ($rows as $row)
            <tr>
                <td class="center">{{ $row['packet'] ?? '-' }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($row['timestamp'])->format('H:i:s') }}</td>

                @foreach ($sensorColumns as $col)
                    <td class="center">{{ $row['sensor'][$col] ?? '-' }}</td>
                @endforeach

                @foreach ($actuatorColumns as $col)
                    <td class="center">{{ $row['aktuator'][$col] ?? '-' }}</td>
                @endforeach

                <td class="center">{{ $row['delay'] ?? '-' }}</td>
                <td class="center">{{ $row['jitter'] ?? '-' }}</td>
                <td class="center">{{ $row['throughput'] ?? '-' }}</td>
                <td class="center">{{ $row['loss'] ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ 6 + $sensorColumns->count() + $actuatorColumns->count() }}" class="center">
                    Belum ada data monitoring untuk praktikum ini.
                </td>
            </tr>
        @endforelse
    </table>

</body>
</html>