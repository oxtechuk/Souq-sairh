<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        // ===== 1. Roles =====
        $roles = [
            'super-admin' => 'مدير النظام الكامل',
            'company-admin' => 'مدير الشركة',
            'branch-manager' => 'مدير الفرع',
            'sales-rep' => 'مندوب مبيعات',
            'viewer' => 'مشاهد فقط',
        ];

        foreach ($roles as $name => $label) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // ===== 2. Permissions (Core) =====
        $permissions = [
            // Contacts
            'contacts.view', 'contacts.create', 'contacts.edit', 'contacts.delete',
            // Tasks
            'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.delete',
            // System
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // super-admin gets all permissions
        Role::findByName('super-admin')->syncPermissions(Permission::all());

        // ===== 3. Modules Registry =====
        $modules = [
            ['alias' => 'core',       'name' => 'Core System',           'version' => '1.0.0', 'is_active' => true],
            ['alias' => 'sales',      'name' => 'Sales & Offers',         'version' => '1.0.0', 'is_active' => false],
            ['alias' => 'crm',        'name' => 'CRM',                    'version' => '1.0.0', 'is_active' => false],
            ['alias' => 'reports',    'name' => 'Reports & Analytics',    'version' => '1.0.0', 'is_active' => false],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(
                ['alias' => $module['alias']],
                array_merge($module, ['installed_at' => now()])
            );
        }

        // ===== 4. Super Admin User =====
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@erp.local'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Admin@123'),
                'is_super_admin' => true,
                'locale' => 'ar',
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('super-admin');

        $this->command->info('✅ Core Seeder completed successfully');
        $this->command->info('   Admin: admin@erp.local / Admin@123');
    }
}
