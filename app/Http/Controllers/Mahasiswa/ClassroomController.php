<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classroom = auth()->user()
            ->joinedClassrooms()
            ->first();

        return view(
            'mahasiswa.classroom.index',
            compact('classroom')
        );
    }

    public function join(Request $request)
    {
        $request->validate([
            'passcode'=>'required'
        ]);

        $classroom = Classroom::where(
            'passcode',
            strtoupper($request->passcode)
        )->first();

        if(!$classroom){

            return back()->with(
                'error',
                'Passcode tidak ditemukan.'
            );

        }

        auth()->user()
            ->joinedClassrooms()
            ->syncWithoutDetaching($classroom->id);

        return redirect()
            ->route('mahasiswa.classroom');
    }
}