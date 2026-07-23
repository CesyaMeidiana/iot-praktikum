<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceSensor extends Model
{
    protected $fillable = [

    'device_id',

    'nama_sensor',

    'parameter',

    'satuan',

    'aktuator',

    'keterangan',

];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function actuators()
    {
        return $this->hasMany(DeviceActuator::class);
    }
}