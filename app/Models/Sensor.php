<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'nama_sensor',
        'parameter',
        'keterangan',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}