{{-- Grafik Monitoring --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    @forelse ($chartSeries as $key => $series)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-slate-700">{{ $series['label'] }}</h3>
                <span class="text-sm font-bold" style="color: {{ $series['color'] }}">
                    {{ $series['current'] }} {{ $series['unit'] }}
                </span>
            </div>
            <div class="h-40">
                <canvas id="chart-{{ $key }}"></canvas>
            </div>
        </div>
    @empty
        <div class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Grafik Monitoring</h2>
            <div class="h-64 flex items-center justify-center border-2 border-dashed border-slate-200 rounded-lg text-slate-400">
                Grafik Sensor Akan Ditampilkan Di Sini
            </div>
        </div>
    @endforelse

</div>

@if (!empty($chartSeries))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartSeries);

            Object.keys(chartData).forEach(function (key) {
                const series = chartData[key];
                const ctx = document.getElementById('chart-' + key);
                if (!ctx) return;

                window["chart_"+key]=new Chart(ctx,{
                    type: 'line',
                    data: {
                        labels: series.labels,
                        datasets: [{
                            label: series.label,
                            data: series.data,
                            borderColor: series.color,
                            backgroundColor: series.color + '22',
                            borderWidth: 2,
                            pointRadius: 2,
                            tension: 0.35,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            },
                            y: {
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            });
        });
    </script>

    <script>

document.addEventListener("realtime-update", function (e) {
    const data = e.detail;
    if (!data.devices) return;

    // Kumpulkan value terbaru per sensor
    data.devices.forEach(function (device) {
        device.sensor.forEach(function (sensor) {
            const now = new Date().toLocaleTimeString("id-ID", {
                hour: "2-digit", minute: "2-digit", second: "2-digit"
            });

            // Coba match ke chart berdasarkan nama sensor
            Object.keys(window).forEach(function (k) {
                if (!k.startsWith("chart_")) return;
                const chart = window[k];
                if (!chart) return;

                // Push data baru
                chart.data.labels.push(now);
                chart.data.datasets[0].data.push(Number(sensor.value));

                // Batasi 6 titik
                if (chart.data.labels.length > 6) {
                    chart.data.labels.shift();
                    chart.data.datasets[0].data.shift();
                }

                chart.update("none");
            });
        });
    });
});
@endif