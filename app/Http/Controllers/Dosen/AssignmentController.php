<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Device;
use App\Notifications\AssignmentCreated;
use App\Notifications\AdminAssignmentCreated;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::where(
            'lecturer_id',
            auth()->id()
        )->latest()->get();

        return view(
            'dosen.tugas.index',
            compact('assignments')
        );
    }

    public function create()
{
    $classrooms = Classroom::where(
        'lecturer_id',
        auth()->id()
    )->get();

    $devices = Device::with([
        'sensors.actuators'
    ])->get();

    return view(
        'dosen.tugas.create',
        compact(
            'classrooms',
            'devices'
        )
    );
}

    public function groups($classroom)
{
    $groups = Group::where(
        'classroom_id',
        $classroom
    )->get();

    return response()->json($groups);
}

public function store(Request $request)
{
    $request->validate([

        'classroom_id' => 'required',

        'title' => 'required',

        'description' => 'nullable',

        'target' => 'required',

        'deadline' => 'required',

    ]);

    $attachment = null;

    if($request->hasFile('attachment')){

        $attachment = $request
            ->file('attachment')
            ->store('assignments','public');

    }

    $assignment = Assignment::create([

        'lecturer_id' => auth()->id(),

        'classroom_id' => $request->classroom_id,

        'title' => $request->title,

        'description' => $request->description,

        'target' => $request->target,

        'deadline' => $request->deadline,

        'attachment' => $attachment,

        'topologies' => $request->topologies,

        'scenarios' => $request->scenarios,

        'distances' => $request->distances,

    ]);

    if($request->target=="group"){
        $assignment->groups()->sync(
            $request->groups
        );
    }

    // Notify mahasiswa di kelas ini (catatan: sementara notify semua mahasiswa
    // di classroom, belum dibedain per-kelompok kalau target="group")
    Notification::send($assignment->classroom->students, new AssignmentCreated($assignment));

    // Notify semua admin
    Notification::send(User::role('Admin')->get(), new AdminAssignmentCreated($assignment));

    return redirect()
        ->route('dosen.tugas.index')
        ->with('success','Tugas berhasil dibuat.');

}

public function show(Assignment $tuga)
{
    $tuga->load('classroom');

    return view('dosen.tugas.show', [
        'assignment' => $tuga
    ]);
}

public function edit(Assignment $tuga)
{
    $classrooms = Classroom::where(
        'lecturer_id',
        auth()->id()
    )->get();

    $devices = Device::with([
        'sensors.actuators'
    ])->get();

    return view(
        'dosen.tugas.edit',
        [
            'assignment' => $tuga,
            'classrooms' => $classrooms,
            'devices' => $devices,
        ]
    );
}

public function update(Request $request, Assignment $tuga)
{
    $attachment = $tuga->attachment;

    if($request->hasFile('attachment')){

        if($attachment){

            Storage::disk('public')->delete($attachment);

        }

        $attachment = $request
            ->file('attachment')
            ->store('assignments','public');

    }

    $tuga->update([

        'classroom_id'=>$request->classroom_id,

        'title'=>$request->title,

        'description'=>$request->description,

        'target'=>$request->target,

        'deadline'=>$request->deadline,

        'attachment'=>$attachment,

        'topologies'=>$request->topologies,

        'scenarios'=>$request->scenarios,

        'distances'=>$request->distances,

    ]);

    $tuga->groups()->sync(
        $request->groups ?? []
    );

    return redirect()
        ->route('dosen.tugas.index')
        ->with('success','Tugas berhasil diubah.');
}

public function destroy(Assignment $tuga)
{
    if($tuga->attachment){

        Storage::disk('public')->delete(
            $tuga->attachment
        );

    }

    $tuga->groups()->detach();

    $tuga->delete();

    return redirect()
        ->route('dosen.tugas.index')
        ->with('success','Tugas berhasil dihapus.');
}

}