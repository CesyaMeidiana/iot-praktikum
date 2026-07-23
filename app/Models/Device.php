<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'node',
        'nama_device',
        'keterangan',
    ];

    /**
     * Satu Master Device memiliki banyak Sensor
     */
    public function sensors()
    {
        return $this->hasMany(DeviceSensor::class);
    }

    /**
     * Satu Master Device dapat dipakai banyak Kelompok
     */
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'group_device',
            'device_id',
            'group_id'
        );
    }

    public function actuators()
{
    return $this->hasManyThrough(
        DeviceActuator::class,
        DeviceSensor::class,
        'device_id',
        'device_sensor_id',
        'id',
        'id'
    );
}
}