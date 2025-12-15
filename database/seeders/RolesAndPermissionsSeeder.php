<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // نفضي الكاش بتاع الصلاحيات عشان لو شغالين قبل كده
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1) تعريف الصلاحيات
        $permissions = [
            'access dashboard',
            'manage users',
            'manage products',
            'manage orders',
            'view orders',
            'manage categories',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2) تعريف الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // 3) ربط الأدوار بالصلاحيات

        // admin = كل الصلاحيات
        $adminRole->syncPermissions(Permission::all());

        // supervisor = جزء من الصلاحيات
        $supervisorRole->syncPermissions([
            'access dashboard',
            'manage products',
            'manage orders',
            'view orders',
        ]);

        // customer = صلاحيات خاصة بالعميل فقط
        $customerRole->syncPermissions([
            'view orders',
        ]);
    }
}
