@php
    $cards = [
        [
    'label' => 'Total Data',
    'value' => $totalData,
    'unit'  => 'Data',
    'color' => 'blue',
    'icon'  => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375',
],
        [
            'label' => 'Device Online',
            'value' => $online,
            'unit'  => 'Perangkat',
            'color' => 'green',
            'icon'  => 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12 20.25h.007v.008H12v-.008z',
        ],
        [
            'label' => 'Device Offline',
            'value' => $offline,
            'unit'  => 'Perangkat',
            'color' => 'gray',
            'icon'  => 'M3 3l18 18M8.288 15.038a5.25 5.25 0 017.424 0M12 20.25h.007v.008H12v-.008zM5.106 11.856a9.735 9.735 0 013.34-2.19m7.114 1.5a9.735 9.735 0 00-2.14-1.744',
        ],
        [
            'label' => 'Data Hari Ini',
            'value' => $dataHariIni,
            'unit'  => 'Data',
            'color' => 'purple',
            'icon'  => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375',
        ],
        [
            'label' => 'Total Kelompok',
            'value' => $totalKelompok,
            'unit'  => 'Kelompok',
            'color' => 'amber',
            'icon'  => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
        ],
        [
            'label' => 'Total Aktuator',
            'value' => $totalAktuator,
            'unit'  => 'Perangkat',
            'color' => 'violet',
            'icon'  => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z',
        ],
        [
            'label' => 'Total Sensor',
            'value' => $totalSensor,
            'unit'  => 'Perangkat',
            'color' => 'sky',
            'icon'  => 'M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z',
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
            <div class="w-9 h-9 rounded-lg {{ $c['bg'] }} flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 {{ $c['text'] }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $card['value'] }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $card['unit'] }}</p>
            </div>
        </div>
    @endforeach
</div>