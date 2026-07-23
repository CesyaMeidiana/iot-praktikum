@php
    $infoList = [
        'Koordinator'      => 'XBee Coordinator',
        'Jaringan'         => 'ZigBee',
        'Update Terakhir'  => $latestMonitoring?->created_at?->diffForHumans() ?? '-',
        // TODO: uptime sistem belum ada sumber datanya — sementara statis, ganti kalau ada cara hitung real
        'Uptime Sistem'    => '—',
        'Versi Sistem'     => 'v1.0.0',
    ];
@endphp

<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Informasi Sistem</h2>
    </div>

    <div class="divide-y">
        @foreach ($infoList as $label => $value)
            <div class="flex items-center justify-between py-2.5 text-sm">
                <span class="text-gray-500">{{ $label }}</span>
                <span class="font-medium text-gray-800">{{ $value }}</span>
            </div>
        @endforeach
    </div>
</div>