<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount(['users', 'permissions']);

        // البحث
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name')->paginate(20)->appends($request->query());

        // إحصائيات
        $stats = [
            'total' => Role::count(),
            'total_users' => \App\Models\User::role(Role::pluck('name')->toArray())->count(),
            'total_permissions' => Permission::count(),
        ];

        // الأدوار مع معلومات إضافية
        $rolesWithDetails = $roles->map(function ($role) {
            return [
                'role' => $role,
                'users' => $role->users()->limit(5)->get(),
                'permissions_count' => $role->permissions()->count(),
                'permissions' => $role->permissions()->limit(5)->pluck('name')->toArray(),
            ];
        });

        return view('admin.roles.index', compact('roles', 'stats', 'rolesWithDetails'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id', 'name', 'guard_name'])
            ->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);
                return $parts[0] ?? 'other';
            });

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:191|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        $this->syncRolePermissions($role, $request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', __('admin.roles.created_successfully'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id', 'name', 'guard_name'])
            ->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);
                return $parts[0] ?? 'other';
            });

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact(
            'role', 'permissions', 'rolePermissionIds'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|string|max:191|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $this->syncRolePermissions($role, $request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', __('admin.roles.updated_successfully'));
    }

    public function destroy(Role $role)
    {
        // منع حذف super-admin
        if ($role->name === 'super-admin') {
            return back()->with('error', __('admin.roles.cannot_delete_super_admin'));
        }

        // منع حذف إذا كان مستخدم
        if ($role->users()->exists()) {
            return back()->with('error', __('admin.roles.cannot_delete_with_users'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', __('admin.roles.deleted_successfully'));
    }

    // === دالة مساعدة لتعيين الصلاحيات ===
    private function syncRolePermissions(Role $role, array $permissionIds)
    {
        $permissions = Permission::whereIn('id', $permissionIds)
            ->where('guard_name', 'web')
            ->get();
        $role->syncPermissions($permissions);
    }
}