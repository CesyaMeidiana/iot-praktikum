<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\PraktikumSession;

class RiwayatController extends Controller
{
    public function index()
    {
        $sessions = PraktikumSession::with([
            'user',
            'devices',
            'sensorLogs.sensor',
            'actuatorLogs.actuator',
            'qosLogs.device',
        ])
        ->latest()
        ->paginate(10);

        return view('kajur.riwayat.index', compact('sessions'));
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

        return view('kajur.riwayat.show', compact('riwayat'));
    }
}