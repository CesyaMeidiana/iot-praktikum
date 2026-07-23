@php
    // Menentukan badge status otomatis berdasarkan jenis sensor & nilainya
    $sensorStatus = function ($nama, $value) {
        $nama = strtolower($nama);
        $value = is_numeric($value) ? (float) $value : $value;

        if (str_contains($nama, 'temp') || str_contains($nama, 'suhu')) {
            if ($value > 32) return ['CRITICAL', 'bg-red-100 text-red-700'];
            if ($value > 30) return ['WASPADA', 'bg-amber-100 text-amber-700'];
            return ['NORMAL', 'bg-green-100 text-green-700'];
        }
        if (str_contains($nama, 'cahaya') || str_contains($nama, 'light') || str_contains($nama, 'lux')) {
            return $value >= 300
                ? ['TERANG', 'bg-amber-100 text-amber-700']
                : ['GELAP', 'bg-slate-200 text-slate-600'];
        }
        if (str_contains($nama, 'motion') || str_contains($nama, 'gerak')) {
            return $value ? ['AKTIF', 'bg-amber-100 text-amber-700'] : ['TIDAK ADA', 'bg-slate-200 text-slate-600'];
        }
        if (str_contains($nama, 'water') || str_contains($nama, 'air')) {
            return $value ? ['NORMAL', 'bg-green-100 text-green-700'] : ['RENDAH', 'bg-amber-100 text-amber-700'];
        }
        if (str_contains($nama, 'gas')) {
            if ($value > 600) return ['BAHAYA', 'bg-red-100 text-red-700'];
            if ($value > 500) return ['WASPADA', 'bg-amber-100 text-amber-700'];
            return ['AMAN', 'bg-green-100 text-green-700'];
        }
        if (str_contains($nama, 'api') || str_contains($nama, 'flame') || str_contains($nama, 'fire')) {
            return $value ? ['BAHAYA', 'bg-red-100 text-red-700'] : ['AMAN', 'bg-green-100 text-green-700'];
        }

        return ['NORMAL', 'bg-green-100 text-green-700'];
    };
@endphp

<div id="rt-monitoring" class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

@forelse($monitoring as $node => $device)

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-slate-800 text-white px-5 py-4 flex justify-between items-center">
            <h3 class="font-bold text-base">{{ $node }}</h3>

            <div class="text-right">
                <p class="text-[10px] uppercase tracking-wide text-slate-400">Last Update</p>
                <p class="text-xs text-slate-200">
                    {{ optional(collect($device['sensor'] ?? [])->first())['updated_at'] ?? '--' }}
                </p>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-5 py-2">

            @foreach($device['sensor'] ?? [] as $sensor)
                @php [$label, $badgeClass] = $sensorStatus($sensor['nama'], $sensor['value']); @endphp
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
    <x-sensor-icon :name="$sensor['nama']" />
    <span class="text-sm text-slate-600">{{ $sensor['nama'] }}</span>
</div>

                    <div class="flex items-center gap-3">
                        <span class="font-bold text-slate-800 text-sm">
                            {{ $sensor['value'] }}{{ $sensor['satuan'] ? ' '.$sensor['satuan'] : '' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $badgeClass }}">
                            {{ $label }}
                        </span>
                    </div>
                </div>
            @endforeach

            @foreach($device['actuator'] ?? [] as $actuator)
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
    <x-sensor-icon :name="$actuator['nama']" />
    <span class="text-sm text-slate-600">{{ $actuator['nama'] }}</span>
</div>

                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $actuator['status'] ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                        {{ $actuator['status'] ? 'ON' : 'OFF' }}
                    </span>
                </div>
            @endforeach

            @if(empty($device['sensor']) && empty($device['actuator']))
                <p class="text-sm text-slate-400 py-6 text-center">Belum ada data</p>
            @endif

        </div>
        <div class="h-2"></div>
    </div>

@empty

    <div class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center text-slate-400">
        Belum ada data monitoring untuk praktikum ini.
    </div>

@endforelse
<script>
function getIcon(nama) {
    const n = nama.toLowerCase();
    if (n.includes('suhu') || n.includes('temp')) return '🌡️';
if (n.includes('kelembaban') || n.includes('humid')) return '💧';
if (n.includes('cahaya') || n.includes('ldr') || n.includes('light')) return '☀️';
if (n.includes('water')) return '🛟';
if (n.includes('hc-sr04') || n.includes('ultrasonic') || n.includes('jarak')) return '📡';
if (n.includes('mq-2') || n.includes('gas') || n.includes('asap')) return '🧪';
if (n.includes('flame') || n.includes('api') || n.includes('fire')) return '🔥';
if (n.includes('motion') || n.includes('gerak')) return '🚶';
if (n.includes('fan') || n.includes('kipas')) return '🪭';
if (n.includes('led') || n.includes('lampu') || n.includes('lamp')) return '💡';
if (n.includes('pump') || n.includes('pompa')) return '🚿';
if (n.includes('buzzer')) return '📢';
return '📟';
}
document.addEventListener("realtime-update",function(e){

    const data=e.detail;

    let html='';

    data.devices.forEach(function(device){

        html+=`

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="bg-slate-800 text-white px-5 py-4 flex justify-between items-center">

        <h3 class="font-bold text-base">${device.nama_device}</h3>

        <div class="text-right">

            <p class="text-[10px] uppercase tracking-wide text-slate-400">
                Last Update
            </p>

            <p class="text-xs text-slate-200">
                ${device.sensor.length ? device.sensor[0].updated_at : '--'}
            </p>

        </div>

    </div>

    <div class="px-5 py-2">

`;

        device.sensor.forEach(function(sensor){

    html+=`

<div class="flex items-center justify-between py-3 border-b border-slate-100">

    <span class="text-sm text-slate-600 flex items-center gap-3">

        <span class="text-lg leading-none">${getIcon(sensor.nama)}</span> ${sensor.nama}

    </span>


    <span class="font-bold text-slate-800 text-sm">

        ${sensor.value} ${sensor.satuan ?? ''}

    </span>

</div>

`;

        });

        device.actuator.forEach(function(actuator){

    html+=`

<div class="flex items-center justify-between py-3 border-b border-slate-100">

    <span class="text-sm text-slate-600 flex items-center gap-3">

        <span class="text-lg leading-none">${getIcon(actuator.nama)}</span> ${actuator.nama}

    </span>


    <span class="px-2 py-1 rounded-full text-xs ${actuator.status?'bg-green-100 text-green-700':'bg-slate-200 text-slate-600'}">

        ${actuator.status?'ON':'OFF'}

    </span>

</div>

`;

        });

        if(device.sensor.length===0 && device.actuator.length===0){

            html+=`

<p class="text-sm text-slate-400 py-6 text-center">

Belum ada data

</p>

`;

        }

        html+=`

    </div>

    <div class="h-2"></div>

</div>

`;

    });

    document.getElementById("rt-monitoring").innerHTML=html;

});

</script>
</div>