<div class="bg-white rounded-xl shadow h-full">

    <div class="flex items-center justify-between px-6 py-4 border-b">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Status Seluruh Node</h2>
            <p class="text-sm text-gray-500">Monitoring seluruh device Smart Home</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Node</th>
                    <th class="px-6 py-3 text-left">Device</th>
                    <th class="px-6 py-3 text-left">Kelompok/Individu</th>
                    <th class="px-6 py-3 text-left">Last Update</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($devices as $index => $device)
                    <tr class="border-b hover:bg-gray-50 last:border-b-0">
                        <td class="px-6 py-4">{{ $device->node ?? 'Node '.($index + 1) }}</td>
                        <td class="px-6 py-4">{{ $device->nama_device }}</td>
                        <td class="px-6 py-4">{{ $device->pemakaiLabel }}</td>
                        <td class="px-6 py-4">
                            {{ $device->updated_at?->diffForHumans() ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">

    @if($device->lastSession)

        <a href="{{ route('admin.riwayat.show', $device->lastSession->id) }}"
           class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition">

            Detail

        </a>

    @else

        <span
            class="inline-flex items-center justify-center bg-gray-300 text-gray-500 px-3 py-2 rounded-lg cursor-not-allowed">

            Detail

        </span>

    @endif

</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-400">
                            Belum ada device terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>