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

    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profiles.index');
        Route::post('/store', [ProfileController::class, 'store'])->name('profiles.store');
        Route::post('/store_password', [ProfileController::class, 'store_password'])->name('profiles.store_password');
        Route::get('/change_password', [ProfileController::class, 'change_password'])->name('profiles.change_password');
        Route::get('/patient_transaction', [ProfileController::class, 'patient_transaction'])->name('profiles.patient_transaction');
        Route::post('/upload_sign_docs', [ProfileController::class, 'upload_sign_docs'])->name('profiles.upload_sign_docs');
        Route::post('/upload_result_docs', [ProfileController::class, 'upload_result_docs'])->name('profiles.upload_result_docs');
        Route::post('/set_advanced_paid', [ProfileController::class, 'set_advanced_paid'])->name('profiles.set_advanced_paid');
        Route::post('/set_settled', [ProfileController::class, 'set_settled'])->name('profiles.set_settled');
    });

    Route::resource('clinics', ClinicController::class)->middleware('role:admin');
    Route::get('/get-clinic-data', [ClinicController::class, 'getClinicData'])->middleware('role:admin');

    Route::resource('referral', ReferralController::class)->middleware('role:office manager');
    Route::delete('/delete-referral-file/{id}', [ReferralController::class, 'deleteReferralFile'])->middleware('role:office manager');

    Route::group(['prefix' => 'calendar'], function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/store', [CalendarController::class, 'store'])->name('calendar.store');
        Route::post('/action', [CalendarController::class, 'action'])->name('calendar.action');
    });

});
