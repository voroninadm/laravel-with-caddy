<?php

use App\Http\Controllers\Auth\DestroyUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\WorkerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // users create
    Route::get('/users', [RegisteredUserController::class, 'users'])->middleware('permission:open_users_profiles')->name('users.show');
    Route::get('/users/create', [RegisteredUserController::class, 'create'])->middleware('permission:open_users_profiles')->name('users.create');
    Route::get('/users/find/{id}', [RegisteredUserController::class, 'find'])->middleware('permission:open_users_profiles')->name('users.find');
    Route::get('/users/edit/{id}', [RegisteredUserController::class, 'edit'])->middleware('permission:open_users_profiles')->name('users.edit');
    Route::patch('/users/update', [RegisteredUserController::class, 'updateProfile'])->middleware('permission:open_users_profiles')->name('users.update-profile');
    Route::patch('/users', [RegisteredUserController::class, 'updatePassword'])->middleware('permission:open_users_profiles')->name('users.update-password');

    Route::post('/users/register', [RegisteredUserController::class, 'store'])->middleware('permission:open_users_profiles')->name('users.register');
    Route::post('/users/destroy/{id}', [DestroyUserController::class, 'destroy'])->middleware('permission:open_users_profiles')->name('users.destroy');


    // workers
    Route::get('/workers', [WorkerController::class, 'workers'])->middleware('permission:open_workers_profiles')->name('workers.show');
    Route::get('/workers/edit/{id}', [WorkerController::class, 'edit'])->middleware('permission:open_workers_profiles')->name('workers.edit');
    Route::patch('/workers/update', [WorkerController::class, 'update'])->middleware('permission:open_workers_profiles')->name('workers.update');

    Route::post('/workers/register', [WorkerController::class, 'store'])->middleware('permission:open_workers_profiles')->name('workers.store');
    Route::post('/workers/destroy/{id}', [WorkerController::class, 'destroy'])->middleware('permission:open_workers_profiles')->name('workers.destroy');
});

