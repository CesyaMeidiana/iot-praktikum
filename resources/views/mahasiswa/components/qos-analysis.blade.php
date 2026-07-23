{{-- Grafik QoS Praktikum (Realtime, sliding window) --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="text-xl font-bold">Grafik QoS Praktikum</h2>

        @if(($praktikums ?? collect())->count())
            <form method="GET" class="flex items-center">
                <select name="praktikum" onchange="this.form.submit()"
                    class="text-sm border border-slate-200 rounded-lg px-3 py-2 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($praktikums as $p)
                        <option value="{{ $p->id }}" {{ (string) ($selectedPraktikum ?? '') === (string) $p->id ? 'selected' : '' }}>
                            Praktikum #{{ $p->id }} - {{ optional($p->created_at)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

    <div class="relative" style="height: 320px;">
        <canvas id="qosAnalysisChart"></canvas>
    </div>
</div>

@once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endonce

<script>
(function () {
    // Berapa banyak titik yang ditampilkan sekaligus di grafik.
    // Kalau data baru masuk dan sudah melebihi ini, titik paling lama akan digeser/dibuang.
    const MAX_POINTS = 15;

    const initialLabels     = @json($qosChartSeries['labels'] ?? []);
    const initialThroughput = @json($qosChartSeries['throughput'] ?? []);
    const initialDelay      = @json($qosChartSeries['delay'] ?? []);
    const initialJitter     = @json($qosChartSeries['jitter'] ?? []);
    const initialLoss       = @json($qosChartSeries['loss'] ?? []);

    // Potong data awal supaya konsisten dengan window realtime (maks MAX_POINTS titik)
    function trim(arr) {
        return arr.length > MAX_POINTS ? arr.slice(arr.length - MAX_POINTS) : arr;
    }

    const labels     = trim(initialLabels).map((l) => 'P' + l);
    const throughput = trim(initialThroughput);
    const delay      = trim(initialDelay);
    const jitter     = trim(initialJitter);
    const loss       = trim(initialLoss);

    const canvas = document.getElementById('qosAnalysisChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const qosAnalysisChart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Throughput (kbps)',
                    data: throughput,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.08)',
                    yAxisID: 'yThroughput',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                },
                {
                    label: 'Delay (ms)',
                    data: delay,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249,115,22,0.08)',
                    yAxisID: 'yOther',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                },
                {
                    label: 'Jitter (ms)',
                    data: jitter,
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168,85,247,0.08)',
                    yAxisID: 'yOther',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                },
                {
                    label: 'Packet Loss (%)',
                    data: loss,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    yAxisID: 'yOther',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 300 },
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, padding: 16 } },
            },
            scales: {
                yThroughput: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Throughput (kbps)' },
                    grid: { drawOnChartArea: false },
                    beginAtZero: true,
                },
                yOther: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Delay / Jitter / Loss' },
                    grid: { color: '#f1f5f9' },
                    beginAtZero: true,
                },
            },
        },
    });

    // Nomor packet terakhir yang sudah tampil, dipakai buat generate label kalau
    // payload realtime tidak menyertakan nomor packet-nya sendiri.
    let packetCounter = 0;
    for (const l of initialLabels) {
        const n = parseInt(l, 10);
        if (!isNaN(n) && n > packetCounter) packetCounter = n;
    }

    // Tambah satu titik baru ke dataset, dan buang titik paling lama
    // kalau panjangnya sudah melebihi MAX_POINTS -> efek "geser".
    function pushPoint(arr, value) {
        arr.push(value);
        if (arr.length > MAX_POINTS) {
            arr.shift();
        }
    }

    // Dengarkan event realtime yang sama dipakai komponen QoS lainnya (lihat qos.blade.php).
    // e.detail.qos diharapkan berbentuk:
    // { throughput, delay, jitter, packet_loss, packet? }
    document.addEventListener('realtime-update', function (e) {
        const q = e.detail && e.detail.qos;
        if (!q) return;

        packetCounter += 1;
        const nextLabel = 'P' + (q.packet ?? packetCounter);

        pushPoint(qosAnalysisChart.data.labels, nextLabel);
        pushPoint(qosAnalysisChart.data.datasets[0].data, Number(q.throughput));
        pushPoint(qosAnalysisChart.data.datasets[1].data, Number(q.delay));
        pushPoint(qosAnalysisChart.data.datasets[2].data, Number(q.jitter));
        pushPoint(qosAnalysisChart.data.datasets[3].data, Number(q.packet_loss));

        // update('none') = tanpa animasi geser ulang seluruh chart tiap kali data masuk
        qosAnalysisChart.update('none');
    });
})();
</script>