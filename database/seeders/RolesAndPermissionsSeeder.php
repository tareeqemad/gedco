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

        // === الأقسام العادية ===
        $normalSections = [
            'users',
            'sliders',
            'jobs',
            'about',
            'why',
            'impact-stats',
        ];

        // === الأقسام الخاصة بالسوبر أدمن فقط ===
        $superOnlySections = [
            'roles',
            'permissions',
            'footer-links',
            'social-links',
        ];

        $permissions = [];

        // 1) صلاحيات الأقسام العادية (CRUD)
        foreach ($normalSections as $section) {
            $permissions[] = "{$section}.view";
            $permissions[] = "{$section}.create";
            $permissions[] = "{$section}.edit";
            $permissions[] = "{$section}.delete";
        }

        // 1-b) صلاحيات إضافية لقسم impact-stats
        $permissions[] = 'impact-stats.toggle';
        $permissions[] = 'impact-stats.reorder';

        // 2) صلاحيات الأقسام الحصرية للسوبر أدمن
        foreach ($superOnlySections as $section) {
            $permissions[] = "{$section}.view";
            $permissions[] = "{$section}.create";
            $permissions[] = "{$section}.edit";
            $permissions[] = "{$section}.delete";
        }

        // 3) إعدادات الموقع
        $permissions[] = 'site-settings.edit';

        // إنشاء الصلاحيات
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // الأدوار
        $super  = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin  = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // 1) Super Admin: الكل
        $super->syncPermissions(Permission::all());

        // 2) Admin: كل شيء ما عدا الأقسام الحصرية
        $adminPermissions = Permission::whereNotIn('name', [
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
            'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',
            'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',
        ])->where(function ($q) {
            $q->where('name', 'like', '%.view')
                ->orWhere('name', 'like', '%.create')
                ->orWhere('name', 'like', '%.edit')
                ->orWhere('name', 'like', '%.delete')
                ->orWhere('name', 'like', '%.toggle')
                ->orWhere('name', 'like', '%.reorder');
        })->get();
        $admin->syncPermissions($adminPermissions);

        // ⚠️ إصلاح التجميع للـ Editor حتى لا تفلت orWhere خارج notIn
        $editorPermissions = Permission::whereNotIn('name', [
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
            'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',
            'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',
        ])->where(function ($q) {
            $q->where('name', 'like', '%.view')
                ->orWhere('name', 'like', '%.create')
                ->orWhere('name', 'like', '%.edit')
                ->orWhere('name', 'like', '%.toggle')   // يعتبر ضمن صلاحيات التحرير
                ->orWhere('name', 'like', '%.reorder'); // ترتيب أيضاً للمحرر
        })->get();
        $editor->syncPermissions($editorPermissions);

        // Viewer: عرض فقط (مع استثناءات الأقسام الحصرية)
        $viewerPermissions = Permission::whereNotIn('name', [
            'roles.view', 'permissions.view',
            'footer-links.view', 'social-links.view',
        ])->where('name', 'like', '%.view')->get();
        $viewer->syncPermissions($viewerPermissions);

        // تعيين الدور للمستخدم الأول
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('super-admin')) {
            $firstUser->assignRole('super-admin');
        }

        User::where('is_admin', true)
            ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['super-admin', 'admin']))
            ->each(fn($u) => $u->assignRole('admin'));
    }
}
