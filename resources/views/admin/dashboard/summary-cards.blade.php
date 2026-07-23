@php
    $cards = [
        [
            'label' => 'Total Data',
            'value' => $totalData,
            'unit'  => 'Data',
            'color' => 'blue',
            'emoji' => '🗄️',
        ],
        [
            'label' => 'Device Online',
            'value' => $online,
            'unit'  => 'Perangkat',
            'color' => 'green',
            'emoji' => '🟢',
        ],
        [
            'label' => 'Device Offline',
            'value' => $offline,
            'unit'  => 'Perangkat',
            'color' => 'gray',
            'emoji' => '🔴',
        ],
        [
            'label' => 'Data Hari Ini',
            'value' => $dataHariIni,
            'unit'  => 'Data',
            'color' => 'purple',
            'emoji' => '📅',
        ],
        [
            'label' => 'Total Kelompok',
            'value' => $totalKelompok,
            'unit'  => 'Kelompok',
            'color' => 'amber',
            'emoji' => '👥',
        ],
        [
            'label' => 'Total Aktuator',
            'value' => $totalAktuator,
            'unit'  => 'Perangkat',
            'color' => 'violet',
            'emoji' => '🎛️',
        ],
        [
            'label' => 'Total Sensor',
            'value' => $totalSensor,
            'unit'  => 'Perangkat',
            'color' => 'sky',
            'emoji' => '🔍',
        ],
    ];

    $colorMap = [
        'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
        'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600'],
        'gray'   => ['bg' => 'bg-gray-200',   'text' => 'text-gray-700'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
        'amber'  => ['bg' => 'bg-amber-100',  'text' => 'text-amber-600'],
        'violet' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-600'],
        'sky'    => ['bg' => 'bg-sky-100',    'text' => 'text-sky-600'],
    ];
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">
    @foreach ($cards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
            <div class="w-9 h-9 rounded-lg {{ $c['bg'] }} flex items-center justify-center text-lg">
                {{ $card['emoji'] }}
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1 rt-card-value" data-label="{{ $card['label'] }}">
                    {{ $card['value'] }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $card['unit'] }}</p>
            </div>
        </div>
    @endforeach
</div>