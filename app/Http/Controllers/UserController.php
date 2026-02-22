<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getUsersData()
    {
        $users = User::with('company')->select('users.*');
        
        // Hide Superadmin from everyone else, and only show same company users
        if (!auth()->user()->hasRole('Superadmin')) {
            $users->where('company_id', auth()->user()->company_id)
                  ->whereDoesntHave('roles', function($q) {
                      $q->where('name', 'Superadmin');
                  });
        }

        return DataTables::of($users)
            ->addColumn('company', function($user) {
                return $user->company ? $user->company->name : '<span class="text-muted fst-italic">None</span>';
            })
            ->addColumn('roles', function($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($user) {
                $editUrl = route('users.edit', $user->id);
                $deleteUrl = route('users.destroy', $user->id);

                return '
                    <div class="d-flex align-items-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-light-primary" title="Edit User">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRecord(\'' . $deleteUrl . '\')" title="Delete User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['action', 'company', 'roles'])
            ->make(true);
    }

    public function create()
    {
        if (auth()->user()->hasRole('Superadmin')) {
            $roles = Role::pluck('name', 'name')->all();
        } else {
            $roles = Role::where('name', '!=', 'Superadmin')->pluck('name', 'name')->all();
        }
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', Password::defaults()],
            'roles' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => auth()->user()->company_id, // Auto-assign to logged-in user's company
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->user()->hasRole('Superadmin')) {
            $roles = Role::pluck('name', 'name')->all();
        } else {
            $roles = Role::where('name', '!=', 'Superadmin')->pluck('name', 'name')->all();
        }
        
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => ['nullable', 'string', Password::defaults()],
            'roles' => 'required'
        ]);

        $user = User::findOrFail($id);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            // Keep existing company_id if user is updated
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        
        // Remove existing roles completely before assigning the selected ones
        // Or simply sync 
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'Cannot delete the only remaining admin user.']);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
