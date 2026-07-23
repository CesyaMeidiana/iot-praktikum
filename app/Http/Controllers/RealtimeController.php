<?php

namespace App\Http\Controllers;

use App\Models\ActuatorLog;
use App\Models\Device;
use App\Models\MonitoringLog;
use App\Models\PraktikumSession;
use App\Models\QosLog;
use App\Models\SensorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\DeviceSensor;
use App\Models\DeviceActuator;

class RealtimeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ==============================================================
        // SCOPING DEVICE PER USER
        // - Mahasiswa: cuma device yang terhubung ke praktikum session
        //   dia sendiri yang statusnya "running". Kalau gak ada sesi
        //   yang lagi jalan, dia gak dapet device sama sekali (kosong).
        //   Ini nyegah dashboard mahasiswa A ketiban data device yang
        //   lagi dipakai mahasiswa B buat praktikum lain.
        // - Role lain (Admin/Dosen/Kajur): tetap lihat semua device,
        //   karena mereka emang butuh pantau semuanya.
        // ==============================================================
        if ($user && $user->hasRole('Mahasiswa')) {

            $session = PraktikumSession::where('user_id', $user->id)
                ->where('status', 'running')
                ->latest()
                ->first();

            if (! $session) {
                // Gak ada sesi yang lagi jalan -> fallback ke sesi TERAKHIR
                // yang pernah dia jalanin (apapun statusnya), biar monitoring
                // tetap nampilin data terakhir (last known), bukan kosong total.
                $session = PraktikumSession::where('user_id', $user->id)
                    ->latest()
                    ->first();
            }

            if ($session) {
                $deviceIds = $session->devices()->pluck('devices.id');
                $devices = Device::with(['sensors', 'sensors.actuators'])
                    ->whereIn('id', $deviceIds)
                    ->get();
            } else {
                // User belum pernah praktikum sama sekali -> memang kosong.
                $devices = collect();
            }

        } else {
            // Admin / Dosen / Kajur: lihat semua device.
            $devices = Device::with([
                'sensors',
                'sensors.actuators'
            ])->get();
        }

        $onlineThreshold = now()->subSeconds(15);

        $deviceData = $devices->map(function ($device) use ($onlineThreshold) {

            $device->is_online = SensorLog::whereHas('sensor', function ($q) use ($device) {
                    $q->where('device_id', $device->id);
                })
                ->where('created_at', '>=', $onlineThreshold)
                ->exists();

            $latestSensors = SensorLog::with('sensor')
                ->whereHas('sensor', function ($q) use ($device) {
                    $q->where('device_id', $device->id);
                })
                ->latest()
                ->get()
                ->unique('device_sensor_id')
                ->values();

            $latestActuators = ActuatorLog::with('actuator')
                ->whereHas('actuator.sensor', function ($q) use ($device) {
                    $q->where('device_id', $device->id);
                })
                ->latest()
                ->get()
                ->unique('device_actuator_id')
                ->values();

            return [

                'id' => $device->id,

                'node' => $device->node,

                'nama_device' => $device->nama_device,

                'online' => $device->is_online,

                'sensor' => $latestSensors->map(function ($log) {

                    return [

                        'id' => $log->device_sensor_id,

                        'nama' => $log->sensor->nama_sensor,

                        'parameter' => $log->sensor->parameter,

                        'value' => $log->value,

                        'satuan' => $log->sensor->satuan,

                        'updated_at' => optional($log->created_at)->format('H:i:s'),

                    ];

                })->values(),

                'actuator' => $latestActuators->map(function ($log) {

                    return [

                        'id' => $log->device_actuator_id,

                        'nama' => $log->actuator->nama_aktuator,

                        'status' => (bool)$log->status,

                        'updated_at' => optional($log->created_at)->format('H:i:s'),

                    ];

                })->values(),

            ];

        });

        // ==============================================================
        // QoS juga di-scope: kalau mahasiswa gak punya sesi aktif,
        // biarin QoS-nya nol semua, jangan narik rata-rata QoS global
        // punya semua orang.
        // ==============================================================
        $deviceIdsForQos = $devices->pluck('id');

        if ($user && $user->hasRole('Mahasiswa')) {
            $qosQuery = QosLog::whereIn('device_id', $deviceIdsForQos);
        } else {
            $qosQuery = QosLog::query();
        }

        $latestQos = (clone $qosQuery)->latest()->first();

        $qos = [

            'throughput' => round((clone $qosQuery)->avg('throughput') ?? 0, 2),

            'delay' => round((clone $qosQuery)->avg(DB::raw('GREATEST(delay-5000,0)')) ?? 0, 2),

            'jitter' => round((clone $qosQuery)->avg(DB::raw('GREATEST(jitter-5000,0)')) ?? 0, 2),

            'packet_loss' => round((clone $qosQuery)->avg('packet_loss') ?? 0, 2),

            'latest' => $latestQos,

        ];

        $totalData     = SensorLog::whereIn('device_sensor_id', DeviceSensor::whereIn('device_id', $deviceIdsForQos)->pluck('id'))->count();
        $dataHariIni   = SensorLog::whereDate('created_at', today())
            ->whereIn('device_sensor_id', DeviceSensor::whereIn('device_id', $deviceIdsForQos)->pluck('id'))
            ->count();
        $totalSensor   = DeviceSensor::whereIn('device_id', $deviceIdsForQos)->count();
        $totalAktuator = DeviceActuator::whereHas('sensor', function ($q) use ($deviceIdsForQos) {
            $q->whereIn('device_id', $deviceIdsForQos);
        })->count();
        $totalKelompok = Group::count();

        // Ambil monitoring logs dari sesi yang sedang running milik semua user
        $activeSessions = \App\Models\PraktikumSession::where('status', 'running')
            ->pluck('id');

        $monitoringLogs = \App\Models\MonitoringLog::whereIn('praktikum_session_id', $activeSessions)
    ->latest()
    ->take(200)
    ->get(['id','praktikum_session_id','packet_id','created_at',
           'delay','jitter','throughput','packet_loss','readings']);

        return response()->json([

            'server_time' => now()->format('Y-m-d H:i:s'),

            'device_online' => $deviceData->where('online', true)->count(),

            'total_device' => $deviceData->count(),

            'total_data'     => $totalData,
            'data_hari_ini'  => $dataHariIni,
            'total_sensor'   => $totalSensor,
            'total_aktuator' => $totalAktuator,
            'total_kelompok' => $totalKelompok,

            'devices' => $deviceData->values(),

            'sensor_logs' => SensorLog::whereIn('device_sensor_id', DeviceSensor::whereIn('device_id', $deviceIdsForQos)->pluck('id'))
                ->latest()
                ->take(100)
                ->get(),

            'monitoring_logs' => MonitoringLog::whereIn('device_id', $deviceIdsForQos)
                ->latest()
                ->take(100)
                ->get(),

            'qos' => $qos,

            'monitoring_logs' => $monitoringLogs,

        ]);
    }
}