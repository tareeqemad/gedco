<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === الأقسام العادية (CRUD) ===
        $normalSections = [
            'users',
            'sliders',
            'jobs',
            'about',
            'why',
            'impact-stats',
            'advertisements',
            'tenders',
            'news',
            'staff-profiles',
            'contact-messages',
        ];

        // === الأقسام الخاصة بالسوبر أدمن فقط (CRUD) ===
        $superOnlySections = [
            'roles',
            'permissions',
            'footer-links',
            'social-links',
            'activity-logs',
        ];

        $permissions = [];

        // 1) صلاحيات الأقسام العادية (CRUD)
        foreach ($normalSections as $section) {
            $permissions[] = "{$section}.view";
            $permissions[] = "{$section}.create";
            $permissions[] = "{$section}.edit";
            $permissions[] = "{$section}.delete";
        }

        // 1-b) صلاحيات إضافية impact stats
        $permissions[] = 'impact-stats.toggle';
        $permissions[] = 'impact-stats.reorder';

        // 2) صلاحيات الأقسام الحصرية للسوبر أدمن
        foreach ($superOnlySections as $section) {
            $permissions[] = "{$section}.view";
            $permissions[] = "{$section}.create";
            $permissions[] = "{$section}.edit";
            $permissions[] = "{$section}.delete";
        }


        $permissions[] = 'home-video.edit';

        // site settings
        $permissions[] = 'site-settings.edit';

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name'=>'web']);
        }

        // roles
        $super  = Role::firstOrCreate(['name'=>'super-admin','guard_name'=>'web']);
        $admin  = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
        $editor = Role::firstOrCreate(['name'=>'editor','guard_name'=>'web']);
        $viewer = Role::firstOrCreate(['name'=>'viewer','guard_name'=>'web']);

        // super admin
        $super->syncPermissions(Permission::all());

        // admin - كل شي ماعدا أقسام السوبر أدمن
        $superOnlyPermissionNames = [];
        foreach ($superOnlySections as $section) {
            $superOnlyPermissionNames[] = "{$section}.view";
            $superOnlyPermissionNames[] = "{$section}.create";
            $superOnlyPermissionNames[] = "{$section}.edit";
            $superOnlyPermissionNames[] = "{$section}.delete";
        }

        $adminPermissions = Permission::whereNotIn('name', $superOnlyPermissionNames)->get();
        $admin->syncPermissions($adminPermissions);

        // editor - view + create + edit فقط (بدون delete) ماعدا أقسام السوبر أدمن
        $editorPermissions = Permission::whereNotIn('name', $superOnlyPermissionNames)
            ->where(function($q){
                $q->where('name','like','%.view')
                    ->orWhere('name','like','%.create')
                    ->orWhere('name','like','%.edit')
                    ->orWhere('name','like','%.toggle')
                    ->orWhere('name','like','%.reorder');
            })->get();
        $editor->syncPermissions($editorPermissions);

        // viewer - view فقط ماعدا أقسام السوبر أدمن
        $viewerPermissions = Permission::whereNotIn('name', $superOnlyPermissionNames)
            ->where('name','like','%.view')->get();
        $viewer->syncPermissions($viewerPermissions);

        // assign to first user
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('super-admin')) {
            $firstUser->assignRole('super-admin');
        }
    }
}
