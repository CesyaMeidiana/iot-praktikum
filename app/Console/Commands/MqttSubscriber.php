<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\Device;
use App\Models\DeviceSensor;
use App\Models\DeviceActuator;
use App\Models\SensorLog;
use App\Models\ActuatorLog;
use App\Models\QosLog;
use App\Models\PraktikumSession;
use Illuminate\Support\Facades\Schema;
use App\Models\PraktikumSessionActuatorConfig;
use App\Models\MonitoringLog;

class MqttSubscriber extends Command
{
    protected $signature = 'mqtt:subscribe';

    protected $description = 'Subscribe MQTT Broker';

    public function handle()
    {
        $server = env('MQTT_HOST');
        $port = (int) env('MQTT_PORT', 8883);
        $clientId = 'laravel-subscriber-' . uniqid();

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USERNAME'))
            ->setPassword(env('MQTT_PASSWORD'))
            ->setUseTls(true);

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, true);

        $this->info('=================================');
        $this->info(' MQTT Subscriber Running...');
        $this->info('=================================');

        $mqtt->subscribe('smarthome/#', function ($topic, $message) {

            $this->info("TOPIC : " . $topic);
            $this->info("DATA  : " . $message);

            try {
                $this->simpanData($message);
            } catch (\Throwable $e) {
                $this->error("GAGAL SIMPAN: " . $e->getMessage());
            }

        }, 0);

        $mqtt->loop(true);

        $mqtt->disconnect();
    }

    private function simpanData($message)
    {
        $data = json_decode($message, true);

        if (!$data || !isset($data['nodeID'])) {
            $this->error('Payload tidak valid, dilewati.');
            return;
        }

        // 1. Cari device berdasarkan nodeID
        $device = Device::where('node', $data['nodeID'])->first();

        if (!$device) {
            $this->error("Device dengan node {$data['nodeID']} tidak ditemukan di database.");
            return;
        }

        // 2. Cek apakah device ini lagi diklaim sesi praktikum yang running
        $session = PraktikumSession::where('status', 'running')
            ->whereHas('devices', function ($query) use ($device) {
                $query->where('devices.id', $device->id);
            })
            ->first();

        $sessionId = $session ? $session->id : null;

        // Update jejak waktu terakhir device ini kirim data (buat auto-timeout nanti),
        // hanya kalau kolomnya memang ada, biar gak crash kalau belum di-migrate.
        if ($session && Schema::hasColumn('praktikum_sessions', 'last_data_at')) {
            $session->forceFill(['last_data_at' => now()])->save();
        }

        // 3. Simpan tiap sensor yang ada di payload
        // Matching parameter dibuat CASE-INSENSITIVE supaya tidak gagal diam-diam
        // kalau di device_sensors kolom "parameter" ditulis "Temperature" dst.
        $sensorMap = [
            'temperature' => 'temperature',
            'humidity'    => 'humidity',
            'ldr'         => 'ldr',
            'water'       => 'water',
            'hcsr04'      => 'hcsr04',
            'mq2'         => 'mq2',
            'flame'       => 'flame',
        ];

        $savedSensors = [];

        foreach ($sensorMap as $jsonKey => $parameter) {
            if (!isset($data[$jsonKey])) {
                continue;
            }

            $deviceSensor = DeviceSensor::where('device_id', $device->id)
                ->whereRaw('LOWER(parameter) = ?', [strtolower($parameter)])
                ->first();

            if (!$deviceSensor) {
                $this->warn("  - Sensor '{$parameter}' tidak ditemukan untuk device_id={$device->id}, dilewati.");
                continue;
            }

            SensorLog::create([
                'praktikum_session_id' => $sessionId,
                'device_sensor_id'     => $deviceSensor->id,
                'value'                => $data[$jsonKey],
            ]);

            $savedSensors[$parameter] = $deviceSensor;
        }

        // 4. Simpan status aktuator — generic, jalan buat node manapun
        $deviceActuators = $device->actuators()->get();
        $savedActuators = [];

        if (isset($data['actuator1']) && isset($deviceActuators[0])) {
            $act = $deviceActuators[0];
            ActuatorLog::create([
                'praktikum_session_id' => $sessionId,
                'device_actuator_id'   => $act->id,
                'status'               => (bool) $data['actuator1'],
            ]);
            $savedActuators[1] = $act;
        } else {
            $this->warn("  - actuator1 tidak ada payload atau device_id={$device->id} belum punya aktuator ke-1.");
        }

        if (isset($data['actuator2']) && isset($deviceActuators[1])) {
            $act = $deviceActuators[1];
            ActuatorLog::create([
                'praktikum_session_id' => $sessionId,
                'device_actuator_id'   => $act->id,
                'status'               => (bool) $data['actuator2'],
            ]);
            $savedActuators[2] = $act;
        } else {
            $this->warn("  - actuator2 tidak ada payload atau device_id={$device->id} belum punya aktuator ke-2.");
        }

        // 5. Simpan QoS - SEKARANG terikat ke sesi (praktikum_session_id),
        // sama kayak sensor & aktuator. Ini penting: kalau device lagi gak
        // diklaim sesi manapun (bukan lagi dipakai praktikum), $sessionId
        // bakal null, dan QoS-nya juga kesimpen null - gak nyasar ke sesi
        // orang lain yang kebetulan rentang waktunya numpuk.
        if (isset($data['qos'])) {
            QosLog::updateOrCreate(

    [
        'device_id' => $device->id,
        'packet'    => $data['packetID'] ?? 0,
    ],

    [
        'praktikum_session_id' => $sessionId,
        'delay'       => $data['qos']['delay'] ?? 0,
        'jitter'      => $data['qos']['jitter'] ?? 0,
        'throughput'  => $data['qos']['throughput'] ?? 0,
        'packet_loss' => $data['qos']['packetLoss'] ?? 0,
    ]

);
        }

$readings = ['sensor' => [], 'aktuator' => []];
foreach ($sensorMap as $jsonKey => $parameter) {
    if (!isset($data[$jsonKey]) || !isset($savedSensors[$parameter])) continue;
    $readings['sensor'][$savedSensors[$parameter]->parameter] = $data[$jsonKey];
}

$kondisiTriggered = [];
if ($session) {
    foreach ($savedSensors as $parameter => $deviceSensor) {
        foreach ($deviceSensor->actuators as $actuator) {
            $config = PraktikumSessionActuatorConfig::where('praktikum_session_id', $session->id)
                ->where('device_actuator_id', $actuator->id)
                ->first();

            if ($config && $config->isTriggeredOn($data[array_search($parameter, $sensorMap)] ?? null)) {
                $kondisiTriggered[] = $actuator->nama_aktuator;
            }
        }
    }
}

foreach ($savedActuators as $slot => $act) {
    $readings['aktuator'][$act->nama_aktuator] = ((bool) $data['actuator' . $slot]) ? 'ON' : 'OFF';
}

MonitoringLog::create([
    'praktikum_session_id' => $sessionId,
    'device_id'            => $device->id,
    'packet_id'            => $data['packetID'] ?? null,
    'topologi'             => $session->topology ?? null,
    'jarak'                => $session->distance ?? null,
    'throughput'           => $data['qos']['throughput'] ?? null,
    'delay'                => $data['qos']['delay'] ?? null,
    'jitter'               => $data['qos']['jitter'] ?? null,
    'packet_loss'          => $data['qos']['packetLoss'] ?? null,
    'kondisi'              => empty($kondisiTriggered) ? 'AMAN' : 'WASPADA: ' . implode(', ', $kondisiTriggered),
    'readings'             => $readings,
]);

        $jumlahTersimpan = count($savedSensors);

        $this->info($sessionId
            ? "Data disimpan ({$jumlahTersimpan} sensor) -> sesi #{$sessionId}"
            : "Data disimpan ({$jumlahTersimpan} sensor) -> TANPA sesi (di luar praktikum)"
        );
    }

    
}