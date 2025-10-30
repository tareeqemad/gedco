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
        // جميع الصلاحيات المستخدمة في لوحة الإدارة
        $permissions = [
            // Users
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // Sliders
            'sliders.view', 'sliders.create', 'sliders.edit', 'sliders.delete',

            // Jobs
            'jobs.view', 'jobs.create', 'jobs.edit', 'jobs.delete',

            // Permissions (لوحة الصلاحيات نفسها)
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',

            // Footer Links
            'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',

            // Social Links
            'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',

            // Site Settings
            'site-settings.edit',

            //  قسم من نحن
            'about.view', 'about.create', 'about.edit', 'about.delete',

            //  قسم لماذا تختارنا
            'why.view', 'why.create', 'why.edit', 'why.delete',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $super  = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin  = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        $super->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'users.view','users.create','users.edit','users.delete',
            'sliders.view','sliders.create','sliders.edit','sliders.delete',
            'jobs.view','jobs.create','jobs.edit','jobs.delete',
            'footer-links.view','footer-links.create','footer-links.edit','footer-links.delete',
            'social-links.view','social-links.create','social-links.edit','social-links.delete',
            'site-settings.edit',
            'permissions.view',
            'about.view','about.create','about.edit','about.delete',
            'why.view','why.create','why.edit','why.delete',
        ]);

        $editor->syncPermissions([
            'sliders.view','sliders.edit',
            'footer-links.view','footer-links.edit',
            'social-links.view','social-links.edit',
            'about.view','about.edit',
            'why.view','why.edit',
        ]);

        $viewer->syncPermissions([
            'sliders.view','footer-links.view','social-links.view','about.view','why.view',
        ]);

        User::where('is_admin', true)->each(function (User $u) use ($admin) {
            if (!$u->hasAnyRole(['super-admin','admin'])) {
                $u->assignRole($admin);
            }
        });
    }
}
