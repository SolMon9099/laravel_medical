<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ClinicController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class)->middleware('role:admin');   

    Route::resource('profiles', ProfileController::class)->middleware('role:admin');

    Route::resource('clinics', ClinicController::class)->middleware('role:admin');
    Route::get('/get-clinic-data', [ClinicController::class, 'getClinicData'])->middleware('role:admin');

    Route::resource('referral', ReferralController::class)->middleware('role:office manager');

    Route::resource('calendar', CalendarController::class)->middleware('role:office manager');
});
