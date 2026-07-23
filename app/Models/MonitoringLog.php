<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringLog extends Model
{
    protected $fillable = [
        'praktikum_session_id', 'device_id', 'packet_id', 'topologi',
        'jarak', 'throughput', 'delay', 'jitter', 'packet_loss',
        'kondisi', 'readings',
    ];

    protected $casts = ['readings' => 'array'];

    public function session() { return $this->belongsTo(PraktikumSession::class, 'praktikum_session_id'); }
    public function device()  { return $this->belongsTo(Device::class); }

    public function getDelayDisplayAttribute()
    {
        return max(0, $this->delay - 5);
    }
}