<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::where('guard_name', 'web')->delete();

        $permissions = [
            'dashboard.view',

            'cars.view', 'cars.create', 'cars.edit', 'cars.delete',
            'brands.view', 'brands.create', 'brands.edit', 'brands.delete',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'specifications.view', 'specifications.create', 'specifications.edit', 'specifications.delete',
            'features.view', 'features.create', 'features.edit', 'features.delete',

            'offers.view', 'offers.create', 'offers.edit', 'offers.delete',

            'contacts.view', 'contacts.create', 'contacts.edit', 'contacts.delete',
            'calculator-leads.view', 'calculator-leads.delete',
            'contact-sources.view', 'contact-sources.create', 'contact-sources.edit', 'contact-sources.delete',

            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.delete',
            'tracking.view',
            'calculator.view', 'calculator.create', 'calculator.edit', 'calculator.delete',

            'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.manage',

            'reports.view',

            'blog.view', 'blog.create', 'blog.edit', 'blog.delete',
            'translations.view', 'translations.edit',

            'testimonials.view', 'testimonials.create', 'testimonials.edit', 'testimonials.delete',
            'partners.view', 'partners.create', 'partners.edit', 'partners.delete',
            'designs.view', 'designs.create', 'designs.edit', 'designs.delete',

            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        $salesPermissions = [
            'dashboard.view',
            'cars.view',
            'brands.view',
            'offers.view',
            'contacts.view', 'contacts.create', 'contacts.edit',
            'bookings.view', 'bookings.create', 'bookings.edit',
            'tasks.view', 'tasks.create', 'tasks.edit',
            'tracking.view',
        ];

        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions($salesPermissions);

        $salesRole = Role::firstOrCreate(['name' => 'sales', 'guard_name' => 'web']);
        $salesRole->syncPermissions($salesPermissions);

        // Assign roles to existing employees
        $employeeModel = \App\Models\Employee::class;
        foreach ($employeeModel::all() as $emp) {
            if ($emp->role === 'admin') {
                $emp->assignRole('admin');
            } elseif ($emp->role === 'sales') {
                $emp->assignRole('sales');
            } else {
                $emp->assignRole('employee');
            }
        }

        $this->command->info('✅ PermissionSeeder completed successfully');
    }
}
