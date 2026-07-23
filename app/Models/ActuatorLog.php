<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActuatorLog extends Model
{
    protected $fillable = [
        'praktikum_session_id',
        'device_actuator_id',
        'status',
    ];

    public function session()
    {
        return $this->belongsTo(
            PraktikumSession::class,
            'praktikum_session_id'
        );
    }

    public function actuator()
    {
        return $this->belongsTo(
            DeviceActuator::class,
            'device_actuator_id'
        );
    }
}