<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        $fiturs = [
            'products', 'categories', 'transactions', 'users',
            'payment_methods', 'roles', 'laporan', 'dashboard', 'pos'
        ];

        $aksisPerFitur = [
            'products' => ['lihat', 'tambah', 'edit', 'hapus'],
            'categories' => ['lihat', 'tambah', 'edit', 'hapus'],
            'transactions' => ['lihat', 'tambah', 'edit', 'hapus'],
            'users' => ['lihat', 'tambah', 'edit', 'hapus'],
            'payment_methods' => ['lihat', 'tambah', 'edit', 'hapus'],
            'roles' => ['lihat', 'tambah', 'edit', 'hapus'],
            'laporan' => ['harian', 'mingguan', 'bulanan','tahunan'],
            'dashboard' => ['lihat'],
            'pos' => ['transaksi']
        ];

        foreach ($aksisPerFitur as $fitur => $aksis) {
            foreach ($aksis as $aksi) {
                Permission::firstOrCreate(['name' => "{$fitur}.{$aksi}"]);
            }
        }

        // Role Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Role Kasir
        $kasirPermissions = Permission::whereIn('name', [
            'transactions.lihat', 'transactions.tambah', 'transactions.edit',
            'products.lihat',
            'pos.transaksi',
        ])->get();

        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $kasir->syncPermissions($kasirPermissions);
    }
}
