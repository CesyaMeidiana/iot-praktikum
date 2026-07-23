<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Informasi Sistem</h2>
    </div>

    <div class="divide-y">

        <div class="flex items-center justify-between py-2.5 text-sm">
            <span class="text-gray-500">Koordinator</span>
            <span class="font-medium text-gray-800">XBee Coordinator</span>
        </div>

        <div class="flex items-center justify-between py-2.5 text-sm">
            <span class="text-gray-500">Jaringan</span>
            <span class="font-medium text-gray-800">ZigBee</span>
        </div>

        <div class="flex items-center justify-between py-2.5 text-sm">
            <span class="text-gray-500">Update Terakhir</span>
            <span id="rt-info-update" class="font-medium text-gray-800">
                {{ $latestMonitoring?->created_at?->diffForHumans() ?? '-' }}
            </span>
        </div>

        <div class="flex items-center justify-between py-2.5 text-sm">
            <span class="text-gray-500">Uptime Sistem</span>
            <span class="font-medium text-gray-800">—</span>
        </div>

        <div class="flex items-center justify-between py-2.5 text-sm">
            <span class="text-gray-500">Versi Sistem</span>
            <span class="font-medium text-gray-800">v1.0.0</span>
        </div>

    </div>
</div>