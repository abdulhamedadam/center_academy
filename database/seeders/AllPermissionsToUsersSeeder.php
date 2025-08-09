<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AllPermissionsToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Permission::all();

        $users = User::all();
        foreach ($users as $user) {
            $user->syncPermissions($allPermissions);
        }
    }
} 