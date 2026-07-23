<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ActuatorLog;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Device;
use App\Models\PraktikumSession;
use App\Models\QosLog;
use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    public function index(Request $request)
{
        $user = Auth::user();

        $praktikumAktif = PraktikumSession::where('user_id', $user->id)
            ->where('status', 'running')
            ->count();

        $kelompok = $user->groups()->first();

        $classroom = $user->joinedClassrooms()
            ->with('lecturer')
            ->first();

       $praktikum = PraktikumSession::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$praktikum) {
            $qosAnalysis = $this->buildQosAnalysis($user);

            return view('mahasiswa.dashboard', [
                'praktikumAktif'      => $praktikumAktif,
                'deviceOnline'        => 0,
                'totalDevice'         => 0,
                'devices'             => collect(),
                'qosAvg' => ['throughput' => 0, 'delay' => 0, 'jitter' => 0, 'packet_loss' => 0],
                'kelompok'            => $kelompok,
                'praktikum'           => null,
                'classroom'           => $classroom,
                'riwayat'             => collect(),
                'deadline'            => null,
                'jumlahDeadline'      => 0,
                'monitoring'          => [],
                'qos'                 => null,
                'riwayatAlarm'        => collect(),
                'chartSeries'         => [],
                'parameterMonitoring' => collect(),
                ...$qosAnalysis,
            ]);
        }

        $riwayat = PraktikumSession::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $deadline = Assignment::where('deadline', '>=', now())
            ->orderBy('deadline')
            ->first();

        $jumlahDeadline = Assignment::where('deadline', '>=', now())
            ->count();

        // Device yang dipakai pada praktikum session ini saja (bukan seluruh device di DB)
        $praktikumDevices = $praktikum->devices()
            ->with('sensors.actuators')
            ->get();

        $totalDevice = $praktikumDevices->count();

       $onlineThreshold = now()->subSeconds(15);

        $devices = $praktikumDevices->map(function ($device) use ($praktikum, $onlineThreshold) {
            $isOnline = SensorLog::where('praktikum_session_id', $praktikum->id)
                ->whereHas('sensor', function ($q) use ($device) {
                    $q->where('device_id', $device->id);
                })
                ->where('created_at', '>=', $onlineThreshold)
                ->exists();

            return (object) [
                'name'      => $device->nama_device,
                'is_online' => $isOnline,
            ];
        });

        $deviceOnline = $devices->where('is_online', true)->count();

        $latestSensor = SensorLog::where('praktikum_session_id', $praktikum->id)
            ->with('sensor.device')
            ->latest()
            ->get()
            ->unique('device_sensor_id')
            ->values();

        $latestActuator = ActuatorLog::where('praktikum_session_id', $praktikum->id)
            ->with('actuator.sensor.device')
            ->latest()
            ->get()
            ->unique('device_actuator_id')
            ->values();

        $monitoring = [];

        foreach ($latestSensor as $log) {
            $node = $log->sensor->device->nama_device;

            $monitoring[$node]['sensor'][] = [
                'nama'   => $log->sensor->nama_sensor,
                'value'  => $log->value,
                'satuan' => $log->sensor->satuan,
            ];
        }

        foreach ($latestActuator as $log) {
            $node = $log->actuator->sensor->device->nama_device;

            $monitoring[$node]['actuator'][] = [
                'nama'   => $log->actuator->nama_aktuator,
                'status' => $log->status,
            ];
        }

        // QosLog tidak punya kolom praktikum_session_id.
        // Ambil lewat method PraktikumSession::getQosLogs() yang sudah
        // memfilter berdasarkan device_id milik session ini + rentang waktu session.
        $qosLogs = $praktikum->getQosLogs();

        $qos = $qosLogs->sortByDesc('created_at')->first();

        if ($qos) {
            $qos->delay  = max(0, $qos->delay - 5000);
            $qos->jitter = max(0, $qos->jitter - 5000);
        }

$qosAvg = [
    'throughput'  => round($qosLogs->avg('throughput') ?? 0, 2),
    'delay'       => round($qosLogs->avg(fn ($l) => max(0, $l->delay - 5000)) ?? 0, 2),
    'jitter'      => round($qosLogs->avg(fn ($l) => max(0, $l->jitter - 5000)) ?? 0, 2),
    'packet_loss' => round($qosLogs->avg('packet_loss') ?? 0, 2),
];

        // Riwayat Alarm: diturunkan dari histori sensor_logs (data real),
        // dievaluasi terhadap ambang batas per jenis sensor.
        $alarmLogs = SensorLog::where('praktikum_session_id', $praktikum->id)
    ->with('sensor.device')
    ->latest()
    ->get();

$riwayatAlarm = $alarmLogs
    ->map(fn($log) => $this->evaluateAlarm($log))

    // urutkan berdasarkan prioritas
    ->sort(function ($a, $b) {

        $priority = [
            'CRITICAL' => 1,
            'WARNING'  => 2,
            'NORMAL'   => 3,
        ];

        if ($priority[$a['status']] !== $priority[$b['status']]) {
            return $priority[$a['status']] <=> $priority[$b['status']];
        }

        return $b['raw_value'] <=> $a['raw_value'];
    })

    // parameter tidak boleh duplikat
    ->unique('parameter')

    // ambil 5 data
    ->take(5)

    ->values();

        // Data grafik: dikelompokkan per jenis sensor (Temperature, Water Level, Gas),
        // diambil dari histori sensor_logs real milik praktikum session ini.
        $chartSeries = $this->buildChartSeries($praktikum->id);

        // Parameter Monitoring & Kondisi: diambil dari data master DeviceSensor +
        // DeviceActuator milik device yang dipakai pada praktikum session ini.
        $parameterMonitoring = $this->buildParameterMonitoring($praktikumDevices);
$qosPerSesi = PraktikumSession::where('user_id', $user->id)
    ->latest()
    ->get()
    ->map(function ($s) {
        $logs = $s->getQosLogs();
        if ($logs->isEmpty()) return null;

       return [
            'id'          => $s->id,
            'jarak'       => $s->distance,
            'throughput'  => round($logs->avg('throughput'), 2),
            'delay'       => round($logs->avg(fn ($l) => max(0, $l->delay - 5)), 2),
            'jitter'      => round($logs->avg(fn ($l) => max(0, $l->jitter - 5)), 2),
            'packet_loss' => round($logs->avg('packet_loss'), 2),
        ];
    })
    ->filter()
    ->values();

        $qosAnalysis = $this->buildQosAnalysis($user);

        $praktikums = PraktikumSession::where('user_id', $user->id)
    ->where('status', 'finished')
    ->latest()
    ->get();

$selectedPraktikum = request('praktikum')
    ?? optional($praktikums->first())->id;

        return view('mahasiswa.dashboard', [
            'praktikumAktif'      => $praktikumAktif,
            'deviceOnline'        => $deviceOnline,
            'totalDevice'         => $totalDevice,
            'devices'             => $devices,
            'qosAvg'              => $qosAvg,
            'kelompok'            => $kelompok,
            'praktikum'           => $praktikum,
            'classroom'           => $classroom,
            'riwayat'             => $riwayat,
            'deadline'            => $deadline,
            'jumlahDeadline'      => $jumlahDeadline,
            'monitoring'          => $monitoring,
            'qos'                 => $qos,
            'riwayatAlarm'        => $riwayatAlarm,
            'chartSeries'         => $chartSeries,
            'parameterMonitoring' => $parameterMonitoring,
'qosPerSesi' => $qosPerSesi,

'praktikums' => $praktikums,
'selectedPraktikum' => $selectedPraktikum,

...$qosAnalysis,
        ]);
    }

    /**
     * Mengevaluasi satu baris SensorLog menjadi baris "Riwayat Alarm":
     * node, parameter, nilai tampilan, status (NORMAL/WARNING/CRITICAL), keterangan.
     */
    private function evaluateAlarm(SensorLog $log): array
    {
        $namaSensor = $log->sensor->nama_sensor ?? '-';
        $namaLower  = strtolower($namaSensor);
        $node       = $log->sensor->device->nama_device ?? '-';
        $value      = $log->value;
        $satuan     = $log->sensor->satuan ?? '';
        $waktu      = $log->created_at;

        $nilaiTampil = $value . ($satuan ? ' ' . $satuan : '');
        $status      = 'NORMAL';
        $keterangan  = 'Kondisi normal';

        if (str_contains($namaLower, 'temp') || str_contains($namaLower, 'suhu')) {
            $v = (float) $value;
            if ($v > 32) {
                $status = 'CRITICAL';
                $keterangan = 'Suhu terlalu tinggi';
            } elseif ($v > 30) {
                $status = 'WARNING';
                $keterangan = 'Suhu mendekati batas atas';
            } else {
                $status = 'NORMAL';
                $keterangan = 'Suhu kembali normal';
            }
        } elseif (str_contains($namaLower, 'gas')) {
            $v = (float) $value;
            if ($v > 600) {
                $status = 'CRITICAL';
                $keterangan = 'Konsentrasi gas tinggi';
            } elseif ($v > 500) {
                $status = 'WARNING';
                $keterangan = 'Konsentrasi gas meningkat';
            } else {
                $status = 'NORMAL';
                $keterangan = 'Konsentrasi gas aman';
            }
        } elseif (str_contains($namaLower, 'water') || str_contains($namaLower, 'air')) {
            if ((float) $value == 0) {
                $status = 'WARNING';
                $keterangan = 'Ketinggian air rendah';
                $nilaiTampil = 'LOW';
            } else {
                $status = 'NORMAL';
                $keterangan = 'Ketinggian air normal';
                $nilaiTampil = 'NORMAL';
            }
        } elseif (str_contains($namaLower, 'api') || str_contains($namaLower, 'flame') || str_contains($namaLower, 'fire')) {
            if ((float) $value == 1) {
                $status = 'WARNING';
                $keterangan = 'Api terdeteksi';
                $nilaiTampil = 'Detected';
            } else {
                $status = 'NORMAL';
                $keterangan = 'Tidak ada api terdeteksi';
                $nilaiTampil = 'Not Detected';
            }
        } elseif (str_contains($namaLower, 'motion') || str_contains($namaLower, 'gerak')) {
            $status = 'NORMAL';
            if ((float) $value == 1) {
                $keterangan = 'Aktivitas terdeteksi';
                $nilaiTampil = 'Detected';
            } else {
                $keterangan = 'Tidak ada aktivitas';
                $nilaiTampil = 'Not Detected';
            }
        } elseif (str_contains($namaLower, 'cahaya') || str_contains($namaLower, 'light') || str_contains($namaLower, 'lux')) {
            $status = 'NORMAL';
            $keterangan = ((float) $value >= 300) ? 'Kondisi terang' : 'Kondisi gelap';
        }

        return [
            'waktu'      => $waktu?->format('H:i:s') ?? '-',
            'node'       => $node,
            'parameter'  => $namaSensor,
            'nilai'      => $nilaiTampil,
            'status'     => $status,
            'keterangan' => $keterangan,
            'raw_value'  => (float)$value,
        ];
    }

    /**
     * Membangun data grafik per jenis sensor (Temperature, Water Level, Gas)
     * dari histori sensor_logs real milik praktikum session tertentu.
     */
    private function buildChartSeries(int $praktikumSessionId): array
{
    $groups = [
        'temperature' => ['keywords' => ['temp', 'suhu'], 'label' => 'Temperature', 'color' => '#2563eb', 'unit' => '°C'],
        'water_level' => ['keywords' => ['water', 'air'], 'label' => 'Water Level', 'color' => '#0ea5e9', 'unit' => '%'],
        'gas'         => ['keywords' => ['gas'], 'label' => 'Gas Concentration', 'color' => '#f97316', 'unit' => 'ppm'],
    ];

    $bucketCount   = 6;
    $windowMinutes = 15;
    $bucketMinutes = $windowMinutes / $bucketCount; // 2.5 menit per titik

    $allLogs = SensorLog::where('praktikum_session_id', $praktikumSessionId)
        ->with('sensor')
        ->orderBy('created_at')
        ->get();

    $series = [];

    foreach ($groups as $key => $group) {
        $logs = $allLogs->filter(function ($log) use ($group) {
            $nama = strtolower($log->sensor->nama_sensor ?? '');
            foreach ($group['keywords'] as $keyword) {
                if (str_contains($nama, $keyword)) return true;
            }
            return false;
        })->values();

        if ($logs->isEmpty()) continue;

        // Jadikan patokan waktu = data TERAKHIR yang masuk buat sensor ini
        // (bukan now()), supaya sesi yang udah selesai lama pun tetep nampilin
        // 15 menit terakhir data-nya, bukan kosong karena "sekarang" udah lewat jauh.
        $referenceTime = $logs->last()->created_at;

        $buckets = [];
        for ($i = $bucketCount - 1; $i >= 0; $i--) {
            $bucketEnd   = $referenceTime->copy()->subMinutes($i * $bucketMinutes);
            $bucketStart = $bucketEnd->copy()->subMinutes($bucketMinutes);

            $inBucket = $logs->filter(fn ($l) => $l->created_at->gt($bucketStart) && $l->created_at->lte($bucketEnd));

            $buckets[] = [
                'label' => $bucketEnd->format('H:i'),
                'value' => $inBucket->isNotEmpty() ? round($inBucket->avg('value'), 2) : null,
            ];
        }

        // Isi titik yang bolong (belum ada data di slot itu) pakai nilai sebelumnya,
        // biar garis ga putus-putus.
        $lastValue = null;
        foreach ($buckets as &$b) {
            if ($b['value'] === null) {
                $b['value'] = $lastValue;
            } else {
                $lastValue = $b['value'];
            }
        }
        unset($b);

        $satuan = $logs->last()->sensor->satuan ?? $group['unit'];

        $series[$key] = [
            'label'   => $group['label'] . ' (' . $satuan . ')',
            'color'   => $group['color'],
            'unit'    => $satuan,
            'labels'  => array_column($buckets, 'label'),
            'data'    => array_column($buckets, 'value'),
            'current' => (float) $logs->last()->value,
        ];
    }

    return $series;
}

    /**
     * Membangun tabel "Parameter Monitoring & Kondisi" dari data real
     * DeviceSensor + DeviceActuator milik device yang dipakai pada
     * praktikum session mahasiswa yang sedang login.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Device>  $praktikumDevices
     */
    private function buildParameterMonitoring($praktikumDevices)
    {
        $rows = collect();

        foreach ($praktikumDevices as $device) {
            foreach ($device->sensors as $sensor) {
                $kondisi = collect();

                foreach ($sensor->actuators as $actuator) {
                    if (!empty($actuator->kondisi_on)) {
                        $kondisi->push([
                            'label' => 'ON',
                            'teks'  => $actuator->kondisi_on,
                        ]);
                    }
                    if (!empty($actuator->kondisi_off)) {
                        $kondisi->push([
                            'label' => 'OFF',
                            'teks'  => $actuator->kondisi_off,
                        ]);
                    }
                }

                $rows->push([
                    'node'      => $device->nama_device,
                    'alat'      => $sensor->nama_sensor,
                    'parameter' => $sensor->parameter ?? '-',
                    'tipe'      => $sensor->satuan ?? '-',
                    'fungsi'    => $sensor->keterangan ?? '-',
                    'kondisi'   => $kondisi,
                ]);
            }
        }

        return $rows->values();
    }

    private function buildQosAnalysis($user): array
{
    $userDeviceIds = PraktikumSession::where('user_id', $user->id)
        ->with('devices')->get()
        ->pluck('devices')->flatten()->pluck('id')->unique()->values();
$praktikums = PraktikumSession::where('user_id', $user->id)
    ->where('status', 'finished')
    ->latest()
    ->get();

$selectedPraktikum = request('praktikum')
    ?? optional($praktikums->first())->id;

$qosChartSeries = [
    'labels' => [],
    'throughput' => [],
    'delay' => [],
    'jitter' => [],
    'loss' => [],
];

if ($selectedPraktikum) {

    $praktikum = PraktikumSession::find($selectedPraktikum);

    if ($praktikum) {

        $logs = $praktikum->getQosLogs()
            ->sortBy('packet')
            ->unique('packet')
            ->values();

        $qosChartSeries = [

            'labels' => $logs->pluck('packet')->all(),

            'throughput' => $logs->pluck('throughput')->all(),

            'delay' => $logs
                ->map(fn($l)=>max(0,$l->delay-5000))
                ->all(),

            'jitter' => $logs
                ->map(fn($l)=>max(0,$l->jitter-5000))
                ->all(),

            'loss' => $logs
                ->pluck('packet_loss')
                ->all(),

        ];
    }
}

return compact(
    'praktikums',
    'selectedPraktikum',
    'qosChartSeries'
);

    $selectedJarak = request('jarak', $qosJarakOptions->first());
    $chartSession = $filteredSessions
    ->where('distance', $selectedJarak)
    ->sortByDesc('created_at')
    ->first();

$qosChartSeries = [
    'labels' => [],
    'throughput' => [],
    'delay' => [],
    'jitter' => [],
    'loss' => [],
];

if ($chartSession) {

    $logs = $chartSession->getQosLogs()
    ->sortBy('packet')
    ->unique('packet')
    ->values();

$count = $logs->count();

if ($count > 15) {
    $logs = $logs->slice($count - 15)->values();
}

    $qosChartSeries['labels'] = $logs
        ->pluck('packet')
        ->map(fn($p) => 'P'.$p)
        ->all();

    $qosChartSeries['throughput'] = $logs->pluck('throughput')->all();

    $qosChartSeries['delay'] = $logs
        ->map(fn($l)=>max(0,$l->delay-5))
        ->all();

    $qosChartSeries['jitter'] = $logs
        ->map(fn($l)=>max(0,$l->jitter-5))
        ->all();

    $qosChartSeries['loss'] = $logs
        ->pluck('packet_loss')
        ->all();
}

    return compact(
        'qosNodeOptions', 'qosKondisiOptions', 'qosJarakOptions',
        'selectedNode', 'selectedKondisi', 'selectedJarak',
        'qosResultRows', 'qosChartSeries'
    );
}

private function qosKategori($delay, $loss): array
{
    if ($loss > 5 || $delay > 50) return ['Poor', 'bg-red-100 text-red-600'];
    if ($loss > 1 || $delay > 20) return ['Good', 'bg-blue-100 text-blue-600'];
    return ['Excellent', 'bg-green-100 text-green-600'];
}
}