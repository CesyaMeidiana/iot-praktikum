@php
    $n = strtolower($name ?? '');

    $icon = match(true) {
        str_contains($n, 'suhu')                          => '🌡️',
        str_contains($n, 'kelembaban') || str_contains($n, 'humid') => '💧',
        str_contains($n, 'cahaya') || str_contains($n, 'ldr')       => '💡',
        str_contains($n, 'water')                          => '🌊',
        str_contains($n, 'hc-sr04') || str_contains($n, 'ultrasonic') || str_contains($n, 'jarak') => '📏',
        str_contains($n, 'mq-2') || str_contains($n, 'gas') || str_contains($n, 'asap')            => '💨',
        str_contains($n, 'flame') || str_contains($n, 'api')        => '🔥',
        default                                             => '📟',
    };
@endphp
<span {{ $attributes->merge(['class' => 'text-lg leading-none']) }}>{{ $icon }}</span>