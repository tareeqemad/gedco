<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        // === البحث بالاسم أو البريد ===
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // === الفلتر بالدور ===
        if ($role = $request->get('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // === الترتيب + الصفحة ===
        $users = $query->orderByDesc('id')->paginate(15);

        // === الحفاظ على الفلاتر في الـ Pagination ===
        $users->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);
        $permissions = $this->getFilteredPermissions();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8',
            'role_id'    => 'nullable|exists:roles,id',
            'permissions'=> 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // === تعيين الدور ===
        if ($request->filled('role_id')) {
            $role = Role::find($request->role_id);
            $user->syncRoles([$role->name]);
        }

        // === تعيين الصلاحيات (آمن) ===
        $this->syncUserPermissions($user, $request->input('permissions', []));

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);

        $permissions = $this->getFilteredPermissions();

        $userPermissionIds = $user->getAllPermissions()->pluck('id')->toArray();
        // الحل: استخدم null-safe
        $userRole = $user->roles()->first(); // قد يكون null

        return view('admin.users.edit', compact(
            'user', 'roles', 'permissions', 'userPermissionIds', 'userRole'
        ));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:8',
            'role_id'    => 'nullable|exists:roles,id',
            'permissions'=> 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // === تعيين الدور ===
        if ($request->filled('role_id')) {
            $role = Role::find($request->role_id);
            $user->syncRoles([$role->name]);
        } else {
            $user->syncRoles([]);
        }

        // === تعيين الصلاحيات (آمن) ===
        $this->syncUserPermissions($user, $request->input('permissions', []));

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تعديل المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'لا يمكنك حذف نفسك.');
        }

        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'لا تملك صلاحية حذف Super Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم');
    }

    // === دالة مساعدة لتعيين الصلاحيات بأمان ===
    private function syncUserPermissions(User $user, array $permissionIds)
    {
        // جلب الكائنات فقط (لا IDs مباشرة)
        $permissions = Permission::find($permissionIds);

        // تصفية حسب guard_name = web (حسب مشروعك)
        $permissions = $permissions->where('guard_name', 'web');

        // تعيين الصلاحيات
        $user->syncPermissions($permissions);
    }

    private function getFilteredPermissions()
    {
        $query = Permission::orderBy('guard_name')->orderBy('name');

        // === لو المستخدم الحالي مو super-admin → أخفي الصلاحيات الحصرية ===
        if (!auth()->user()->hasRole('super-admin')) {
            $superOnly = [
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
                'footer-links.view', 'footer-links.create', 'footer-links.edit', 'footer-links.delete',
                'social-links.view', 'social-links.create', 'social-links.edit', 'social-links.delete',
            ];

            $query->whereNotIn('name', $superOnly);
        }

        return $query->get(['id', 'name', 'guard_name'])
            ->groupBy('guard_name');
    }
}
