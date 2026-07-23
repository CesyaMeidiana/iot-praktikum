{{-- Summary Card --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Praktikum Aktif --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                🧪
            </div>
            <p class="text-sm text-slate-500">Praktikum Aktif</p>
        </div>

        <h2 id="rt-praktikum-aktif" class="text-4xl font-bold text-slate-800 mt-4">
    {{ $praktikumAktif }}
</h2>
        <p class="text-xs text-slate-400 mt-2">
            {{ $praktikumAktif > 0 ? 'Sedang berlangsung' : 'Belum ada praktikum aktif' }}
        </p>
    </div>

    {{-- Deadline --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                📋
            </div>
            <p class="text-sm text-slate-500">Deadline Tugas</p>
        </div>

        <h2 id="rt-deadline" class="text-4xl font-bold text-orange-500 mt-4">
    {{ $jumlahDeadline }}
</h2>
        <p class="text-xs text-slate-400 mt-2 truncate">
            {{ $deadline?->title ?? 'Tidak ada deadline' }}
        </p>
    </div>

    {{-- Device Online --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                📡
            </div>
            <p class="text-sm text-slate-500">Device Online</p>
        </div>

        <h2 class="text-3xl font-bold mt-4">
    <span id="rt-device-online" class="text-green-600">{{ $deviceOnline }}</span>
    <span class="text-slate-300 font-normal">/ <span id="rt-total-device">{{ $totalDevice }}</span></span>
</h2>

        <div id="rt-device-list" class="space-y-1.5 mt-3 text-sm">
            @forelse ($devices as $device)
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $device->is_online ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    <span class="text-slate-600">{{ $device->name }}</span>
                    <span class="{{ $device->is_online ? 'text-green-600' : 'text-red-500' }} text-xs ml-auto">
                        {{ $device->is_online ? 'Online' : 'Offline' }}
                    </span>
                </div>
            @empty
                <span class="text-slate-400 text-xs">Belum ada device</span>
            @endforelse
        </div>
    </div>

    {{-- Kelompok --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                👥
            </div>
            <p class="text-sm text-slate-500">Kelompok</p>
        </div>

        <h2 class="text-2xl font-bold text-slate-800 mt-4 truncate">
            {{ $kelompok?->nama_kelompok ?? '-' }}
        </h2>

        <p class="text-xs text-slate-500 mt-2 truncate">
            Dosen: <span class="font-medium text-slate-700">{{ $classroom?->lecturer?->name ?? '-' }}</span>
        </p>
        <p class="text-xs text-slate-500 truncate">
            Angkatan: <span class="font-medium text-slate-700">{{ $kelompok?->angkatan ?? $classroom?->angkatan ?? '-' }}</span>
        </p>
    </div>
<script>

document.addEventListener("realtime-update",function(e){

    const data=e.detail;

    document.getElementById("rt-device-online").innerHTML=data.device_online;

    document.getElementById("rt-total-device").innerHTML=data.total_device;

    let html='';

    data.devices.forEach(function(device){

        html+=`
        <div class="flex items-center gap-2">

            <span class="w-1.5 h-1.5 rounded-full ${device.online?'bg-green-500':'bg-red-500'}"></span>

            <span class="text-slate-600">${device.nama_device}</span>

            <span class="${device.online?'text-green-600':'text-red-500'} text-xs ml-auto">

                ${device.online?'Online':'Offline'}

            </span>

        </div>
        `;

    });

    document.getElementById("rt-device-list").innerHTML=html;

});

</script>
</div>