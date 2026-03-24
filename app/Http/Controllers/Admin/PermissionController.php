<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        // البحث
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // فلترة حسب Guard
        if ($guard = $request->get('guard')) {
            $query->where('guard_name', $guard);
        }

        $permissions = $query->orderBy('name')->paginate(30)->appends($request->query());

        // إحصائيات
        $stats = [
            'total' => Permission::count(),
            'web' => Permission::where('guard_name', 'web')->count(),
            'api' => Permission::where('guard_name', 'api')->count(),
        ];

        // تجميع الصلاحيات حسب المجموعة (مثل: news.view, news.create)
        $grouped = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        // الأدوار التي تستخدم كل صلاحية
        $permissionsWithRoles = $permissions->map(function ($permission) {
            $roles = $permission->roles()->pluck('name')->toArray();
            return [
                'permission' => $permission,
                'roles' => $roles,
                'roles_count' => count($roles),
            ];
        });

        // AJAX response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.permissions.partials.table', compact('permissionsWithRoles', 'permissions'))->render(),
                'pagination' => view('admin.permissions.partials.pagination', compact('permissions'))->render(),
                'total' => $permissions->total(),
            ]);
        }

        return view('admin.permissions.index', compact(
            'permissions',
            'stats',
            'grouped',
            'permissionsWithRoles'
        ));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:191|unique:permissions,name',
            'guard_name' => 'nullable|string|in:web,api',
        ]);

        Permission::create([
            'name' => trim($request->name),
            'guard_name' => $request->guard_name ?: 'web',
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', __('admin.permissions.created_successfully'));
    }

    public function edit(Permission $permission)
    {
        $roles = $permission->roles()->pluck('name')->toArray();
        return view('admin.permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'       => 'required|string|max:191|unique:permissions,name,'.$permission->id,
            'guard_name' => 'nullable|string|in:web,api',
        ]);

        $permission->update([
            'name'       => trim($request->name),
            'guard_name' => $request->guard_name ?: $permission->guard_name,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', __('admin.permissions.updated_successfully'));
    }

    public function destroy(Permission $permission)
    {
        // حماية: لا تحذف صلاحية حرجة
        $criticalPermissions = ['permissions.manage', 'roles.manage'];
        if (in_array($permission->name, $criticalPermissions)) {
            return back()->with('error', __('admin.permissions.cannot_delete_critical'));
        }

        $permission->delete();
        return redirect()->route('admin.permissions.index')
            ->with('success', __('admin.permissions.deleted_successfully'));
    }
}