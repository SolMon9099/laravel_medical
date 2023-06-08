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

    Route::resource('profiles', ProfileController::class)->middleware('role:patient');;

    Route::resource('clinics', ClinicController::class)->middleware('role:admin');
    Route::get('/get-clinic-data', [ClinicController::class, 'getClinicData'])->middleware('role:admin');

    Route::resource('referral', ReferralController::class)->middleware('role:office manager');
    Route::delete('/delete-referral-file/{id}', [ReferralController::class, 'deleteReferralFile'])->middleware('role:office manager');

    Route::group(['prefix' => 'calendar'], function () {
        Route::get('/', [CalendarController::class, 'index'])->middleware('role:office manager')->name('calendar.index');
        Route::post('/store', [CalendarController::class, 'store'])->middleware('role:office manager')->name('calendar.store');
        Route::post('/action', [CalendarController::class, 'action'])->middleware('role:office manager')->name('calendar.action');
    });


});
