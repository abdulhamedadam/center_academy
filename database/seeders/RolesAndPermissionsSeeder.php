<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'super admin',
            'admin',
            'accountant',
            'receptionist',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $allPermissions = Permission::all();

        Role::findByName('super_admin')->syncPermissions($allPermissions);
        Role::findByName('admin')->syncPermissions($allPermissions);

        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
            $user->syncPermissions($allPermissions);
        }
    }
}
