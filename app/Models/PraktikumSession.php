<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MonitoringLog;

class PraktikumSession extends Model
{
    protected $fillable = [
    'user_id',
    'praktikum_id',
    'classroom_id',
    'topology',
    'scenario',
    'distance',
    'status',
    'started_at',
    'finished_at',
];

protected $casts = [
    'started_at'  => 'datetime',
    'finished_at' => 'datetime',
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function devices()
{
    return $this->belongsToMany(
        Device::class,
        'praktikum_session_devices'
    );
}

public function sensorLogs()
{
    return $this->hasMany(SensorLog::class);
}

public function actuatorLogs()
{
    return $this->hasMany(ActuatorLog::class);
}

public function qosLogs()
{
    return $this->hasMany(QosLog::class);
}

public function getQosLogs()
{
    // Filter LANGSUNG by praktikum_session_id (bukan nebak lewat
    // device_id + rentang waktu lagi), biar gak ada QoS punya sesi
    // lain yang nyasar ke sini gara-gara device fisiknya dipakai
    // bergantian sama mahasiswa lain di rentang waktu yang numpuk.
    //
    // Catatan: ini cuma bakal nangkep QoS yang direkam SETELAH fix ini
    // dipasang. Data QoS lama (sebelum kolom praktikum_session_id ada)
    // tetap null session-nya dan gak akan muncul lagi di sesi manapun.
    return $this->qosLogs()->get();
}

public function classroom()
{
    return $this->belongsTo(Classroom::class);
}

public function getDurasiAttribute()
{
    if (!$this->started_at) {
        return '-';
    }

    $end = $this->finished_at ?? now();
    $detik = $this->started_at->diffInSeconds($end);

    $menit = floor($detik / 60);
    $sisaDetik = $detik % 60;

    return "{$menit} menit {$sisaDetik} detik";
}

public function getJumlahDataAttribute()
{
    return $this->sensorLogs->pluck('created_at')->unique()->count();
}

public function monitoringLogs()
{
    return $this->hasMany(MonitoringLog::class, 'praktikum_session_id');
}

public function getWideTableData(): array
{
    $logs = $this->monitoringLogs()
        ->with('device')                 // ⬅️ tambahan: biar $log->device kebaca
        ->orderBy('created_at')
        ->orderBy('packet_id')           // ⬅️ tambahan: tie-breaker kalau created_at sama persis
        ->get();

    $sensorColumns = collect();
    $actuatorColumns = collect();

    foreach ($logs as $log) {
        $sensorColumns = $sensorColumns->merge(array_keys($log->readings['sensor'] ?? []));
        $actuatorColumns = $actuatorColumns->merge(array_keys($log->readings['aktuator'] ?? []));
    }

    $sensorColumns = $sensorColumns->unique()->values();
    $actuatorColumns = $actuatorColumns->unique()->values();

    $rows = $logs->map(fn ($log) => [
        'timestamp'  => $log->created_at,
        'node'       => $log->device->node ?? '-',        // ⬅️ tambahan
        'device'     => $log->device->nama_device ?? '-', // ⬅️ tambahan
        'packet'     => $log->packet_id,
        'sensor'     => $log->readings['sensor'] ?? [],
        'aktuator'   => $log->readings['aktuator'] ?? [],
        'delay'      => $log->delay_display,
        'jitter'     => $log->jitter,
        'throughput' => $log->throughput,
        'loss'       => $log->packet_loss,
        'kondisi'    => $log->kondisi,
    ]);

    return compact('sensorColumns', 'actuatorColumns', 'rows');
}
}