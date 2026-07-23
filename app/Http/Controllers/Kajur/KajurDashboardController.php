<?php

namespace App\Http\Controllers\kajur;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Group;
use App\Models\Classroom;
use App\Models\DeviceSensor;
use App\Models\DeviceActuator;

use App\Models\SensorLog;
use App\Models\PraktikumSession;

use Carbon\Carbon;

class KajurDashboardController extends Controller
{
    public function index()
    {
        // ===================== SUMMARY CARDS =====================
        $totalData = SensorLog::count();
        $totalKelompok = Group::count();
        $totalSensor   = DeviceSensor::count();
        $totalAktuator = DeviceActuator::count();

        $online   = Device::where('status', 'Online')->count();
        $offline  = Device::where('status', 'Offline')->count();
        $warning  = Device::where('status', 'Warning')->count();
        $critical = Device::where('status', 'Critical')->count();

        // Jumlah data masuk (sensor log) hari ini
        $dataHariIni = SensorLog::whereDate('created_at', Carbon::today())->count();

        // ===================== KELAS AKTIF =====================
        // ASUMSI: kolom `status` di tabel classrooms bernilai 'aktif' untuk kelas yang sedang berjalan.
        $totalKelas      = Classroom::count();
        $totalKelasAktif = Classroom::where('status', 'aktif')->count();

        // ===================== RINGKASAN QOS (DARI PRAKTIKUM TERAKHIR) =====================
        $lastPraktikum = PraktikumSession::latest('started_at')->first();
        $qosLogs = $lastPraktikum?->getQosLogs() ?? collect();

        $avgThroughput = $qosLogs->avg('throughput');
        $avgDelay      = $qosLogs->avg('delay');
        $avgJitter     = $qosLogs->avg('jitter');
        $avgLoss       = $qosLogs->avg('packet_loss');

        $qosAvg = [
            'throughput'  => $avgThroughput !== null ? round($avgThroughput, 1) : 0,
            'delay'       => $avgDelay !== null ? max(0, round($avgDelay, 1) - 5) : 0,
            'jitter'      => $avgJitter !== null ? round($avgJitter, 1) : 0,
            'packet_loss' => $avgLoss !== null ? round($avgLoss, 1) : 0,
        ];

        $qosLastUpdate = $qosLogs->max('created_at');

        // ===================== SISTEM PEMAKAIAN SKEMA (BERDASARKAN PRAKTIKUM TERAKHIR) =====================
        $jumlahData = $lastPraktikum?->jumlah_data ?? 0;

        $skemaTopologi = [
            'Point to Point' => 0,
            'Star' => 0,
            'Mesh' => 0,
            'Tree' => 0,
        ];

        if ($lastPraktikum) {
            switch (strtoupper($lastPraktikum->topology)) {
                case 'POINT TO POINT':
                case 'P2P':
                    $skemaTopologi['Point to Point'] = $jumlahData;
                    break;
                case 'STAR':
                    $skemaTopologi['Star'] = $jumlahData;
                    break;
                case 'MESH':
                    $skemaTopologi['Mesh'] = $jumlahData;
                    break;
                case 'TREE':
                    $skemaTopologi['Tree'] = $jumlahData;
                    break;
            }
        }

        $skemaLosNlos = [
            'LOS' => 0,
            'NLOS' => 0,
        ];

        if ($lastPraktikum) {
            $scenario = strtoupper($lastPraktikum->scenario);
            if (isset($skemaLosNlos[$scenario])) {
                $skemaLosNlos[$scenario] = $jumlahData;
            }
        }

        $skemaJarak = [
            1 => 0,
            5 => 0,
            10 => 0,
            15 => 0,
            20 => 0,
        ];

        if ($lastPraktikum) {
            $jarak = (int) $lastPraktikum->distance;
            if (isset($skemaJarak[$jarak])) {
                $skemaJarak[$jarak] = $jumlahData;
            }
        }

        // ===================== KELAS AKTIF (DETAIL PER DOSEN) =====================
        $kelasAktifList = Classroom::where('status', 'aktif')
            ->with(['lecturer', 'groups', 'students'])
            ->get()
            ->map(fn ($classroom) => (object) [
                'dosen'           => $classroom->lecturer->name ?? '-',
                'jumlahKelompok'  => $classroom->groups->count(),
                'jumlahMahasiswa' => $classroom->students->count(),
            ]);

        // ===================== STATUS SELURUH NODE =====================
        // Device punya relasi many-to-many ke Group lewat tabel group_device.
        // Sekalian dihitung "pemakai"-nya di sini: kalau device terikat ke Group -> tampilkan
        // nama kelompok; kalau tidak (dipakai perorangan) -> tampilkan nama mahasiswa dari
        // sesi praktikum terakhir yang memakai device ini.
        $devices = Device::with('groups')->get()->map(function ($device) {
            $lastSession = PraktikumSession::whereHas('devices', function ($q) use ($device) {
                    $q->where('devices.id', $device->id);
                })
                ->with(['user', 'classroom.lecturer'])
                ->latest('started_at')
                ->first();

            $device->lastSession = $lastSession;

            $device->pemakaiLabel = $device->groups->isNotEmpty()
                ? $device->groups->pluck('nama_kelompok')->implode(', ')
                : ($lastSession->user->name ?? '-');

            return $device;
        });

        // ===================== MONITORING PER DEVICE (ED) =====================
        // Tiap device dapat: status sensor terbaru, grafik suhu 24 jam, dan info pemakai terakhir.
        $deviceMonitoring = $devices->map(function ($device) {

            $sensors = $device->sensors()->with('actuators')->get()->map(function ($sensor) {
                $lastLog = SensorLog::where('device_sensor_id', $sensor->id)
                    ->latest('created_at')
                    ->first();

                $value  = $lastLog->value ?? null;
                $status = 'Normal';

                foreach ($sensor->actuators as $actuator) {
                    if ($value !== null && $actuator->isTriggeredOn($value)) {
                        $status = 'Warning';
                        break;
                    }
                }

                return (object) [
                    'nama_sensor' => $sensor->nama_sensor,
                    'value'       => $value,
                    'satuan'      => $sensor->satuan,
                    'status'      => $status,
                ];
            });

            // ===================== GRAFIK SENSOR =====================

$chart = [
    'labels' => [],
    'values' => [],
];

$firstSensor = $device->sensors()->first();

if ($firstSensor) {

    $logs = SensorLog::where('device_sensor_id', $firstSensor->id)
        ->latest()
        ->take(24)
        ->get()
        ->reverse();

    foreach ($logs as $log) {

        $chart['labels'][] = $log->created_at->format('H:i');

        $chart['values'][] = (float) $log->value;

    }

}

            return (object) [
                'device'      => $device,
                'sensors'     => $sensors,
                'chart'       => $chart,
                'lastSession' => $device->lastSession,
                'chartTitle' => $firstSensor->nama_sensor ?? 'Monitoring Sensor',
                'chartUnit'  => $firstSensor->satuan ?? '',
            ];
        });

        return view('kajur.dashboard', compact(
            'totalData',
            'totalKelompok',
            'totalSensor',
            'totalAktuator',
            'online',
            'offline',
            'warning',
            'critical',
            'dataHariIni',
            'totalKelas',
            'totalKelasAktif',
            'qosAvg',
            'qosLastUpdate',
            'skemaTopologi',
            'skemaJarak',
            'skemaLosNlos',
            'kelasAktifList',
            'devices',
            'deviceMonitoring'
        ));
    }
}