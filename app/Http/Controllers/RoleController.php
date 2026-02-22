<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    public function getRolesData()
    {
        $roles = Role::query();
        if (!auth()->user()->hasRole('Superadmin')) {
            $roles->where('name', '!=', 'Superadmin');
        }

        return DataTables::of($roles)
            ->addColumn('permissions', function($role) {
                return $role->permissions->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($role) {
                if ($role->name === 'Superadmin') {
                    return '<span class="badge bg-danger">Protected</span>';
                }
                
                $editUrl = route('roles.edit', $role->id);
                $deleteUrl = route('roles.destroy', $role->id);

                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-light-primary" title="Edit Role">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRecord(\'' . $deleteUrl . '\')" title="Delete Role">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($perm) {
            $parts = explode(' ', $perm->name);
            return (count($parts) > 1) ? implode(' ', array_slice($parts, 1)) : $perm->name;
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy(function($perm) {
            $parts = explode(' ', $perm->name);
            return (count($parts) > 1) ? implode(' ', array_slice($parts, 1)) : $perm->name;
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'permissions' => 'nullable|array'
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['Admin', 'Superadmin'])) {
            return response()->json(['success' => false, 'message' => "{$role->name} role cannot be deleted."]);
        }
        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
    }
}
