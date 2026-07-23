<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use App\Notifications\AssignmentSubmitted;
use App\Notifications\AdminAssignmentSubmitted;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\DosenAssignmentSubmitted;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with([
            'classroom',
            'submissions'
        ])->latest()->get();

        return view(
            'mahasiswa.tugas.index',
            compact('assignments')
        );
    }

    public function show(Assignment $assignment)
{
    return view(
        'mahasiswa.tugas.show',
        compact('assignment')
    );
}

    public function submit(Request $request, Assignment $assignment)
{
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,zip|max:20480'
        ]);

        $path = $request
            ->file('file')
            ->store('submissions','public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
            ],
            [
                'file' => $path,
                'submitted_at' => now(),
            ]
        );

        auth()->user()->notify(new AssignmentSubmitted($assignment));
        Notification::send(User::role('Admin')->get(), new AdminAssignmentSubmitted($assignment, auth()->user()));

        $assignment->lecturer->notify(new DosenAssignmentSubmitted($assignment, auth()->user()));

        return redirect()
            ->route('mahasiswa.tugas.index')
            ->with(
                'success',
                'Tugas berhasil dikumpulkan.'
            );
    }
}