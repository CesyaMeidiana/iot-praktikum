<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $groups = Group::with(['dosen', 'members'])
        ->latest()
        ->paginate(10);

    return view('admin.groups.index', compact('groups'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $dosens = User::role('Dosen')->orderBy('name')->get();

    $mahasiswas = User::role('Mahasiswa')
        ->orderBy('name')
        ->get();

    return view('admin.groups.create', compact(
        'dosens',
        'mahasiswas'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_kelompok' => 'required',
        'angkatan' => 'required',
        'kelas' => 'required',
        'dosen_id' => 'required',
    ]);

    $group = Group::create([

    'nama_kelompok' => $request->nama_kelompok,

    'angkatan' => $request->angkatan,

    'kelas' => $request->kelas,

    'join_code' =>
        strtoupper(
            str_replace(' ', '', $request->kelas)
        )
        . "-"
        . substr($request->angkatan, 2)
        . "-"
        . strtoupper(Str::random(4)),

    'dosen_id' => $request->dosen_id,

]);

    $group->members()->sync($request->members ?? []);

    return redirect()
        ->route('groups.index')
        ->with('success', 'Kelompok berhasil ditambahkan');
}

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
{
    $group->load([
        'dosen',
        'members'
    ]);

    return view('admin.groups.show', compact('group'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
{
    $dosens = User::role('Dosen')->get();

    $students = User::role('Mahasiswa')
        ->where('angkatan', $group->angkatan)
        ->where('kelas', $group->kelas)
        ->get();

    $group->load('members');

    return view('admin.groups.edit', compact(
        'group',
        'dosens',
        'students'
    ));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
{
    $request->validate([
        'nama_kelompok' => 'required',
        'angkatan'      => 'required',
        'kelas'         => 'required',
        'dosen_id'      => 'required',
        'members'       => 'required|array',
    ]);

    $group->update([
        'nama_kelompok' => $request->nama_kelompok,
        'angkatan'      => $request->angkatan,
        'kelas'         => $request->kelas,
        'dosen_id'      => $request->dosen_id,
    ]);

    $group->members()->sync($request->members);

    return redirect()
        ->route('groups.index')
        ->with('success', 'Kelompok berhasil diperbarui.');
}

public function destroy(Group $group)
{
    $group->members()->detach();

    $group->delete();

    return redirect()
        ->route('groups.index')
        ->with('success', 'Kelompok berhasil dihapus.');
}
}