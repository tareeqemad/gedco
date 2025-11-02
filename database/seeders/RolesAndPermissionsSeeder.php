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
            'advertisements', // 👈 جديد: صلاحيات CRUD كاملة لهذا القسم
        ];

        // === الأقسام الخاصة بالسوبر أدمن فقط (CRUD) ===
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

        // 2) صلاحيات الأقسام الحصرية للسوبر أدمن (CRUD)
        foreach ($superOnlySections as $section) {
            $permissions[] = "{$section}.view";
            $permissions[] = "{$section}.create";
            $permissions[] = "{$section}.edit";
            $permissions[] = "{$section}.delete";
        }

        // 3) إعدادات الموقع
        $permissions[] = 'site-settings.edit';

        // إنشاء الصلاحيات (إن وُجدت من قبل لا تتكرر)
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // إنشاء الأدوار
        $super  = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin  = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // 1) Super Admin: كل الصلاحيات
        $super->syncPermissions(Permission::all());

        // 2) Admin: كل شيء ما عدا الأقسام الحصرية (roles/permissions/footer-links/social-links)
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

        // 3) Editor: عرض + إنشاء/تعديل + toggle/reorder (بدون الأقسام الحصرية)
        $editorPermissions = Permission::whereNotIn('name', [
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
            'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',
            'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',
        ])->where(function ($q) {
            $q->where('name', 'like', '%.view')
                ->orWhere('name', 'like', '%.create')
                ->orWhere('name', 'like', '%.edit')
                ->orWhere('name', 'like', '%.toggle')
                ->orWhere('name', 'like', '%.reorder');
        })->get();
        $editor->syncPermissions($editorPermissions);

        // 4) Viewer: عرض فقط (مع استثناء عرض الأقسام الحصرية)
        $viewerPermissions = Permission::whereNotIn('name', [
            'roles.view', 'permissions.view',
            'footer-links.view', 'social-links.view',
        ])->where('name', 'like', '%.view')->get();
        $viewer->syncPermissions($viewerPermissions);

        // تعيين الدور للمستخدم الأول (إن لم يكن سوبر)
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('super-admin')) {
            $firstUser->assignRole('super-admin');
        }

        // ترقية أي مستخدم is_admin=true إلى admin إن لم يملك دورًا
        User::where('is_admin', true)
            ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['super-admin', 'admin']))
            ->each(fn($u) => $u->assignRole('admin'));
    }
}
