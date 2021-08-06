<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ParentCompanyController;
use App\Http\Controllers\ShortlistedVehicleController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VerificationController;
//use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;

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
Route::post('verify-otp', [ApiController::class, 'verifyOtp']);
Route::post('regenerate-otp', [ApiController::class, 'regenerateOtp']);
Route::post('social-login', [ApiController::class, 'socialLogin']);
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');


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
     * Stores
     */
    Route::get('stores', [StoreController::class, 'index']);
    Route::post('add-store', [StoreController::class, 'store']);
    Route::put('update-store-status/{store}', [StoreController::class, 'statusUpdate']);
    Route::delete('delete-store/{store}', [StoreController::class, 'destroy']);
    Route::get('store/{id}', [StoreController::class, 'show']);
    Route::put('update-store/{store}', [StoreController::class, 'update']);

    /**
     * Roles
     */
    Route::get('roles', [RoleController::class, 'index']);

    /**
     * Vehicles
     */
    Route::get('vehicles', [VehicleController::class, 'index']);
    
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('create_company_profile',[ParentCompanyController::class, 'createCompany']);
    Route::put('update_company_profile/{id}',[ParentCompanyController::class, 'updateCompany']);
    Route::get('shortlisted_vehicles', [ShortlistedVehicleController::class, 'shortlistedvehicles']);
    Route::delete('delete_shortlisted_vehicle/{id}', [ShortlistedVehicleController::class, 'deletevehicle']);
    //Route::post('file_upload', [FileController::class, 'upload']);
});

Route::post('admin-login', [AdminController::class, 'login']);
Route::post('admin-forgot-password', [AdminController::class, 'ForgotPassword']);
Route::post('admin-update-password', [AdminController::class, 'updatePassword']);