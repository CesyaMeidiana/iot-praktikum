{{-- Grafik QoS Praktikum (Realtime, sliding window) --}}

{{-- Filter praktikum (dipakai bareng oleh ketiga chart di bawah) --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="text-xl font-bold">Grafik QoS Praktikum</h2>

        @if(($praktikums ?? collect())->count())
           <form method="GET" class="flex items-center gap-3">

    <select
        name="praktikum"
        onchange="this.form.submit()"
        class="text-sm border border-slate-200 rounded-lg px-3 py-2">

        @foreach($praktikums as $p)
            <option
                value="{{ $p->id }}"
                {{ (string)$selectedPraktikum === (string)$p->id ? 'selected' : '' }}>
                Praktikum #{{ $p->id }}
            </option>
        @endforeach

    </select>

    <select
        name="device"
        onchange="this.form.submit()"
        class="text-sm border border-slate-200 rounded-lg px-3 py-2">

        @foreach($deviceOptions as $device)

            <option
                value="{{ $device->id }}"
                {{ (string)$selectedDevice === (string)$device->id ? 'selected' : '' }}>

                {{ $device->nama_device }}

            </option>

        @endforeach

    </select>

</form>

        @endif
    </div>
</div>

{{-- Box 1: Throughput --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="text-xl font-bold">Throughput</h2>
    </div>

    <div class="relative" style="height: 360px;">
        <canvas id="qosThroughputChart"></canvas>
    </div>
</div>

{{-- Box 2: Delay & Jitter --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="text-xl font-bold">Delay & Jitter</h2>
    </div>

    <div class="relative" style="height: 360px;">
        <canvas id="qosDelayJitterChart"></canvas>
    </div>
</div>

{{-- Box 3: Packet Loss --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <h2 class="text-xl font-bold">Packet Loss</h2>
    </div>

    <div class="relative" style="height: 360px;">
        <canvas id="qosLossChart"></canvas>
    </div>
</div>

@once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endonce

<script>
(function () {
    // Berapa banyak titik yang ditampilkan sekaligus di grafik.
    // Kalau data baru masuk dan sudah melebihi ini, titik paling lama akan digeser/dibuang.
    // Berapa titik yang kelihatan sekaligus di layar
    const WINDOW_SIZE = 15;

    // Seberapa cepat window geser (ms). Makin kecil, makin cepat jalan.
    const SLIDE_INTERVAL_MS = 800;

    const fullLabels     = @json($qosChartSeries['labels'] ?? []);
    const fullThroughput = @json($qosChartSeries['throughput'] ?? []);
    const fullDelay      = @json($qosChartSeries['delay'] ?? []);
    const fullJitter     = @json($qosChartSeries['jitter'] ?? []);
    const fullLoss       = @json($qosChartSeries['loss'] ?? []);

    // Index awal window yang lagi ditampilkan (0 = mulai dari paket pertama)
    let windowStart = 0;

    function windowSlice(arr) {
        return arr.slice(windowStart, windowStart + WINDOW_SIZE);
    }

    const labels     = windowSlice(fullLabels).map((l) => 'P' + l);
    const throughput = windowSlice(fullThroughput);
    const delay      = windowSlice(fullDelay);
    const jitter     = windowSlice(fullJitter);
    const loss       = windowSlice(fullLoss);

    const throughputCanvas   = document.getElementById('qosThroughputChart');
    const delayJitterCanvas  = document.getElementById('qosDelayJitterChart');
    const lossCanvas         = document.getElementById('qosLossChart');

    if (!throughputCanvas || !delayJitterCanvas || !lossCanvas || typeof Chart === 'undefined') return;

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 300 },
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, padding: 16 } },
        },
    };

    // Chart 1: Throughput
    const qosThroughputChart = new Chart(throughputCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Throughput (kbps)',
                    data: throughput,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                },
            ],
        },
        options: {
            ...baseOptions,
            scales: {
                x: {
                    title: { display: true, text: 'Nomor Paket (Packet)' },
                },
                y: {
                    type: 'linear',
                    title: { display: true, text: 'Throughput (kbps)' },
                    grid: { color: '#f1f5f9' },
                    beginAtZero: true,
                },
            },
        },
    });

    // Chart 2: Delay & Jitter
    const qosDelayJitterChart = new Chart(delayJitterCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Delay (ms)',
                    data: delay,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249,115,22,0.08)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                },
                {
                    label: 'Jitter (ms)',
                    data: jitter,
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168,85,247,0.08)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                },
            ],
        },
        options: {
            ...baseOptions,
            scales: {
                x: {
                    title: { display: true, text: 'Nomor Paket (Packet)' },
                },
                y: {
                    type: 'linear',
                    title: { display: true, text: 'Delay / Jitter (ms)' },
                    grid: { color: '#f1f5f9' },
                    beginAtZero: true,
                },
            },
        },
    });

    // Chart 3: Packet Loss
    const qosLossChart = new Chart(lossCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Packet Loss (%)',
                    data: loss,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                },
            ],
        },
        options: {
            ...baseOptions,
            scales: {
                x: {
                    title: { display: true, text: 'Nomor Paket (Packet)' },
                },
                y: {
                    type: 'linear',
                    title: { display: true, text: 'Packet Loss (%)' },
                    grid: { color: '#f1f5f9' },
                    beginAtZero: true,
                },
            },
        },
    });

    // ==========================================================
    // Animasi window geser: tiap SLIDE_INTERVAL_MS, window maju
    // 1 titik (1-10 -> 2-11 -> 3-12 -> ... sampai data terakhir).
    // Ini BUKAN realtime dari MQTT, cuma "memutar ulang" data yang
    // sudah di-fetch sekali saat halaman di-load — jadi aman dan
    // gak melanggar aturan "chart historis gak boleh dengar event
    // realtime-update" di catatan bawah ini.
    // ==========================================================
    if (fullLabels.length > WINDOW_SIZE) {
        const maxStart = fullLabels.length - WINDOW_SIZE;

        const slideTimer = setInterval(() => {
            windowStart += 1;

            if (windowStart > maxStart) {
                clearInterval(slideTimer);
                return;
            }

            const newLabels     = windowSlice(fullLabels).map((l) => 'P' + l);
            const newThroughput = windowSlice(fullThroughput);
            const newDelay      = windowSlice(fullDelay);
            const newJitter     = windowSlice(fullJitter);
            const newLoss       = windowSlice(fullLoss);

            qosThroughputChart.data.labels = newLabels;
            qosThroughputChart.data.datasets[0].data = newThroughput;
            qosThroughputChart.update();

            qosDelayJitterChart.data.labels = newLabels;
            qosDelayJitterChart.data.datasets[0].data = newDelay;
            qosDelayJitterChart.data.datasets[1].data = newJitter;
            qosDelayJitterChart.update();

            qosLossChart.data.labels = newLabels;
            qosLossChart.data.datasets[0].data = newLoss;
            qosLossChart.update();
        }, SLIDE_INTERVAL_MS);
    }
})();
</script>