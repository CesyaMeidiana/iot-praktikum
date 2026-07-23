@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Detail Riwayat Praktikum</h1>

    {{-- Kartu Ringkasan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

        <div class="grid md:grid-cols-4 gap-6">

            <div>
                <p class="text-xs text-slate-500 uppercase">Mahasiswa</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->user->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Kelas</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->classroom->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Dosen</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->classroom->lecturer->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Tanggal</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Topologi</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->topology }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Skema</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->scenario }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Jarak</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->distance }} meter</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Durasi</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->durasi }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Jumlah Data</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $riwayat->jumlah_data }} data</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Device</p>
                <p class="font-semibold text-slate-800 mt-1">
                    @foreach ($riwayat->devices as $device)
                        {{ $device->nama_device }} (Node {{ $device->node }})@if (!$loop->last), @endif
                    @endforeach
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500 uppercase">Sensor Diukur</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $sensorColumns->implode(', ') ?: '-' }}</p>
            </div>

        </div>

    </div>

    {{-- Tabel Data ala spreadsheet --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <h3 class="text-xl font-bold text-slate-800">Data Monitoring</h3>
            <span class="text-xs text-slate-400">Menampilkan {{ count($rows) }} data</span>
        </div>

        <div class="overflow-auto" style="max-height: 65vh;">
            <table class="border-collapse text-sm w-full">
                <thead class="sticky top-0 z-10">
                    <tr>
                        <th class="sticky left-0 z-20 bg-slate-100 text-slate-600 px-4 py-3 border border-slate-200 text-left whitespace-nowrap font-semibold">
                            Waktu
                        </th>

                        @foreach ($sensorColumns as $col)
                            <th class="bg-blue-50 text-blue-700 px-4 py-3 border border-blue-100 whitespace-nowrap font-semibold">
                                {{ $col }}
                            </th>
                        @endforeach

                        @foreach ($actuatorColumns as $col)
                            <th class="bg-emerald-50 text-emerald-700 px-4 py-3 border border-emerald-100 whitespace-nowrap font-semibold">
                                {{ $col }}
                            </th>
                        @endforeach

                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Packet</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Delay (ms)</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Jitter (ms)</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Throughput</th>
                        <th class="bg-amber-50 text-amber-700 px-4 py-3 border border-amber-100 whitespace-nowrap font-semibold">Loss (%)</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rows as $row)
                        <tr class="odd:bg-white even:bg-slate-50 hover:bg-blue-50">

                            <td class="sticky left-0 bg-inherit px-4 py-2 border border-slate-100 whitespace-nowrap font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($row['timestamp'])->format('H:i:s') }}
                            </td>

                            @foreach ($sensorColumns as $col)
                                <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['sensor'][$col] ?? '-' }}</td>
                            @endforeach

                            @foreach ($actuatorColumns as $col)
                                <td class="px-4 py-2 border border-slate-100 text-center">
                                    @if (isset($row['aktuator'][$col]))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $row['aktuator'][$col] ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $row['aktuator'][$col] ? 'ON' : 'OFF' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['packet'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['delay'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['jitter'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['throughput'] ?? '-' }}</td>
                            <td class="px-4 py-2 border border-slate-100 text-center">{{ $row['loss'] ?? '-' }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center py-10 text-slate-400">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection