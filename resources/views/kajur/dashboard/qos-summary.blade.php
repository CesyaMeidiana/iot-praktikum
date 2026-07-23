@php
    // Batas normalisasi buat lebar progress bar (sesuaikan kalau perlu)
    $maxThroughput = 100; // kbps
    $maxDelay      = 100; // ms
    $maxJitter     = 50;  // ms

    $throughputPct = min(100, ($qosAvg['throughput'] / $maxThroughput) * 100);
    $delayPct      = min(100, ($qosAvg['delay'] / $maxDelay) * 100);
    $jitterPct     = min(100, ($qosAvg['jitter'] / $maxJitter) * 100);
    $lossPct       = min(100, $qosAvg['packet_loss']);
@endphp

<div class="bg-white rounded-xl shadow p-6 h-full">

    <div class="flex items-center justify-between mb-1">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Ringkasan QoS <span class="text-sm font-normal text-gray-400">(Dari Praktikum Terakhir)</span></h2>
            <p class="text-sm text-gray-500">Rata-rata performa jaringan ZigBee</p>
        </div>
    </div>

    <p class="text-xs text-gray-400 mb-5">
        Update terakhir: {{ $qosLastUpdate?->diffForHumans() ?? '-' }}
    </p>

    <div class="space-y-5">

        <div>
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">Throughput</span>
                <span class="text-sm text-blue-600 font-semibold">{{ $qosAvg['throughput'] }} kbps</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $throughputPct }}%"></div>
            </div>
        </div>

        <div>
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">Delay</span>
                <span class="text-sm text-green-600 font-semibold">{{ $qosAvg['delay'] }} ms</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $delayPct }}%"></div>
            </div>
        </div>

        <div>
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">Jitter</span>
                <span class="text-sm text-yellow-500 font-semibold">{{ $qosAvg['jitter'] }} ms</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $jitterPct }}%"></div>
            </div>
        </div>

        <div>
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">Packet Loss</span>
                <span class="text-sm text-red-600 font-semibold">{{ $qosAvg['packet_loss'] }} %</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $lossPct }}%"></div>
            </div>
        </div>

    </div>
</div>