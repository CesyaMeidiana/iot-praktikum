<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceActuator extends Model
{
    protected $fillable = [
    'device_sensor_id',
    'nama_aktuator',
    'kondisi_on_operator', 'kondisi_on_value',
    'kondisi_off_operator', 'kondisi_off_value',
];

    public function sensor()
    {
        return $this->belongsTo(DeviceSensor::class,'device_sensor_id');
    }

    public function isTriggeredOn($value): bool
{
    if (!$this->kondisi_on_operator || $this->kondisi_on_value === null) return false;
    return match ($this->kondisi_on_operator) {
        '>' => $value > $this->kondisi_on_value,
        '>=' => $value >= $this->kondisi_on_value,
        '<' => $value < $this->kondisi_on_value,
        '<=' => $value <= $this->kondisi_on_value,
        '=' => (float) $value == $this->kondisi_on_value,
    };
}
}