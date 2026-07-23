<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with([
            'lecturer',
            'students',
            'groups'
        ])->latest()->paginate(10);

        return view(
            'kajur.classrooms.index',
            compact('classrooms')
        );
    }

    public function show(Classroom $classroom)
    {
        $classroom->load([
            'lecturer',
            'students',
            'groups.members',
        ]);

        return view(
            'kajur.classrooms.show',
            compact('classroom')
        );
    }
}