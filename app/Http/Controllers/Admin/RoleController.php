<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\AuthManager;

class RoleController extends Controller
{
    public function getRoles()
    {
        $roles = Auth::user()->roles->pluck('name')->first();
        $user = Auth::user();
        $name = $user->name;
        return view('dashboard', compact('roles', 'name'));

    }

    public function contact()
    {
        return view('contactUs');
    }


    public function index(Request $id)
    {
        $users = User::all();
        $user = User::with('roles')->find($id);
        return view('admin.roles.index', compact('users'));
    }
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }
    public function getRole(Request $request, $id)
    {
        $users = User::get();
        $roles = Role::get();
        $user = User::find($id);
        $role = Role::find($id);
        return view('admin.roles.edit', ['id' => $id], compact('roles', 'users', 'user'));
    }
    public function changeUserRole(Request $request, $id)
    {

        if (Auth::user()->hasRole('admin') && Auth::user()->id != $id) {
            $user = User::findOrFail($id);
            $user->syncRoles($request->roles);
            return redirect()->route('admin.roles.index')
                ->with('success', 'User updated successfully');
        }

        // If admin is trying to change their own role, show an error message
        return view('admin.roles.message');
    }
    // Get Roles Api
    public function getRoleAPI(Request $request, $id)
    { {
            if (!$id) {
                return response()->json(['error' => 'User ID is required'], 400);
            }
            try {
                $user = User::findOrFail($id);
                $role = $user->roles()->first();
                $response = [
                    'Name' => $user->name,
                    'Phone Number' => $user->number,
                    'Location' => $user->location,
                    'CNIC' => $user->cnic,
                    'Email' => $user->email,
                    'Role' => $role->name,
                ];
                return response()->json($response, 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'User not found with id ' . $id]);
            }
        }
    }
    // Display All Roles Except Admin
    public function getAllRolesAPI()
    {
        $roles = Role::where('name', '<>', 'admin')->pluck('name');
        return response()->json(['roles' => $roles]);
    }
    // Update Role API
    public function updateRoleAPI(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $roleName = $request->input('role');

        $role = \DB::table('roles')->where('name', $roleName)->first();

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        $user->syncRoles([$role->name]);
        return response()->json(['message' => 'Role updated successfully', 'New Role' => $role->name]);
    }





    public function logoutAPI()
    {
        auth()->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout Successfully'
        ]);
    }
}