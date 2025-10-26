<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
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

        return redirect()->route('admin.permissions.index')->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
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

        return redirect()->route('admin.permissions.index')->with('success', 'تم تحديث الصلاحية');
    }

    public function destroy(Permission $permission)
    {
        // حماية بسيطة: لا تحذف صلاحية حرجة إن حاب
        if (in_array($permission->name, ['permissions.manage'])) {
            return back()->with('success', 'لا يمكن حذف هذه الصلاحية الحرجة.');
        }

        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'تم حذف الصلاحية');
    }
}
