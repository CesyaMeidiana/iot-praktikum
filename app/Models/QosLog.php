<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QosLog extends Model
{
    protected $fillable = [
        'praktikum_session_id',
        'device_id',
        'packet',
        'delay',
        'jitter',
        'throughput',
        'packet_loss',
    ];

    public function session()
    {
        return $this->belongsTo(
            PraktikumSession::class,
            'praktikum_session_id'
        );
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function getDelayDisplayAttribute()
{
    return max(0, $this->delay - 5);
}
}