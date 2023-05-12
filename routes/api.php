<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RegisteredUserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});
Route::controller(RoleController::class)->group(function () {
    Route::get('getRoles/{id}', 'getRoleAPI');
    Route::get('getAllRoles', 'getAllRolesAPI');
    Route::middleware(['auth:sanctum', 'admin'])->put('/updateRole/{id}', 'updateRoleAPI');
});
Route::middleware(['auth:sanctum'])->post('/logout', [RoleController::class, 'logoutAPI']);