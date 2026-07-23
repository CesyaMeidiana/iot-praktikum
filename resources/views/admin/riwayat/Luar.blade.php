@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Data Monitoring Di Luar Praktikum</h1>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="grid md:grid-cols-3 gap-6">
        <div>
            <p class="text-xs text-slate-500 uppercase">Jumlah Data</p>
            <p class="font-semibold text-slate-800 mt-1">{{ count($rows) }} data</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Sensor Diukur</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $sensorColumns->implode(', ') ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase">Aktuator</p>
            <p class="font-semibold text-slate-800 mt-1">{{ $actuatorColumns->implode(', ') ?: '-' }}</p>
        </div>
    </div>
</div>

    {{-- Tabel Data ala spreadsheet --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-slate-800">Data Monitoring</h3>
            <span class="text-xs text-slate-400">Menampilkan {{ count($rows) }} data</span>
        </div>

        <div class="overflow-auto" style="max-height: 65vh;">
            <table class="border-collapse text-sm w-full">
                <thead class="sticky top-0 z-10">
                    <tr>
                        <th class="sticky left-0 z-20 bg-slate-100 text-slate-600 px-4 py-3 border border-slate-200 text-left whitespace-nowrap font-semibold">
                            Waktu
                        </th>

                        <th class="bg-slate-100 text-slate-600 px-4 py-3 border border-slate-200 whitespace-nowrap font-semibold">Node</th>

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

                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Packet</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Delay (ms)</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Jitter (ms)</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Throughput</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Loss (%)</th>
                        <th class="bg-slate-100 text-slate-600 px-4 py-3 border border-slate-200 whitespace-nowrap font-semibold">Kondisi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rows as $row)
                        <tr class="odd:bg-white even:bg-slate-50 hover:bg-blue-50">

                            <td class="sticky left-0 bg-inherit px-4 py-2 border border-slate-100 whitespace-nowrap font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($row['timestamp'])->format('d/m/Y H:i:s') }}
                            </td>

                            @foreach ($sensorColumns as $col)
                                <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['sensor'][$col] ?? '-' }}</td>
                            @endforeach

                            @foreach ($actuatorColumns as $col)
                                <td class="px-4 py-2 border border-slate-100 text-center">
                                    @if (isset($row['aktuator'][$col]))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $row['aktuator'][$col] === 'ON' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $row['aktuator'][$col] }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['packet'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['delay'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['jitter'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['throughput'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['loss'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center font-medium"> {{ $row['node'] }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ str_starts_with($row['kondisi'] ?? '', 'WASPADA') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $row['kondisi'] ?? '-' }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center py-10 text-slate-400">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-6">
        <a href="{{ route('admin.riwayat.index') }}" class="text-blue-600 hover:underline font-medium">
            &larr; Kembali ke Riwayat
        </a>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener("realtime-update", function (e) {
    const d = e.detail;
    if (!d.luar || d.luar.length === 0) return;

    const tbody = document.querySelector("table tbody");
    if (!tbody) return;

    // Update jumlah data
    const jumlahEl = document.querySelector(".text-xs.text-slate-400");
    if (jumlahEl) jumlahEl.innerHTML = "Menampilkan " + d.luar.length + " data";

    let html = "";
    d.luar.forEach(function (row) {

        // Sensor columns — ambil semua key dari sensor object
        let sensorCells = "";
        Object.values(row.sensor).forEach(function (val) {
            sensorCells += `<td class="px-4 py-2 border border-slate-100 text-center">${val ?? '-'}</td>`;
        });

        // Actuator columns
        let actuatorCells = "";
        Object.values(row.aktuator).forEach(function (val) {
            const isOn = val === "ON" || val === 1 || val === true;
            actuatorCells += `
            <td class="px-4 py-2 border border-slate-100 text-center">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold ${isOn ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600'}">
                    ${isOn ? 'ON' : 'OFF'}
                </span>
            </td>`;
        });

        const isWaspada = (row.kondisi ?? '').startsWith('WASPADA');

        html += `
        <tr class="odd:bg-white even:bg-slate-50 hover:bg-blue-50">
            <td class="sticky left-0 bg-inherit px-4 py-2 border border-slate-100 whitespace-nowrap font-medium text-slate-700">
                ${row.waktu}
            </td>
            ${sensorCells}
            ${actuatorCells}
            <td class="px-4 py-2 border border-slate-100 text-center">${row.packet ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center">${row.delay ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center">${row.jitter ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center">${row.throughput ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center">${row.loss ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center font-medium">${row.node ?? '-'}</td>
            <td class="px-4 py-2 border border-slate-100 text-center">
                <span class="px-2 py-1 rounded text-xs font-semibold ${isWaspada ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                    ${row.kondisi ?? '-'}
                </span>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
});
</script>
@endpush

@endsection