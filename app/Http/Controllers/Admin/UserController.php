<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
        {
            $query = User::with('roles');

            // Search
            if ($request->filled('search')) {

                $query->where(function ($q) use ($request) {

                    $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nim_nip', 'like', '%' . $request->search . '%');

                });

            }

            // Filter Role
            if ($request->filled('role')) {

                $query->role($request->role);

            }

            // Filter Status
            if ($request->filled('status')) {

                $query->where('status', $request->status);

            }

            $users = $query->paginate(10);

            $roles = Role::all();

            return view('admin.users.index', compact('users', 'roles'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nim_nip' => 'required|string|min:10|max:20|unique:users,nim_nip',
                'angkatan' => 'required',
                'kelas' => 'required',
                'phone' => 'nullable|string|max:20',
                'role' => 'required',
                'status' => 'required|boolean',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nim_nip' => $request->nim_nip,
                'angkatan' => $request->angkatan,
                'kelas' => $request->kelas,
                'phone' => $request->phone,
                'status' => $request->status,
                'password' => Hash::make('12345678'),
            ]);

            $user->assignRole($request->role);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan.');
        }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
        {
            $request->validate([
                'name'=>'required',
                'email'=>'required|email',
                'nim_nip'=>'required',
                'angkatan'=>'required',
                'kelas'=>'required',
                'status'=>'required',
                'role'=>'required'
            ]);

            $user->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'nim_nip'=>$request->nim_nip,
                'angkatan'=>$request->angkatan,
                'kelas'=>$request->kelas,
                'status'=>$request->status
            ]);

            $user->syncRoles([$request->role]);

            return redirect()->route('users.index')
                    ->with('success','User berhasil diupdate');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
        {
            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success','User berhasil dihapus.');
        }
}
