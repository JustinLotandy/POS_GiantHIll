<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        // Daftar fitur & aksi yang ingin kamu generate
        $fiturs = [
            'products', 'categories', 'transactions', 'users'
        ];
        $aksis = [
            'lihat', 'tambah', 'edit', 'hapus'
        ];

        // Generate permission untuk setiap fitur dan aksi
        foreach ($fiturs as $fitur) {
            foreach ($aksis as $aksi) {
                Permission::firstOrCreate(['name' => "{$fitur}.{$aksi}"]);
            }
        }

        // Bikin role 'admin' dan assign semua permission
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Contoh role kasir hanya bisa transaksi & lihat produk
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $kasirPermissions = Permission::whereIn('name', [
            'transactions.lihat',
            'transactions.tambah',
            'transactions.edit',
            'products.lihat',
        ])->get();
        $kasir->syncPermissions($kasirPermissions);
    }
}
