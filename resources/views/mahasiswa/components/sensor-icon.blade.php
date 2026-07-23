$icon = match(true) {

    // ===== Sensor =====
    str_contains($n, 'suhu') || str_contains($n, 'temp')
        => '🌡️',

    str_contains($n, 'kelembaban') || str_contains($n, 'humid')
        => '💧',

    str_contains($n, 'cahaya') || str_contains($n, 'ldr') || str_contains($n, 'light')
        => '☀️',

    str_contains($n, 'water')
        => '🛟',

    str_contains($n, 'hc-sr04') || str_contains($n, 'ultrasonic') || str_contains($n, 'jarak')
        => '📡',

    str_contains($n, 'mq-2') || str_contains($n, 'gas') || str_contains($n, 'asap')
        => '🧪',

    str_contains($n, 'flame') || str_contains($n, 'api') || str_contains($n, 'fire')
        => '🔥',

    str_contains($n, 'motion') || str_contains($n, 'gerak')
        => '🚶',

    // ===== Aktuator =====
    str_contains($n, 'fan') || str_contains($n, 'kipas')
        => '🪭',

    str_contains($n, 'led') || str_contains($n, 'lampu') || str_contains($n, 'lamp')
        => '💡',

    str_contains($n, 'pump') || str_contains($n, 'pompa')
        => '🚿',

    str_contains($n, 'buzzer')
        => '📢',

    default => '📢',
};