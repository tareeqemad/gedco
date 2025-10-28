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
            'sliders.view', 'sliders.create', 'sliders.edit', 'sliders.delete',
            'jobs.view', 'jobs.create', 'jobs.edit', 'jobs.delete',
            // Permissions (لوحة الصلاحيات نفسها)
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',

            // Footer Links CRUD
            'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',

            // Social Links CRUD
            'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',

            // Site Settings (سجل واحد غالباً)
            'site-settings.edit',
        ];

        // إنشاء الصلاحيات (guard: web)
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // الأدوار
        $super  = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin  = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor',      'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

        // ربط الصلاحيات بالأدوار
        // السوبر ياخذ كل الصلاحيات
        $super->syncPermissions(Permission::all());

        // الأدمن: إدارة المستخدمين + روابط الفوتر + السوشال + تعديل إعدادات الموقع + عرض الصلاحيات
        $admin->syncPermissions([
            'users.view','users.create','users.edit','users.delete',
            'sliders.view', 'sliders.create', 'sliders.edit', 'sliders.delete',
            'footer-links.view','footer-links.create','footer-links.edit','footer-links.delete',
            'social-links.view','social-links.create','social-links.edit','social-links.delete',
            'site-settings.edit',
            'permissions.view', // للعرض فقط
        ]);

        // المحرّر: عرض/تعديل عناصر المحتوى فقط
        $editor->syncPermissions([
            'users.view',
            'sliders.view',
            'footer-links.view','footer-links.edit',
            'social-links.view','social-links.edit',
        ]);

        // المشاهد: عرض فقط
        $viewer->syncPermissions([
            'users.view', 'sliders.view', 'footer-links.view', 'social-links.view',
        ]);

        User::where('is_admin', true)->each(function (User $u) use ($admin) {
            // لو عنده أي دور أصلاً، لا تلمسه
            if ($u->hasAnyRole(['super-admin','admin'])) {
                return;
            }
            $u->assignRole($admin);
        });
    }
}
