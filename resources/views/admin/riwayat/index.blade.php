@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
        Riwayat Praktikum
    </h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Mahasiswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Dosen</th>
                    <th class="px-4 py-3">Node</th>
                    <th class="px-4 py-3">Skema</th>
                    <th class="px-4 py-3">Durasi</th>
                    <th class="px-4 py-3">Jumlah Data</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sessions as $session)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $session->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $session->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $session->classroom->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $session->classroom->lecturer->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @foreach ($session->devices as $device)
                                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1">
                                    {{ $device->nama_device }}
                                </span>
                            @endforeach
                        </td>

                        <td class="px-4 py-3">
                            {{ $session->scenario }}
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $session->durasi }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $session->jumlah_data }}
                        </td>

                        <td class="px-4 py-3">
                            <a href="{{ route('admin.riwayat.show', $session) }}"
                               class="text-blue-600 hover:underline font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-500">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $sessions->links() }}
    </div>

    {{-- ================================================================ --}}
    {{-- Data monitoring yang masuk DI LUAR sesi praktikum (device tetap   --}}
    {{-- kirim data ke MQTT walau tidak ada mahasiswa yang sedang praktik) --}}
    {{-- ================================================================ --}}
<div class="mt-10">
    <h2 class="text-xl font-bold mb-4">Data Di Luar Praktikum</h2>

    @if ($luarPraktikum)
        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
            <div class="grid grid-cols-4 gap-6 text-sm">
                <div>
                    <p class="text-xs text-slate-500 uppercase">Jumlah Data</p>
                    <p id="rt-luar-jumlah" class="font-semibold mt-1">{{ $luarPraktikum->jumlah_data }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase">Node Terlibat</p>
                    <p class="font-semibold mt-1">{{ $luarPraktikum->jumlah_node }} node</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase">Periode</p>
                    <p class="font-semibold mt-1">
                        {{ $luarPraktikum->pertama->format('d/m/Y H:i') }} &ndash; {{ $luarPraktikum->terakhir->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase">Kondisi Terakhir</p>
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ str_starts_with($luarPraktikum->kondisi ?? '', 'WASPADA') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $luarPraktikum->kondisi ?? '-' }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.riwayat.luar') }}" class="text-blue-600 hover:underline font-medium whitespace-nowrap ml-6">
                Lihat Semua &rarr;
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            Tidak ada data di luar praktikum.
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener("realtime-update", function (e) {
    const d = e.detail;
    if (!d.praktikum) return;

    const tbody = document.querySelector("table tbody");
    if (!tbody) return;

    // Kalau data kosong
    if (d.praktikum.length === 0) return;

    let html = "";
    d.praktikum.forEach(function (s) {
        html += `
        <tr class="border-t hover:bg-gray-50">
            <td class="px-4 py-3 whitespace-nowrap">${s.tanggal}</td>
            <td class="px-4 py-3">${s.mahasiswa}</td>
            <td class="px-4 py-3">${s.kelas}</td>
            <td class="px-4 py-3">${s.dosen}</td>
            <td class="px-4 py-3">
                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1">
                    ${s.node}
                </span>
            </td>
            <td class="px-4 py-3">${s.scenario}</td>
            <td class="px-4 py-3 whitespace-nowrap">${s.durasi}</td>
            <td class="px-4 py-3">${s.jumlah_data}</td>
            <td class="px-4 py-3">
                <a href="${s.detail}" class="text-blue-600 hover:underline font-medium">Detail</a>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;

    // Update ringkasan luar praktikum
    if (d.luar && d.luar.length > 0) {
        const jumlahEl = document.getElementById("rt-luar-jumlah");
        if (jumlahEl) jumlahEl.innerHTML = d.luar.length + " data";
    }
});
</script>
@endpush

@endsection