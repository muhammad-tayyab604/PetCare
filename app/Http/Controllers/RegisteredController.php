<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RegisteredController extends Controller
{
    public function create()
    {
        $roles = Role::where('name', '!=', 'admin')->pluck('name', 'id');
        return view('auth.register', compact('roles'));
    }

}