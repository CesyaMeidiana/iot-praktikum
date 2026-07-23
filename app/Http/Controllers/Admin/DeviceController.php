<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceSensor;
use App\Models\DeviceActuator;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with('sensors.actuators')
            ->latest()
            ->paginate(10);

        return view('admin.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'node' => 'required|numeric|unique:devices,node',
            'nama_sensor'=>'required|array|min:1',
            'parameter'=>'required|array',
            'satuan'=>'required|array',
        ]);

        $device = Device::create([
            'node' => $request->node,
            'nama_device' => $request->nama_device,
            'keterangan' => $request->keterangan,
        ]);

        if ($request->nama_sensor) {

            foreach ($request->nama_sensor as $i => $sensor) {

                if ($sensor == '') continue;

                $deviceSensor = DeviceSensor::create([
                    'device_id'   => $device->id,
                    'nama_sensor' => $sensor,
                    'parameter'   => $request->parameter[$i],
                    'satuan'      => $request->satuan[$i],
                    'keterangan'  => $request->sensor_keterangan[$i] ?? null,
                ]);

        if(isset($request->aktuator_nama[$i])){

            foreach($request->aktuator_nama[$i] as $j=>$aktuator){

                if(empty($aktuator)) continue;

                DeviceActuator::create([

                    'device_sensor_id'=>$deviceSensor->id,

                    'nama_aktuator'=>$aktuator,

                    'kondisi_on'=>$request->aktuator_on[$i][$j] ?? null,

                    'kondisi_off'=>$request->aktuator_off[$i][$j] ?? null,

                ]);

            }

        }
                
            }
        }

        return redirect()
            ->route('devices.index')
            ->with('success', 'Master Device berhasil ditambahkan.');
    }

    public function show(Device $device)
    {
        $device->load('sensors.actuators');

        return view('admin.devices.show', compact('device'));
    }

    public function edit(Device $device)
{
    $device->load('sensors.actuators');

    $existingSensors = $device->sensors->map(function ($sensor) {
        return [
            'nama_sensor' => $sensor->nama_sensor,
            'parameter'   => $sensor->parameter,
            'satuan'      => $sensor->satuan,
            'keterangan'  => $sensor->keterangan,
            'actuators' => $sensor->actuators->map(function ($a) {
                return [
                    'nama_aktuator' => $a->nama_aktuator,
                    'kondisi_on'    => $a->kondisi_on,
                    'kondisi_off'   => $a->kondisi_off,
                ];
            }),
        ];
    });

    return view('admin.devices.edit', compact('device', 'existingSensors'));
}

    public function update(Request $request, Device $device)
{
    $request->validate([
        'node' => 'required|numeric|unique:devices,node,' . $device->id,
        'nama_device' => 'required',
    ]);

    $device->update([
        'node' => $request->node,
        'nama_device' => $request->nama_device,
        'keterangan' => $request->keterangan,
    ]);

    // Hapus aktuator dulu sebelum sensor dihapus
    foreach ($device->sensors as $oldSensor) {
        $oldSensor->actuators()->delete();
    }
    $device->sensors()->delete();

    if ($request->nama_sensor) {

        foreach ($request->nama_sensor as $i => $sensor) {

            if ($sensor == '') continue;

            $deviceSensor = DeviceSensor::create([
                'device_id'   => $device->id,
                'nama_sensor' => $sensor,
                'parameter'   => $request->parameter[$i],
                'satuan'      => $request->satuan[$i],
                'keterangan'  => $request->sensor_keterangan[$i] ?? null,
            ]);

            if (isset($request->aktuator_nama[$i])) {

                foreach ($request->aktuator_nama[$i] as $j => $aktuator) {

                    if (empty($aktuator)) continue;

                    DeviceActuator::create([
                        'device_sensor_id' => $deviceSensor->id,
                        'nama_aktuator'    => $aktuator,
                        'kondisi_on'       => $request->aktuator_on[$i][$j] ?? null,
                        'kondisi_off'      => $request->aktuator_off[$i][$j] ?? null,
                    ]);
                }
            }
        }
    }

    return redirect()
        ->route('devices.index')
        ->with('success', 'Master Device berhasil diperbarui.');
}

    public function destroy(Device $device)
    {
        $device->sensors()->delete();

        $device->delete();

        return redirect()
            ->route('devices.index')
            ->with('success', 'Master Device berhasil dihapus.');
    }
}