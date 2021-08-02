<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ParentCompanyController;
use App\Http\Controllers\ShortlistedVehicleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
Route::post('req-password-reset', [ApiController::class, 'reqForgotPassword']);
Route::post('update-password', [ApiController::class, 'updatePassword']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);

    /**
     * Users
     */
    Route::get('users/{role}', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('add-user', [UserController::class, 'store']);
    Route::put('update-user-status/{user}', [UserController::class, 'statusUpdate']);
    Route::delete('delete-user/{user}', [UserController::class, 'destroy']);
    Route::put('update-user/{user}', [UserController::class, 'update']);
    Route::post('update-email', [UserController::class, 'changeEmail']);
    Route::post('update-user-password', [UserController::class, 'changePassword']);

    /**
     * Roles
     */
    Route::get('roles', [RoleController::class, 'index']);
    
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('create_company_profile',[ParentCompanyController::class, 'createCompany']);
    Route::put('update_company_profile/{id}',[ParentCompanyController::class, 'updateCompany']);
    Route::get('shortlisted_vehicles', [ShortlistedVehicleController::class, 'shortlistedvehicles']);
    Route::delete('delete_shortlisted_vehicle/{id}', [ShortlistedVehicleController::class, 'deletevehicle']);
});