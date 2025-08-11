<?php

use App\Http\Controllers\InfoController;
use App\Http\Controllers\JsonOrderController;
use App\Http\Controllers\PersonalTesting\HiringController;
use App\Http\Controllers\PersonalTesting\PersonalTestingController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\Reports\IdleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ThirdPartyApi\EncostApiController;
use App\Http\Controllers\ThirdPartyApi\SPbApiController;
use App\Http\Middleware\CheckUserInfoAccess;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::middleware('guest')->get(('/'), function () {
    return Inertia::render('WelcomePage');
})->name('login');


// Site routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [SiteController::class, 'index'])->name('dashboard');
});


// Reports and idles routes
Route::middleware(['auth'])->prefix('idles')->group(function () {
    Route::get('/machines', [IdleController::class, 'machinesIdles'])->name('machinesIdle.get');
    Route::post('/machines', [IdleController::class, 'machinesIdles'])->name('machinesIdle.post');
});


// Info routes
Route::middleware(['auth'])->group(function () {
    Route::get('/info/{sector}', [InfoController::class, 'info'])->middleware('CheckUserInfoAccess')->name('info');
    Route::post('/info/store', [InfoController::class, 'store'])->name('info.store');
    Route::patch('/info/read/{info}', [InfoController::class, 'read']);
    Route::patch('/info/finish/{info}', [InfoController::class, 'finish']);
    Route::patch('/info/update/{info}', [InfoController::class, 'update'])->name('info.update');
    Route::delete('/info/destroy/{info}', [InfoController::class, 'destroy'])->name('info.destroy');
});

// JsonOrders
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [JsonOrderController::class, 'orders'])->name('orders');
    Route::post('/orders', [JsonOrderController::class, 'orders']);
    Route::post('/order/update/{order}', [JsonOrderController::class, 'update'])->name('order.update');

    Route::post('/order/spoolcutter', [JsonOrderController::class, 'spoolcutterReportExport'])->name('order.spoolcutter');

});

// Utilities
Route::get('/promo', [PromoController::class, 'index'])->middleware('permission:open_apps')->name('promo');
Route::post('/get-promo', [PromoController::class, 'getPromo'])->middleware('permission:open_apps')->name('promo.generate');


// NTL user personal testing
Route::controller(PersonalTestingController::class)->middleware(['guest', 'verifyTestsAuth'])->group(callback: function () {
    Route::get('/personal-testing/index', 'index')->name('testing.index');
});

Route::controller(HiringController::class)->group(callback: function () {
    Route::get('/personal-testing/hiring', 'index')->middleware(['guest', 'verifyTestsAuth'])->name('hiring.index');
    Route::post('/personal-testing/hiring/store', 'store')->middleware(['guest', 'verifyTestsAuth'])->name('hiring.store');

    Route::get('/personal-testing/hiring/show/{YearMonth}', 'show')->middleware(['auth','permission:open_tests'])->name('hiring.show');
    Route::patch('/personal-testing/hiring/update/{person}', 'update')->middleware('auth','permission:open_tests')->name('hiring.update');
    Route::get('/personal-testing/hiring/performance/{person}', 'performance')->middleware(['auth','permission:open_tests'])->name('hiring.performance');
    Route::get('/personal-testing/hiring/download/{person}', 'ExportToPDF')->middleware(['auth','permission:open_tests'])->name('hiring.pdf');
});


// SPb db api
Route::get('/api/get-orders/{tkn}', [SPbApiController::class, 'getOrders'])->middleware('auth');
Route::get('/api/get-schedule', [SPbApiController::class, 'getSchedule'])->middleware('auth');


//encost api
Route::get('/api/encost/endpoints', [EncostApiController::class, 'getEndpoints'])->middleware('auth');
Route::get('/api/encost/get-idles', [EncostApiController::class, 'getIdlesData'])->middleware('auth');

require __DIR__.'/auth.php';
