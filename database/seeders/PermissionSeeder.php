<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        dump('Seeder berjalan!');

        $permissions = [
            'view_product', 'create_product', 'update_product', 'delete_product',
            'view_category', 'create_category', 'update_category', 'delete_category',
            'view_transaction', 'create_transaction', 'update_transaction', 'delete_transaction',
            'view_user', 'create_user', 'update_user', 'delete_user',
            'view_role', 'create_role', 'update_role', 'delete_role',
            'view_permission', 'create_permission', 'update_permission', 'delete_permission',
            'view_report_daily', 'view_report_weekly', 'view_report_monthly',
            'access_pos', 'print_receipt',
        ];

        foreach ($permissions as $perm) {
            $created = Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);

            dump("✔️ Permission: " . $created->name);
        }
    }
}
