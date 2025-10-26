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
    public function index()
    {
        // نعرض الأدوار مع المستخدمين
        $users = User::with('roles')->orderByDesc('id')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get(['id','name']);
        $permissions = Permission::orderBy('name')->get(['id','name']);
        return view('admin.users.create', compact('roles','permissions'));
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
            // لو حاب تبقي المؤقت القديم:
            'is_admin' => false,
        ]);

        // ربط الدور (اختياري)
        if ($request->filled('role_id')) {
            $role = Role::find($request->role_id);
            $user->syncRoles([$role->name]);
        }

        // ربط صلاحيات إضافية (اختياري)
        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get(['id','name']);
        $permissions = Permission::orderBy('name')->get(['id','name']);
        $userPermissionIds = $user->permissions()->pluck('id')->toArray();
        $userRole = $user->roles()->first(); // أول/الدور الأساسي

        return view('admin.users.edit', compact('user','roles','permissions','userPermissionIds','userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
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
        // لو لسه محتفظ بعلم is_admin مؤقتًا:
        $user->is_admin = $user->hasAnyRole(['admin','super-admin']);
        $user->save();

        if ($request->filled('role_id')) {
            $role = Role::find($request->role_id);
            $user->syncRoles([$role->name]);
        } else {
            $user->syncRoles([]); // إزالة الأدوار لو ما تم اختيار شيء
        }

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]); // إزالة الصلاحيات المنفردة
        }

        return redirect()->route('admin.users.index')->with('success', 'تم تعديل المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        // حماية: لا تحذف نفسك، ولا تحذف super-admin آخر لو أنت لست super-admin
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('success', 'لا يمكنك حذف نفسك.');
        }
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->back()->with('success', 'لا تملك صلاحية حذف Super Admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم');
    }
}
