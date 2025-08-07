<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.lihat')->only('index');
        $this->middleware('permission:users.tambah')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.hapus')->only('destroy');
    }
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

  
  public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'roles'    => 'required|array', // PERBAIKAN DISINI
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->syncRoles($request->roles); // PASTIKAN INI SESUAI

    return redirect()->route('users.index')->with('success', 'User berhasil ditambah!');
}
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); 
        $userRoles = $user->roles->pluck('name')->toArray(); 

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update roles
        $user->syncRoles($request->roles ?? []);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.create', compact('roles'));
    }
    public function editRole($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    $userRoles = $user->roles->pluck('name')->toArray();

    // Group berdasarkan prefix sebelum titik (contoh: "product.create")
    $permissions = \Spatie\Permission\Models\Permission::all()->groupBy(function ($perm) {
        return explode('.', $perm->name)[0];
    });

    return view('users.edit_role', compact('user', 'roles', 'userRoles', 'permissions'));
}

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'roles' => 'array'
        ]);
        $user->syncRoles($request->roles ?? []);
        return redirect()->route('users.index')->with('success', 'Role user berhasil diupdate!');
    }
}
