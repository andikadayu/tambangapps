<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('login', [\App\Http\Controllers\AULoginController::class, 'index'])->name('login');
Route::post('login/process', [\App\Http\Controllers\AULoginController::class, 'login'])->name('login_process');
Route::get('logout', [\App\Http\Controllers\AULoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['check_login']], function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('get-data-dashboard', [App\Http\Controllers\DashboardController::class, 'getdata'])->name('get_data_dashboard');

    Route::get('schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule');
    Route::post('schedule/delete', [\App\Http\Controllers\ScheduleController::class, 'schedule_delete'])->name('schedule_delete');
    Route::get('schedule/add', [\App\Http\Controllers\ScheduleController::class, 'add'])->name('add_schedule');
    Route::post('schedule/add/process', [\App\Http\Controllers\ScheduleController::class, 'add_process'])->name('add_schedule_process');

    Route::get('schedule/{id}', [\App\Http\Controllers\ScheduleController::class, 'detail'])->name('detail');
    Route::post('schedule/update', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('update_schedule_process');
    Route::post('schedule/confirm-or-reject', [\App\Http\Controllers\ScheduleController::class, 'confirm_reject'])->name('confirm_reject');

    Route::get('stats/export', [\App\Http\Controllers\ExportController::class, 'exportdata'])->name('exportdata');
});
