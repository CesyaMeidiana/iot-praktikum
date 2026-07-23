<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = [
        'praktikum_session_id',
        'device_sensor_id',
        'value',
    ];

    public function session()
    {
        return $this->belongsTo(
            PraktikumSession::class,
            'praktikum_session_id'
        );
    }

    public function sensor()
    {
        return $this->belongsTo(
            DeviceSensor::class,
            'device_sensor_id'
        );
    }
}