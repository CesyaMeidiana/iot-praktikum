<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Assignment extends Model
{
    protected $fillable = [

        'lecturer_id',

        'classroom_id',

        'title',

        'description',

        'target',

        'deadline',

        'attachment',

        'topologies',

        'scenarios',

        'distances',

    ];

    protected $casts = [

        'topologies' => 'array',

        'scenarios' => 'array',

        'distances' => 'array',

        'deadline' => 'datetime',

    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class,'lecturer_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'assignment_groups');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}