<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('guard_name')
            ->orderBy('name')
            ->get(['id', 'name', 'guard_name'])
            ->groupBy('guard_name');

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
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('guard_name')
            ->orderBy('name')
            ->get(['id', 'name', 'guard_name'])
            ->groupBy('guard_name');

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
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        // منع حذف super-admin
        if ($role->name === 'super-admin') {
            return back()->with('error', 'لا يمكن حذف دور Super Admin');
        }

        // منع حذف إذا كان مستخدم
        if ($role->users()->exists()) {
            return back()->with('error', 'لا يمكن حذف دور مستخدم من قبل مستخدمين');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح');
    }

    // === دالة مساعدة لتعيين الصلاحيات ===
    private function syncRolePermissions(Role $role, array $permissionIds)
    {
        $permissions = Permission::find($permissionIds);
        $permissions = $permissions->where('guard_name', 'web');
        $role->syncPermissions($permissions);
    }
}
