<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Status IoT</h2>
    </div>

    <div class="divide-y">
        @forelse ($latestSensor as $sensor)
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-2">
    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $sensor->status === 'Normal' ? 'bg-green-100' : 'bg-yellow-100' }}">
        <x-sensor-icon :name="$sensor->nama_sensor" />
    </span>
    <span class="text-sm text-gray-700">{{ $sensor->nama_sensor }}</span>
</div>
                <span class="text-sm font-medium text-gray-800">
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