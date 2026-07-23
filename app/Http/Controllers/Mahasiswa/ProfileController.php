<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $classroom = $user
            ->joinedClassrooms()
            ->with('lecturer')
            ->first();

        return view(
            'mahasiswa.profile.index',
            compact('user','classroom')
        );
    }

    public function joinClass(Request $request)
    {
        $request->validate([
            'passcode'=>'required'
        ]);

        $classroom = Classroom::where(
            'passcode',
            strtoupper($request->passcode)
        )->first();

        if(!$classroom){

            return back()->withErrors([
                'passcode'=>'Kode kelas tidak ditemukan.'
            ]);
        }

        auth()->user()
            ->joinedClassrooms()
            ->syncWithoutDetaching($classroom->id);

        return back()->with(
            'success',
            'Berhasil bergabung ke kelas.'
        );
    }
}