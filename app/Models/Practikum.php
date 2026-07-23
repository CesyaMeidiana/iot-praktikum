<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practikum extends Model
{
    protected $fillable = [

        'classroom_id',

        'created_by',

        'title',

        'description',

        'deadline',

        'type',

        'status',

    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}