<div id="rt-device-monitoring" class="space-y-6">
    @forelse ($deviceMonitoring as $dm)
<div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

    {{-- Header Device --}}
    <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">

        <div>

            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
    <span class="text-xl">📡</span>
    {{ $dm->device->nama_device }}
    @if($dm->device->node)
        ({{ $dm->device->node }})
    @endif
</h3>
        </div>

        <div class="text-right">

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                {{ $dm->device->status == 'Online'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700' }}">

                ● {{ $dm->device->status ?? 'Offline' }}

            </span>

        </div>

    </div>

    {{-- Isi --}}
    <div class="p-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Status IoT --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Status IoT</h2>
                    </div>
                    <div class="divide-y">
                        @forelse ($dm->sensors as $sensor)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-2">
    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $sensor->status === 'Normal' ? 'bg-green-100' : 'bg-yellow-100' }}">
        <x-sensor-icon :name="$sensor->nama_sensor" />
    </span>
    <span class="text-sm text-gray-700">{{ $sensor->nama_sensor }}</span>
</div>
                                <span id="rt-sensor-{{ $sensor->device_sensor_id ?? $loop->index }}" class="text-sm font-medium text-gray-800">
    {{ $sensor->value !== null ? $sensor->value.' '.$sensor->satuan : 'Tidak Ada' }}
</span>
                                <span class="px-2 py-0.5 rounded-full text-xs
                                    {{ $sensor->status === 'Normal' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $sensor->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 py-3">Belum ada data sensor.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Grafik Suhu --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">

    {{ $dm->chartTitle }}

</h2>
                        <span class="text-blue-600 font-semibold">
                           @if(!empty($dm->chart['values']))

    {{ end($dm->chart['values']) }}
    {{ $dm->chartUnit }}

@else

    -

@endif
                        </span>
                    </div>
                    @if(empty($dm->chart['labels']))

    <div class="h-40 flex items-center justify-center text-gray-400 text-sm">
        Belum ada data sensor.
    </div>

@else

    <canvas id="chart-device-{{ $dm->device->id }}" height="100"></canvas>

@endif
                </div>

                {{-- Pemakai Terakhir --}}
                <div class="bg-white rounded-xl shadow p-6">
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Pemakai Terakhir</h2>
    </div>

    @php
        $session = $dm->lastSession;
    @endphp

    <div class="divide-y text-sm">

        <div class="flex items-center justify-between py-2.5">
            <span class="text-gray-500">Mahasiswa</span>
            <span class="font-medium text-gray-800">
                {{ $session?->user?->name ?? '-' }}
            </span>
        </div>

        <div class="flex items-center justify-between py-2.5">
            <span class="text-gray-500">Kelas</span>
            <span class="font-medium text-gray-800">
                {{ $session?->classroom?->class_name ?? '-' }}
            </span>
        </div>

        <div class="flex items-center justify-between py-2.5">
            <span class="text-gray-500">Dosen</span>
            <span class="font-medium text-gray-800">
                {{ $session?->classroom?->lecturer?->name ?? '-' }}
            </span>
        </div>

        <div class="flex items-center justify-between py-2.5">
            <span class="text-gray-500">Waktu Pemakaian</span>
            <span class="font-medium text-gray-800">
                {{ $session?->started_at?->diffForHumans() ?? '-' }}
            </span>
        </div>

    </div>
</div>

            </div>
</div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400">
            Belum ada device terdaftar.
        </div>
    @endforelse
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const deviceCharts = @json($deviceMonitoring->mapWithKeys(fn ($dm) => [$dm->device->id => $dm->chart]));

    Object.keys(deviceCharts).forEach(function (deviceId) {
        const ctx = document.getElementById('chart-device-' + deviceId);
        if (!ctx) return;

        const series = deviceCharts[deviceId];

        window["deviceChart_"+deviceId]=new Chart(ctx,{
            type: 'line',
            data: {
                labels: series.labels,
                datasets: [{
                    label: 'Suhu (°C)',
                    data: series.values,
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
    });
</script>

<script>

document.addEventListener("realtime-update",function(e){

    const data=e.detail;

    data.devices.forEach(function(device){

        const chart=window["deviceChart_"+device.id];

        if(!chart) return;

        let value=[];

        let label=[];

        device.sensor.forEach(function(sensor){

            value.push(Number(sensor.value));

            label.push(sensor.updated_at);

        });

        chart.data.labels=label;

        chart.data.datasets[0].data=value;

        chart.update();

    });

});

</script>
@endpush