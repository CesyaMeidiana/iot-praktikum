<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PraktikumSession;

class RiwayatController extends Controller
{
    public function index()
{
    $dosen = auth()->user();

    $studentIds = \App\Models\Classroom::where('lecturer_id', $dosen->id)
        ->with('students:id')
        ->get()
        ->pluck('students')
        ->flatten()
        ->pluck('id')
        ->unique();

    $sessions = \App\Models\PraktikumSession::with([
        'user',
        'devices',
        'sensorLogs.sensor',
        'actuatorLogs.actuator',
        'qosLogs.device',
    ])
    ->whereIn('user_id', $studentIds)
    ->latest()
    ->paginate(10);

    return view('dosen.riwayat.index', compact('sessions'));
}

    public function show(PraktikumSession $riwayat)
    {
        $riwayat->load([
            'user',
            'devices',
            'sensorLogs.sensor',
            'actuatorLogs.actuator',
            'qosLogs.device',
        ]);

        return view('dosen.riwayat.show', compact('riwayat'));
    }
}