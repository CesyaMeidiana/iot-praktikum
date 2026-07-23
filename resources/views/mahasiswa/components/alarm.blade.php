{{-- Riwayat Alarm --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-slate-800">Riwayat Alarm Terbaru</h2>

        <a href="#" class="text-sm px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-3 pr-4 font-medium">Waktu</th>
                    <th class="py-3 pr-4 font-medium">Node</th>
                    <th class="py-3 pr-4 font-medium">Parameter</th>
                    <th class="py-3 pr-4 font-medium">Nilai</th>
                    <th class="py-3 pr-4 font-medium">Status</th>
                    <th class="py-3 font-medium">Keterangan</th>
                </tr>
            </thead>
            <tbody id="rt-alarm-table">
                @forelse ($riwayatAlarm as $alarm)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-3 pr-4 text-slate-500">{{ $alarm['waktu'] }}</td>
                        <td class="py-3 pr-4 text-slate-700">{{ $alarm['node'] }}</td>
                        <td class="py-3 pr-4 text-slate-700">{{ $alarm['parameter'] }}</td>
                        <td class="py-3 pr-4 text-slate-700">{{ $alarm['nilai'] }}</td>
                        <td class="py-3 pr-4">
                            @php
                                $statusClass = match ($alarm['status']) {
                                    'CRITICAL' => 'bg-red-100 text-red-600',
                                    'WARNING'  => 'bg-amber-100 text-amber-600',
                                    default    => 'bg-green-100 text-green-600',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                {{ $alarm['status'] }}
                            </span>
                        </td>
                        <td class="py-3 text-slate-500">{{ $alarm['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Belum ada data alarm untuk praktikum ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
<script>
document.addEventListener("realtime-update", function (e) {
    const data = e.detail;
    if (!data.devices) return;

    let html = '';
    let count = 0;

    data.devices.forEach(function (device) {
        device.sensor.forEach(function (sensor) {
            if (count >= 10) return;

            let status = 'NORMAL';
            let warna = 'bg-green-100 text-green-600';
            let ket = 'Kondisi Normal';
            const val = Number(sensor.value);

            if (sensor.nama.toLowerCase().includes('gas')) {
                if (val > 600) { status = 'CRITICAL'; warna = 'bg-red-100 text-red-600'; ket = 'Gas Berbahaya'; }
                else if (val > 500) { status = 'WARNING'; warna = 'bg-amber-100 text-amber-600'; ket = 'Gas Mendekati Ambang'; }
            } else if (sensor.nama.toLowerCase().includes('temp') || sensor.nama.toLowerCase().includes('suhu')) {
                if (val > 32) { status = 'CRITICAL'; warna = 'bg-red-100 text-red-600'; ket = 'Suhu Terlalu Tinggi'; }
                else if (val > 30) { status = 'WARNING'; warna = 'bg-amber-100 text-amber-600'; ket = 'Suhu Tinggi'; }
            }

            html += `
            <tr class="border-b border-slate-50 last:border-0">
                <td class="py-3 pr-4 text-slate-500">${sensor.updated_at ?? '-'}</td>
                <td class="py-3 pr-4 text-slate-700">${device.nama_device}</td>
                <td class="py-3 pr-4 text-slate-700">${sensor.nama}</td>
                <td class="py-3 pr-4 text-slate-700">${sensor.value} ${sensor.satuan ?? ''}</td>
                <td class="py-3 pr-4">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold ${warna}">${status}</span>
                </td>
                <td class="py-3 text-slate-500">${ket}</td>
            </tr>`;
            count++;
        });
    });

    if (html) document.getElementById("rt-alarm-table").innerHTML = html;
});
</script>