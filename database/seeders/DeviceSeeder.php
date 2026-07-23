<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceSensor;
use App\Models\DeviceActuator;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // ED1
        // ============================
        $ed1 = Device::firstOrCreate(
            ['node' => 1],
            [
                'nama_device' => 'ED1',
                'keterangan'  => 'End Device 1 - Suhu, Kelembaban, Cahaya',
            ]
        );

        $suhu = DeviceSensor::firstOrCreate(
            ['device_id' => $ed1->id, 'parameter' => 'temperature'],
            [
                'nama_sensor' => 'DHT22 - Suhu',
                'satuan'      => 'C',
            ]
        );

        $lembab = DeviceSensor::firstOrCreate(
            ['device_id' => $ed1->id, 'parameter' => 'humidity'],
            [
                'nama_sensor' => 'DHT22 - Kelembaban',
                'satuan'      => '%',
            ]
        );

        $ldr = DeviceSensor::firstOrCreate(
            ['device_id' => $ed1->id, 'parameter' => 'ldr'],
            [
                'nama_sensor' => 'LDR - Cahaya',
                'satuan'      => 'lux',
            ]
        );

        // Aktuator nempel ke sensor terkait
        DeviceActuator::firstOrCreate(
            ['device_sensor_id' => $suhu->id, 'nama_aktuator' => 'Fan'],
            [
                'kondisi_on'  => 'Suhu tinggi',
                'kondisi_off' => 'Suhu normal',
            ]
        );

        DeviceActuator::firstOrCreate(
            ['device_sensor_id' => $ldr->id, 'nama_aktuator' => 'LED'],
            [
                'kondisi_on'  => 'Cahaya rendah',
                'kondisi_off' => 'Cahaya cukup',
            ]
        );
    }
}