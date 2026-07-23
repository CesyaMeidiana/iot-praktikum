<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'admin.classrooms.index',
            compact('classrooms')
        );
    }

    public function create()
{
    $lecturers = User::role('Dosen')
        ->orderBy('name')
        ->get();

    return view(
        'admin.classrooms.create',
        compact('lecturers')
    );
}

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'academic_year' => 'required',
        'class_name' => 'required',
        'lecturer_id' => 'required|exists:users,id',
    ]);

    Classroom::create([

        'name' => $request->name,

        'academic_year' => $request->academic_year,

        'class_name' => $request->class_name,

        'lecturer_id' => $request->lecturer_id,

        'passcode' => strtoupper(Str::random(6)),

        'status' => true,

    ]);

    return redirect()
        ->route('classrooms.index')
        ->with('success', 'Kelas berhasil ditambahkan.');
}

    public function show(Classroom $classroom)
{
    $classroom->load([
        'lecturer',
        'students',
        'groups.members',
    ]);

    return view(
        'admin.classrooms.show',
        compact('classroom')
    );
}

    public function edit(Classroom $classroom)
{
    $lecturers = User::role('Dosen')
        ->orderBy('name')
        ->get();

    return view(
        'admin.classrooms.edit',
        compact('classroom', 'lecturers')
    );
}

    public function update(Request $request, Classroom $classroom)
{
    $request->validate([
        'name' => 'required|max:255',
        'academic_year' => 'required',
        'class_name' => 'required',
        'lecturer_id' => 'required',
        'status' => 'required',
    ]);

    $classroom->update([

        'name' => $request->name,

        'academic_year' => $request->academic_year,

        'class_name' => $request->class_name,

        'lecturer_id' => $request->lecturer_id,

        'status' => $request->status,

    ]);

    return redirect()
        ->route('classrooms.index')
        ->with('success', 'Kelas berhasil diperbarui.');
}

    public function destroy(Classroom $classroom)
{
    $classroom->students()->detach();

    foreach ($classroom->groups as $group) {

        $group->members()->detach();

    }

    $classroom->groups()->delete();

    $classroom->delete();

    return redirect()
        ->route('classrooms.index')
        ->with('success', 'Kelas berhasil dihapus.');
}
}