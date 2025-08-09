<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin Role with all permissions
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());
        
        // Create Accountant Role
        $accountant = Role::create(['name' => 'Accountant']);
        $accountant->givePermissionTo([
            // Student permissions
            'view_students',
            'view_any_students',
            // Course permissions
            'view_courses',
            'view_any_courses',
            // Payment permissions
            'view_subscription::payment',
            'view_any_subscription::payment',
            'create_subscription::payment',
            'update_subscription::payment',
            // Expense permissions
            'view_expense',
            'view_any_expense',
            'create_expense',
            'update_expense',
            // Payment methods
            'view_payment::methods',
            'view_any_payment::methods',
            // Payment status
            'view_payment::status',
            'view_any_payment::status',
            // Reports
            'page_PaymentsReceivedReport',
            'page_RevenueReports',
            'page_OverdueInstallmentsReport',
        ]);

        // Create Receptionist Role
        $receptionist = Role::create(['name' => 'Receptionist']);
        $receptionist->givePermissionTo([
            // Student permissions
            'view_students',
            'view_any_students',
            'create_students',
            'update_students',
            // Course permissions
            'view_courses',
            'view_any_courses',
            'create_courses',
            'update_courses',
            // Attendance permissions
            'view_attendance',
            'view_any_attendance',
            'create_attendance',
            'update_attendance',
            // CRM permissions
            'view_leads',
            'view_any_leads',
            'create_leads',
            'update_leads',
            'view_follow::up',
            'view_any_follow::up',
            'create_follow::up',
            'update_follow::up',
            // Schedule permissions
            'page_GlobalSchedules',
            'widget_GlobalSchedulesWidget',
            'widget_TodaysFollowUps',
        ]);
    }
} 