<?php

// app/Http/Controllers/RoleController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:role.lihat')->only('index');
    //     $this->middleware('permission:role.tambah')->only(['create', 'store']);
    //     $this->middleware('permission:role.edit')->only(['edit', 'update']);
    //     $this->middleware('permission:role.hapus')->only('destroy');
    // }
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0]; // Kelompokkan berdasarkan prefix seperti 'product.create'
        });

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit(Role $role)
{
    $permissions = Permission::all()->groupBy(function ($perm) {
        return explode('.', $perm->name)[0]; // Kelompokkan berdasarkan prefix fitur
    });

    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
}


    public function update(Request $request, Role $role)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'permissions' => 'array',
    ]);

    $role->update([
        'name' => $request->name,
    ]);

    $role->syncPermissions($request->permissions ?? []);

    return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui');
}
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return back()->with('success', 'Role berhasil dihapus!');
    }
}
