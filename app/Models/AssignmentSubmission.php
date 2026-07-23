<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'group_id',
        'file',
        'submitted_at',
        'score',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function show(Assignment $tuga)
{
    $tuga->load([
        'classroom',
        'groups',
        'submissions.student',
        'submissions.group'
    ]);

    return view('dosen.tugas.show', [
        'assignment' => $tuga
    ]);
}
}