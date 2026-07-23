<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'lecturer_id',
        'name',
        'academic_year',
        'class_name',
        'passcode',
        'status',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'classroom_student',
            'classroom_id',
            'student_id'
        );
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}