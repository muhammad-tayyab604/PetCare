<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'digits_between:1,16', 'unique:' . User::class],
            'location' => ['required', 'string', 'max:255'],
            'cnic' => ['required', 'numeric', 'digits_between:1,13', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'number' => $request->number,
            'location' => $request->location,
            'cnic' => $request->cnic,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $roleName = Role::findById($request->role_id);
        $user->assignRole($roleName);
        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
    // Register API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'digits_between:1,16', 'unique:' . User::class],
            'location' => ['required', 'string', 'max:255'],
            'cnic' => ['required', 'numeric', 'digits_between:1,13', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::create([
            'name' => $request->input('name'),
            'number' => $request->input('number'),
            'cnic' => $request->input('cnic'),
            'location' => $request->input('location'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'password_confirmation' => ['required', 'same:password'],

        ]);
        $roles = Role::whereNotIn('name', ['admin'])->pluck('name');
        $roleName = $request->input('role');
        $role = \DB::table('roles')->where('name', $roleName)->first();
        if ($roleName == 'admin') {
            $user->delete();
            return response()->json([
                'error' => 'Role not found please enter appropriate role ',
                'Roles' => '' . $roles,
            ]);
        }

        if ($role) {
            $roleName = $request->input('role');
            $role = Role::where('name', $roleName)->first();
            $user->assignRole($role);
            $success['token'] = $user->createToken('PetCare')->plainTextToken;
            $success['name'] = $user->name;
            $success['phone number'] = $user->number;
            $success['location'] = $user->location;
            $success['cnic'] = $user->cnic;
            $success['email'] = $user->email;
            $success['role'] = $role->name;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'Registered Successfully'
            ];
            return response()->json($response, 200);
        } else {
            $user->delete();
            return response()->json([
                'error' => 'Role not found please enter appropriate role ',
                'Roles' => '' . $roles
            ], 422);

        }
    }

    // Login API
    public function login(Request $request, Role $role)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::User();
            $role = $user->roles()->pluck('name')[0];
            // $success['phone number'] = $user->number;
            // $success['location'] = $user->location;
            // $success['cnic'] = $user->cnic;
            // $success['email'] = $user->email;
            // $success['role'] = $role;
            $response = [
                'success' => true,
                'Token' => $user->createToken('PetCare')->plainTextToken,
                'Name' => $user->name,
                'Dashboard' => $role . "'s Dashboard",
                'message' => 'Welcome ' . $user->name
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Please register your account'
            ];
            return response()->json($response);
        }
    }
}