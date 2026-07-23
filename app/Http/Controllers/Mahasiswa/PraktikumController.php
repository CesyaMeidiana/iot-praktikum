<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PraktikumSession;
use App\Models\SensorLog;
use App\Models\ActuatorLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Device;
use App\Models\Classroom;
use App\Models\PraktikumSessionActuatorConfig;
use App\Models\QosLog;
use Carbon\Carbon;
use App\Notifications\PraktikumSessionStarted;
use App\Notifications\AdminPraktikumStarted;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\DosenPraktikumStarted;

class PraktikumController extends Controller
{
    public function index()
    {
        $sessions = PraktikumSession::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'mahasiswa.praktikum.index',
            compact('sessions')
        );
    }

    public function create()
    {
        $devices = Device::with([
            'sensors.actuators'
        ])->get();

        $classrooms = Classroom::whereHas('students', function ($query) {
            $query->where('student_id', Auth::id());
        })->get();

        return view(
            'mahasiswa.praktikum.create',
            compact('devices', 'classrooms')
        );
    }

    public function store(Request $request)
    {
        $running = PraktikumSession::where('user_id', Auth::id())
            ->where('status', 'running')
            ->first();

        if ($running) {
            return redirect()->route(
                'mahasiswa.praktikum.show',
                $running->id
            );
        }

        // ============================
        // CEK: apakah device yang dipilih lagi dipakai orang lain?
        // ============================
        $devicesDipilih = (array) $request->devices;

        $devicePakai = PraktikumSession::where('status', 'running')
            ->whereHas('devices', function ($query) use ($devicesDipilih) {
                $query->whereIn('devices.id', $devicesDipilih);
            })
            ->with('devices')
            ->first();

        if ($devicePakai) {
            $namaDevice = $devicePakai->devices->pluck('nama_device')->implode(', ');

            return redirect()->back()->with(
                'error',
                "Device {$namaDevice} sedang dipakai mahasiswa lain, coba lagi nanti."
            );
        }

        $session = PraktikumSession::create([

            'user_id' => Auth::id(),

            'praktikum_id' => null,

            'classroom_id' => $request->classroom_id,

            'device_id' => $request->device_id,

            'topology' => $request->topology,

            'scenario' => $request->scenario,

            'distance' => $request->distance,

            'status' => 'running',

            'started_at' => now(),

        ]);

        $session->devices()->sync(
            (array) $request->devices
        );

        Auth::user()->notify(new PraktikumSessionStarted($session));
        Notification::send(User::role('Admin')->get(), new AdminPraktikumStarted($session));

        if ($session->classroom && $session->classroom->lecturer) {
            $session->classroom->lecturer->notify(new DosenPraktikumStarted($session));
        }

        foreach ($request->input('actuator_config', []) as $actuatorId => $cfg) {
    if (empty($cfg['on_operator']) || $cfg['on_value'] === null || $cfg['on_value'] === '') {
        continue;
    }
    PraktikumSessionActuatorConfig::create([
        'praktikum_session_id' => $session->id,
        'device_actuator_id'   => $actuatorId,
        'kondisi_on_operator'  => $cfg['on_operator'],
        'kondisi_on_value'     => $cfg['on_value'],
    ]);
}

        return redirect()->route(
            'mahasiswa.praktikum.show',
            $session->id
        );
    }

    public function finish($id)
    {
        $session = PraktikumSession::with('classroom.lecturer')->findOrFail($id);

        $session->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        \Illuminate\Support\Facades\Notification::send(
            \App\Models\User::role('Admin')->get(),
            new \App\Notifications\AdminPraktikumFinished($session)
        );

        if ($session->classroom && $session->classroom->lecturer) {
            $session->classroom->lecturer->notify(new \App\Notifications\DosenPraktikumFinished($session));
        }

        return redirect()
            ->route('mahasiswa.praktikum.index')
            ->with('success', 'Praktikum berhasil diselesaikan.');
    }

    public function show($id)
{
    $session = PraktikumSession::with([
        'devices',
        'sensorLogs.sensor',
        'actuatorLogs.actuator',
    ])->findOrFail($id);

    // >>> PINDAHIN KE SINI, SEBELUM LOOP <
    $sensorColumns = $session->sensorLogs->map(fn ($l) => $l->sensor->parameter ?? $l->sensor->nama_sensor)->unique()->values();
    $actuatorColumns = $session->actuatorLogs->map(fn ($l) => $l->actuator->nama_aktuator)->unique()->values();

    $qosLogs = $session->getQosLogs();
    $rows = [];

    $logs = \App\Models\MonitoringLog::with('device')
        ->where('praktikum_session_id', $session->id)
        ->orderBy('created_at')
        ->get();

    foreach ($logs as $log) {
        $readings = $log->readings ?? [];

        $sensorData   = collect($readings)->only($sensorColumns->all())->all();
        $actuatorData = collect($readings)->only($actuatorColumns->all())->all();

        $rows[] = [
            'timestamp'  => $log->created_at,
            'packet'     => $log->packet_id,
            'device'     => $log->device->nama_device ?? '-',
            'sensor'     => $sensorData,
            'aktuator'   => $actuatorData,
            'delay'      => $log->delay,
            'jitter'     => $log->jitter,
            'throughput' => $log->throughput,
            'loss'       => $log->packet_loss,
        ];
    }

    $lastLogTime = $session->sensorLogs->max('created_at');
    $isDeviceOnline = $lastLogTime && $lastLogTime->gt(now()->subSeconds(15));

    return view('mahasiswa.praktikum.show', compact(
        'session', 'rows', 'sensorColumns', 'actuatorColumns', 'isDeviceOnline'
    ));
}
    public function active()
    {
        $session = PraktikumSession::where('user_id', auth()->id())
            ->where('status', 'running')
            ->first();

        if (!$session) {
            return redirect()->route('mahasiswa.praktikum.index');
        }

        return redirect()->route('mahasiswa.praktikum.show', $session->id);
    }

    public function download($id)
    {
        $session = PraktikumSession::findOrFail($id);

        $filename = 'praktikum_' . $session->id . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($session) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Praktikum',
                'Topologi',
                'Skema',
                'Jarak',
                'Status',
                'Mulai',
                'Selesai'
            ]);

            fputcsv($file, [
                $session->id,
                $session->topology,
                $session->scenario,
                $session->distance,
                $session->status,
                $session->started_at,
                $session->finished_at
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $session = PraktikumSession::findOrFail($id);

        $session->delete();

        return redirect()
            ->route('mahasiswa.praktikum.index')
            ->with('success', 'Riwayat berhasil dihapus.');
    }

    /**
     * Hapus satu baris data monitoring (gabungan sensor_logs, actuator_logs,
     * qos_logs) berdasarkan timestamp (presisi detik) yang dipilih user
     * di tabel "Data Monitoring".
     */
    public function destroyRow(Request $request, $id)
    {
        $session = PraktikumSession::with('devices')->findOrFail($id);

        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'timestamp' => 'required|date',
        ]);

        $start = Carbon::parse($request->input('timestamp'));
        $end   = $start->copy()->addSecond();

        // sensor_logs & actuator_logs punya kolom praktikum_session_id langsung
        SensorLog::where('praktikum_session_id', $session->id)
            ->whereBetween('created_at', [$start, $end])
            ->delete();

        ActuatorLog::where('praktikum_session_id', $session->id)
            ->whereBetween('created_at', [$start, $end])
            ->delete();

        // qos_logs tidak punya praktikum_session_id, filter lewat device_id
        // milik session ini (sama seperti PraktikumSession::getQosLogs())
        $deviceIds = $session->devices->pluck('id');

        QosLog::whereIn('device_id', $deviceIds)
            ->whereBetween('created_at', [$start, $end])
            ->delete();

        return redirect()
            ->route('mahasiswa.praktikum.show', $session->id)
            ->with('success', 'Data berhasil dihapus.');
    }

    public function downloadPdf($id)
{
    set_time_limit(300); // kasih waktu lebih, 5 menit
    ini_set('memory_limit', '512M');
    
    $session = PraktikumSession::with([
        'user',
        'devices',
        'sensorLogs.sensor',
        'actuatorLogs.actuator',
    ])->findOrFail($id);

    $logs = \App\Models\MonitoringLog::where('praktikum_session_id', $session->id)
        ->orderBy('created_at')
        ->get();

    $rows = [];
    $logs = \App\Models\MonitoringLog::with('device')  // <-- eager load biar gak N+1
    ->where('praktikum_session_id', $session->id)
    ->orderBy('created_at')
    ->get();

foreach ($logs as $log) {
    $readings = $log->readings ?? [];

    // Pisahkan flat readings jadi sensor vs aktuator
    // berdasarkan nama kolom yang udah diketahui dari $sensorColumns/$actuatorColumns
    $sensorData   = collect($readings)->only($sensorColumns->all())->all();
    $actuatorData = collect($readings)->only($actuatorColumns->all())->all();

    $rows[] = [
        'timestamp'  => $log->created_at,
        'packet'     => $log->packet_id,
        'device'     => $log->device->nama_device ?? '-',   // <-- NEW: buat kolom ED
        'sensor'     => $sensorData,
        'aktuator'   => $actuatorData,
        'delay'      => $log->delay,
        'jitter'     => $log->jitter,
        'throughput' => $log->throughput,
        'loss'       => $log->packet_loss,
    ];
}

    $sensorColumns = $session->sensorLogs->map(fn ($l) => $l->sensor->parameter ?? $l->sensor->nama_sensor)->unique()->values();
    $actuatorColumns = $session->actuatorLogs->map(fn ($l) => $l->actuator->nama_aktuator)->unique()->values();

    $pdf = Pdf::loadView(
        'pdf.praktikum',
        compact('session', 'rows', 'sensorColumns', 'actuatorColumns')
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->download(
        'Laporan-Praktikum-' . $session->id . '.pdf'
    );
}
}