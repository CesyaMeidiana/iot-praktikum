<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\MonitoringLog;
use App\Models\PraktikumSession;

class RiwayatController extends Controller
{
    public function index()
{
    $sessions = PraktikumSession::with([
        'user', 'classroom.lecturer', 'devices', 'sensorLogs.sensor',
    ])->latest()->paginate(10, ['*'], 'sessions_page');

    // Dulu: groupBy('device_id') -> banyak "riwayat" per ED.
    // Sekarang: semua data non-sesi dianggap SATU aliran monitoring,
    // sama seperti data dari coordinator yang gabungan ED1/ED2/ED3.
    $logsLuar = MonitoringLog::whereNull('praktikum_session_id')->get();

    $luarPraktikum = $logsLuar->isNotEmpty()
        ? (object) [
            'jumlah_data'   => $logsLuar->count(),
            'jumlah_node'   => $logsLuar->pluck('device_id')->unique()->count(),
            'pertama'       => $logsLuar->min('created_at'),
            'terakhir'      => $logsLuar->max('created_at'),
            'kondisi'       => optional($logsLuar->sortByDesc('created_at')->first())->kondisi,
        ]
        : null;

    return view('admin.riwayat.index', compact('sessions', 'luarPraktikum'));
}

    public function show(PraktikumSession $riwayat)
{
    $riwayat->load(['user', 'classroom.lecturer', 'devices']);

    $data = $riwayat->getWideTableData();

    return view('admin.riwayat.show', array_merge(
        ['riwayat' => $riwayat],
        $data   // sudah berisi: sensorColumns, actuatorColumns, rows
    ));
}

    /**
     * Detail data monitoring untuk 1 device yang masuk DI LUAR sesi praktikum.
     * Dipakai dari tombol "Detail" di tabel "Data Di Luar Praktikum" pada
     * halaman index riwayat (tidak butuh menu sidebar baru).
     */
    public function showLuarPraktikum()
{
    $logs = MonitoringLog::whereNull('praktikum_session_id')
        ->with('device')
        ->orderBy('created_at')
        ->orderBy('packet_id')
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
        'node'       => $log->device->node ?? '-',
        'device'     => $log->device->nama_device ?? '-',
        'packet'     => $log->packet_id,
        'sensor'     => $log->readings['sensor'] ?? [],
        'aktuator'   => $log->readings['aktuator'] ?? [],
        'delay'      => $log->delay_display,
        'jitter'     => $log->jitter,
        'throughput' => $log->throughput,
        'loss'       => $log->packet_loss,
        'kondisi'    => $log->kondisi,
    ]);

    return view('admin.riwayat.luar', compact('rows', 'sensorColumns', 'actuatorColumns'));
}
public function realtime()
{
    $sessions = PraktikumSession::with([
        'user',
        'classroom.lecturer',
        'devices'
    ])->latest()->get();

    $luar = MonitoringLog::whereNull('praktikum_session_id')
        ->with('device')
        ->latest()
        ->get();

    return response()->json([

        'praktikum' => $sessions->map(function ($s) {

            return [

                'id' => $s->id,

                'tanggal' => $s->created_at->format('d/m/Y H:i'),

                'mahasiswa' => $s->user->name ?? '-',

                'kelas' => $s->classroom->name ?? '-',

                'dosen' => $s->classroom->lecturer->name ?? '-',

                'node' => $s->devices->pluck('nama_device')->implode(', '),

                'scenario' => $s->scenario,

                'durasi' => $s->durasi,

                'jumlah_data' => $s->jumlah_data,

                'detail' => route('admin.riwayat.show',$s),

            ];

        }),

        'luar' => $luar->map(function($m){

            return [

                'waktu' => $m->created_at->format('d/m/Y H:i:s'),

                'node' => $m->device->node ?? '-',

                'packet' => $m->packet_id,

                'sensor' => $m->readings['sensor'] ?? [],

                'aktuator' => $m->readings['aktuator'] ?? [],

                'delay' => $m->delay_display,

                'jitter' => $m->jitter,

                'throughput' => $m->throughput,

                'loss' => $m->packet_loss,

                'kondisi' => $m->kondisi,

            ];

        })

    ]);
}
}