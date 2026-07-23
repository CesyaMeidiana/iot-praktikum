@extends('layouts.mahasiswa')

@section('title','Monitoring Praktikum')

@section('content')

<h2 class="text-3xl font-bold mb-8">Monitoring Praktikum</h2>

{{-- Kartu Ringkasan --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-slate-800">Status Praktikum</h3>
        @if($session->status=='running')
            <span class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">Sedang Berjalan</span>
        @else
            <span class="bg-slate-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">Praktikum Selesai</span>
        @endif
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        <div>
            <p class="text-xs text-slate-500 uppercase">Topologi</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $session->topology }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Skema</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $session->scenario }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Jarak</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $session->distance }} meter</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Status Device</p>
            <p id="rt-device-status" class="font-semibold mt-1 {{ $isDeviceOnline ? 'text-green-600' : 'text-red-500' }}">
                {{ $isDeviceOnline ? 'Online' : 'Offline' }}
            </p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Device</p>
            <p class="font-semibold text-slate-800 mt-1">
                @foreach ($session->devices as $device)
                    {{ $device->nama_device }} (Node {{ $device->node }})@if (!$loop->last), @endif
                @endforeach
            </p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Sensor Diukur</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $sensorColumns->implode(', ') ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Jumlah Data</p>
            <p id="rt-jumlah-data" class="font-semibold text-slate-800 mt-1">{{ count($rows) }} data</p>
        </div>
    </div>

</div>

{{-- Tabel Data Monitoring --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="flex items-center justify-between px-6 pt-6 pb-4">
        <h3 class="text-xl font-bold text-slate-800">Data Monitoring</h3>
        <span id="rt-total-row" class="text-xs text-slate-400">
            Menampilkan {{ count($rows) }} data
        </span>
    </div>

    <div class="overflow-auto" style="max-height: 60vh;">
        <table class="border-collapse text-sm w-full">
            <thead class="sticky top-0 z-10">
                <tr>
                    {{-- Packet ID di PALING DEPAN --}}
                    <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">
                        Packet
                    </th>

                    <th class="bg-purple-50 text-purple-700 px-4 py-3 border border-purple-100 whitespace-nowrap font-semibold">
                        ED
                    </th>

                    <th class="sticky left-0 z-20 bg-slate-100 text-slate-600 px-4 py-3 border border-slate-200 text-left whitespace-nowrap font-semibold">
                        Waktu
                    </th>

                    @foreach ($sensorColumns as $col)
                        <th class="bg-blue-50 text-blue-700 px-4 py-3 border border-blue-100 whitespace-nowrap font-semibold">
                            {{ $col }}
                        </th>
                    @endforeach

                    @foreach ($actuatorColumns as $col)
                        <th class="bg-emerald-50 text-emerald-700 px-4 py-3 border border-emerald-100 whitespace-nowrap font-semibold">
                            {{ $col }}
                        </th>
                    @endforeach

                    <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Delay (ms)</th>
                    <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Jitter (ms)</th>
                    <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Throughput</th>
                    <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Loss (%)</th>
                    <th class="bg-red-50 text-red-700 px-4 py-3 border border-red-100 whitespace-nowrap font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody id="rt-praktikum-table">
               @forelse ($rows as $row)
    <tr data-packet="{{ $row['packet'] ?? '' }}" class="odd:bg-white even:bg-slate-50 hover:bg-blue-50">

                        {{-- Packet ID --}}
                        <td class="px-4 py-2 border border-slate-100 text-center font-bold text-amber-700">
                            {{ $row['packet'] ?? '-' }}
                        </td>

                        <td class="px-4 py-2 border border-slate-100 text-center font-semibold text-purple-700">
                            {{ $row['device'] ?? '-' }}
                        </td>

                        <td class="sticky left-0 bg-inherit px-4 py-2 border border-slate-100 whitespace-nowrap font-medium text-slate-700">
                            {{ \Carbon\Carbon::parse($row['timestamp'])->format('H:i:s') }}
                        </td>

                        @foreach ($sensorColumns as $col)
                            <td class="px-4 py-2 border border-slate-100 text-center">
                                {{ $row['sensor'][$col] ?? '-' }}
                            </td>
                        @endforeach

                        @foreach ($actuatorColumns as $col)
                            <td class="px-4 py-2 border border-slate-100 text-center">
                                @if (isset($row['aktuator'][$col]))
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold
                                        {{ $row['aktuator'][$col] === 'ON' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $row['aktuator'][$col] }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach

                        <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['delay'] ?? '-' }}</td>
                        <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['jitter'] ?? '-' }}</td>
                        <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['throughput'] ?? '-' }}</td>
                        <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['loss'] ?? '-' }}</td>

                        <td class="px-4 py-2 border border-slate-100 text-center">
                            <form action="{{ route('mahasiswa.praktikum.destroyRow', $session->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="timestamp"
                                       value="{{ \Carbon\Carbon::parse($row['timestamp'])->format('Y-m-d H:i:s') }}">
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg px-2 py-1">
                                    🗑️
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="text-center py-10 text-slate-400">
                            Belum ada data masuk untuk praktikum ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="flex gap-3 mt-8">
    <a href="{{ route('mahasiswa.praktikum.index') }}"
       class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-lg">
        Kembali
    </a>

    @if($session->status=='running')
        <form action="{{ route('mahasiswa.praktikum.finish',$session->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">
                Selesaikan Praktikum
            </button>
        </form>
    @else
        <a href="{{ route('mahasiswa.praktikum.download',$session->id) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
            Download CSV
        </a>
        <a href="{{ route('mahasiswa.praktikum.pdf',$session->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
            Download PDF
        </a>
    @endif
</div>

@if($session->status == 'running')
<script>

const rtSensorColumns   = @json($sensorColumns);
const rtActuatorColumns = @json($actuatorColumns);
const rtSessionId       = {{ $session->id }};

// ===== DEVICE ONLINE/OFFLINE — tracking waktu terakhir data masuk =====
let lastDataTime = null;
let onlineCheckInterval = null;

function updateOnlineStatus(isOnline) {
    const el = document.getElementById("rt-device-status");
    if (!el) return;
    el.innerHTML   = isOnline ? "Online" : "Offline";
    el.className   = "font-semibold mt-1 " + (isOnline ? "text-green-600" : "text-red-500");
}

// Cek setiap detik: kalau sudah 15 detik tidak ada data baru → Offline
onlineCheckInterval = setInterval(function () {
    if (!lastDataTime) return;
    const selisih = (Date.now() - lastDataTime) / 1000;
    updateOnlineStatus(selisih <= 15);
}, 1000);


<script>

const rtSensorColumns   = @json($sensorColumns);
const rtActuatorColumns = @json($actuatorColumns);
const rtSessionId       = {{ $session->id }};

let lastPacket = Number(
    document.querySelector("#rt-praktikum-table tr:last-child")?.dataset.packet || 0
);

let lastDataTime = Date.now();

function updateOnlineStatus(status){
    const el = document.getElementById("rt-device-status");
    if(!el) return;

    el.innerHTML = status ? "Online" : "Offline";
    el.className = "font-semibold mt-1 " + (status
        ? "text-green-600"
        : "text-red-500");
}

setInterval(function(){

    if((Date.now()-lastDataTime) > 15000){
        updateOnlineStatus(false);
    }

},1000);

document.addEventListener("realtime-update",function(e){
    console.log("SHOW BLADE EVENT", e.detail.monitoring_logs.length);

    const data = e.detail;

    if(!data.monitoring_logs) return;

    const tbody = document.getElementById("rt-praktikum-table");

    const rows = data.monitoring_logs
        .filter(r=>r.praktikum_session_id==rtSessionId)
        .sort((a,b)=>a.packet_id-b.packet_id);

    rows.forEach(function(row){

        if(Number(row.packet_id)<=lastPacket){
            return;
        }

        lastPacket = Number(row.packet_id);
        lastDataTime = Date.now();

        updateOnlineStatus(true);

        const readings = row.readings || {};
        const sensor   = readings.sensor || {};
        const actuator = readings.aktuator || {};

        let html = "";

        html += `<tr data-packet="${row.packet_id}" class="odd:bg-white even:bg-slate-50 hover:bg-blue-50">`;

        html += `<td class="px-4 py-2 border text-center font-bold">${row.packet_id}</td>`;

        html += `<td class="px-4 py-2 border">${
            new Date(row.created_at).toLocaleTimeString("id-ID")
        }</td>`;

        rtSensorColumns.forEach(function(col){

            html += `<td class="px-4 py-2 border text-center">${
                sensor[col] ?? "-"
            }</td>`;

        });

        rtActuatorColumns.forEach(function(col){

            let val = actuator[col] ?? "-";

            html += `<td class="px-4 py-2 border text-center">${val}</td>`;

        });

        html += `<td class="px-4 py-2 border text-center">${row.delay??"-"}</td>`;
        html += `<td class="px-4 py-2 border text-center">${row.jitter??"-"}</td>`;
        html += `<td class="px-4 py-2 border text-center">${row.throughput??"-"}</td>`;
        html += `<td class="px-4 py-2 border text-center">${row.packet_loss??"-"}</td>`;

        html += `<td class="px-4 py-2 border text-center">-</td>`;

        html += `</tr>`;

        tbody.insertAdjacentHTML("beforeend",html);

        document.getElementById("rt-jumlah-data").innerHTML =
            tbody.querySelectorAll("tr").length+" data";

        document.getElementById("rt-total-row").innerHTML =
            "Menampilkan "+tbody.querySelectorAll("tr").length+" data";

        tbody.parentElement.scrollTop = tbody.parentElement.scrollHeight;

    });

});

</script>


</script>
@endif

@endsection