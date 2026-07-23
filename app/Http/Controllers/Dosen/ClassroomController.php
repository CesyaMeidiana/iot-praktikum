<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::where(
            'lecturer_id',
            auth()->id()
        )->latest()->get();

        return view(
            'dosen.classrooms.index',
            compact('classrooms')
        );
    }

    public function create()
    {
       return view('dosen.classrooms.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'academic_year' => 'required',
        'class_name' => 'required',
    ]);

    Classroom::create([

        'lecturer_id' => auth()->id(),

        'name' => $request->name,

        'academic_year' => $request->academic_year,

        'class_name' => $request->class_name,

        'passcode' => strtoupper(Str::random(6)),

        'status' => true,

    ]);

    return redirect()
        ->route('dosen.classrooms.index')
        ->with('success', 'Kelas berhasil dibuat.');
}

public function show(Classroom $classroom)
{
    abort_if(
        $classroom->lecturer_id != auth()->id(),
        403
    );

    $classroom->load([
        'students',
        'groups'
    ]);

    return view(
        'dosen.classrooms.show',
        compact('classroom')
    );
}
}