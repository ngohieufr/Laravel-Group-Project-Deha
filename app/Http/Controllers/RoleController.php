<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;

        // Thêm middleware để kiểm tra quyền
    }

    public function index()
    {
        $roles = $this->role->latest('id')->paginate(5);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('edit', $role);

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('edit', $role);

        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        $permissions = $request->input('permissions', []);
        $role->permissions()->sync($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
