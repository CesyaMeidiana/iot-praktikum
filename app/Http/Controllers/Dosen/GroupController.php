<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Classroom $classroom)
    {
        abort_if(
            $classroom->lecturer_id != auth()->id(),
            403
        );

        $students = $classroom->students;

        return view(
            'dosen.groups.create',
            compact('classroom', 'students')
        );
    }

    public function store(Request $request, Classroom $classroom)
    {
        abort_if(
            $classroom->lecturer_id != auth()->id(),
            403
        );

        $request->validate([
            'nama_kelompok' => 'required|max:255',
            'members' => 'required|array|min:1',
        ]);

        $group = Group::create([
            'classroom_id' => $classroom->id,
            'nama_kelompok' => $request->nama_kelompok,
        ]);

        $group->members()->sync($request->members);

        return redirect()
            ->route('dosen.classrooms.show', $classroom)
            ->with('success', 'Kelompok berhasil dibuat.');
    }

    public function show(Group $group)
    {
        abort_if(
            $group->classroom->lecturer_id != auth()->id(),
            403
        );

        $group->load([
            'members',
            'classroom'
        ]);

        return view(
            'dosen.groups.show',
            compact('group')
        );
    }
}