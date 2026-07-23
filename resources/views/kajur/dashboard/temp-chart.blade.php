<div class="bg-white rounded-xl shadow p-6">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Grafik Suhu (24 Jam)</h2>
        <span class="text-blue-600 font-semibold">
            {{ !empty($chartData['values']) ? end($chartData['values']).' °C' : '- °C' }}
        </span>
    </div>

    <canvas id="tempChart" height="120"></canvas>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const tempCtx = document.getElementById('tempChart');
    new Chart(tempCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Suhu (°C)',
                data: @json($chartData['values']),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.35,
                fill: true,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => v + '°C' } }
            }
        }
    });
</script>
@endpush