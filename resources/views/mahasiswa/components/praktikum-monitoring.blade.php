{{-- Parameter Monitoring & Kondisi --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">

    <h2 class="text-lg font-bold text-slate-800 mb-5">
        Parameter Monitoring &amp; Kondisi
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-3 pr-4 font-medium">No</th>
                    <th class="py-3 pr-4 font-medium">Parameter</th>
                    <th class="py-3 pr-4 font-medium">Alat</th>
                    <th class="py-3 pr-4 font-medium">Tipe</th>
                    <th class="py-3 pr-4 font-medium">Fungsi</th>
                    <th class="py-3 font-medium">Kondisi &amp; Ambang Batas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parameterMonitoring as $i => $row)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-3 pr-4 text-slate-500">{{ $i + 1 }}</td>
                        <td class="py-3 pr-4 text-slate-700 font-medium">{{ $row['parameter'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['alat'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $row['tipe'] }}</td>
                        <td class="py-3 pr-4 text-slate-500">{{ $row['fungsi'] }}</td>
                        <td class="py-3">
                            @forelse ($row['kondisi'] as $kondisi)
                                <span class="inline-block px-2.5 py-1 mr-1.5 mb-1 rounded-full text-[11px] font-medium
                                    {{ $kondisi['label'] === 'ON' ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $kondisi['label'] }}: {{ $kondisi['teks'] }}
                                </span>
                            @empty
                                <span class="text-slate-400 text-xs">-</span>
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Belum ada data parameter untuk praktikum ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>