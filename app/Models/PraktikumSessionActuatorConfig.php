<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PraktikumSessionActuatorConfig extends Model
{
    protected $fillable = [
        'praktikum_session_id', 'device_actuator_id',
        'kondisi_on_operator', 'kondisi_on_value',
        'kondisi_off_operator', 'kondisi_off_value',
    ];

    public function session()  { return $this->belongsTo(PraktikumSession::class, 'praktikum_session_id'); }
    public function actuator() { return $this->belongsTo(DeviceActuator::class, 'device_actuator_id'); }

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