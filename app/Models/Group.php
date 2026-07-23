<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'classroom_id',
        'nama_kelompok',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'group_members',
            'group_id',
            'student_id'
        );
    }
}