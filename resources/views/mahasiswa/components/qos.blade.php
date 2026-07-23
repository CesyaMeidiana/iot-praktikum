@php
    // Fallback: kalau controller belum ngirim $qosPerSesi (misal akun baru
    // yang belum punya praktikum sama sekali), anggap kosong aja.
    $qosPerSesi = $qosPerSesi ?? [];
@endphp
{{-- QoS Result per Sesi --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-5">QoS Result per Praktikum</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-3 pr-4 font-medium">Praktikum</th>
                    <th class="py-3 pr-4 font-medium">Jarak</th>
                    <th class="py-3 pr-4 font-medium">Throughput</th>
                    <th class="py-3 pr-4 font-medium">Delay (ms)</th>
                    <th class="py-3 pr-4 font-medium">Jitter (ms)</th>
                    <th class="py-3 font-medium">Packet Loss (%)</th>
                </tr>
            </thead>
            <tbody id="rt-qos-table">
                @forelse ($qosPerSesi as $row)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-3 pr-4 font-medium text-slate-700">Praktikum #{{ $row['id'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['jarak'] }} m</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['throughput'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['delay'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['jitter'] }}</td>
                        <td class="py-3 text-slate-600">{{ $row['packet_loss'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data QoS.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<script>
document.addEventListener("realtime-update", function (e) {
    const q = e.detail.qos;
    if (!q) return;

    // Update hanya baris pertama (Live) kalau ada, atau tambah di atas
    const table = document.getElementById("rt-qos-table");
    if (!table) return;

    const liveRow = document.getElementById("rt-qos-live");
    const html = `
    <tr id="rt-qos-live" class="border-b border-slate-50 bg-blue-50">
        <td class="py-3 pr-4 font-medium text-blue-700">🔴 Live</td>
        <td class="py-3 pr-4 text-slate-600">-</td>
        <td class="py-3 pr-4 text-slate-600">${Number(q.throughput).toFixed(2)}</td>
        <td class="py-3 pr-4 text-slate-600">${Number(q.delay).toFixed(2)}</td>
        <td class="py-3 pr-4 text-slate-600">${Number(q.jitter).toFixed(2)}</td>
        <td class="py-3 text-slate-600">${Number(q.packet_loss).toFixed(2)}</td>
    </tr>`;

    if (liveRow) {
        liveRow.outerHTML = html;
    } else {
        table.insertAdjacentHTML("afterbegin", html);
    }
});
</script>
</div>