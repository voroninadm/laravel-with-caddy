<?php

use App\Http\Controllers\Elog\CirculationController;
use App\Http\Controllers\Elog\CuttingController;
use App\Http\Controllers\Elog\ExtrusionController;
use App\Http\Controllers\Elog\LaminationController;
use App\Http\Controllers\Elog\PrintingController;
use App\Http\Controllers\Elog\RecyclingController;
use App\Http\Controllers\Elog\FlexoformController;
use App\Http\Controllers\Elog\WeldingController;
use Illuminate\Support\Facades\Route;


Route::controller(PrintingController::class)->middleware('auth')->group(function () {
    Route::get('/print/create', 'create')->middleware('permission:medium_printing_permission|full_printing_permission')->name('printing.create');
    Route::post('/print/store', 'store')->middleware('permission:medium_printing_permission|full_printing_permission')->name('printing.store');
    Route::get('/print/edit/{task}', 'edit')->middleware('PrintingTasksMiddleware')->name('printing.edit');
    Route::patch('/print/update/{task}', 'update')->middleware('PrintingTasksMiddleware')->name('printing.update');
    Route::delete('/print/destroy/{task}', 'destroy')->middleware('PrintingTasksMiddleware')->name('printing.destroy');

    Route::get('/print/allWorks', 'allWorks')->name('printing.allWorks');
    Route::post('/print/allWorks', 'allWorks');
    Route::get('/print/workout', 'workout')->middleware('role:user')->name('printing.workout');
    Route::post('/print/workout', 'workout')->middleware('role:user');
    Route::get('/print/worksReport', 'worksReport')->middleware('role:user')->name('printing.worksReport');
    Route::post('/print/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(LaminationController::class)->middleware('auth')->group(function () {
    Route::get('/lam/create', 'create')->middleware('permission:medium_lamination_permission|full_lamination_permission')->name('lamination.create');
    Route::post('/lam/store', 'store')->middleware('permission:medium_lamination_permission|full_lamination_permission')->name('lamination.store');
    Route::get('/lam/edit/{task}', 'edit')->middleware('LaminationTasksMiddleware')->name('lamination.edit');
    Route::patch('/lam/update/{task}', 'update')->middleware('LaminationTasksMiddleware')->name('lamination.update');
    Route::delete('/lam/destroy/{task}', 'destroy')->middleware('LaminationTasksMiddleware')->name('lamination.destroy');

    Route::get('/lam/allWorks', 'allWorks')->name('lamination.allWorks');
    Route::post('/lam/allWorks', 'allWorks');
    Route::get('/lam/workout', 'workout')->middleware('role:user')->name('lamination.workout');
    Route::post('/lam/workout', 'workout')->middleware('role:user');
    Route::get('/lam/worksReport', 'worksReport')->middleware('role:user')->name('lamination.worksReport');
    Route::post('/lam/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(WeldingController::class)->middleware('auth')->group(function () {
    Route::get('/welding/create', 'create')->middleware('permission:medium_welding_permission|full_welding_permission')->name('welding.create');
    Route::post('/welding/store', 'store')->middleware('permission:medium_welding_permission|full_welding_permission')->name('welding.store');
    Route::get('/welding/edit/{task}', 'edit')->middleware('WeldingTasksMiddleware')->name('welding.edit');
    Route::patch('/welding/update/{task}', 'update')->middleware('WeldingTasksMiddleware')->name('welding.update');
    Route::delete('/welding/destroy/{task}', 'destroy')->middleware('WeldingTasksMiddleware')->name('welding.destroy');

    Route::get('/welding/allWorks', 'allWorks')->name('welding.allWorks');
    Route::post('/welding/allWorks', 'allWorks');
    Route::get('/welding/workout', 'workout')->middleware('role:user')->name('welding.workout');
    Route::post('/welding/workout', 'workout')->middleware('role:user');
    Route::get('/welding/worksReport', 'worksReport')->middleware('role:user')->name('welding.worksReport');
    Route::post('/welding/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(CuttingController::class)->middleware('auth')->group(function () {
    Route::get('/cutting/create', 'create')->middleware('permission:medium_cutting_permission|full_cutting_permission')->name('cutting.create');
    Route::post('/cutting/store', 'store')->middleware('permission:medium_cutting_permission|full_cutting_permission')->name('cutting.store');
    Route::get('/cutting/edit/{task}', 'edit')->middleware('CuttingTasksMiddleware')->name('cutting.edit');
    Route::patch('/cutting/update/{task}', 'update')->middleware('CuttingTasksMiddleware')->name('cutting.update');
    Route::delete('/cutting/destroy/{task}', 'destroy')->middleware('CuttingTasksMiddleware')->name('cutting.destroy');

    Route::get('/cutting/allWorks', 'allWorks')->name('cutting.allWorks');
    Route::post('/cutting/allWorks', 'allWorks');
    Route::get('/cutting/workout', 'workout')->middleware('role:user')->name('cutting.workout');
    Route::post('/cutting/workout', 'workout')->middleware('role:user');
    Route::get('/cutting/worksReport', 'worksReport')->middleware('role:user')->name('cutting.worksReport');
    Route::post('/cutting/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(ExtrusionController::class)->middleware('auth')->group(function () {
    Route::get('/extrusion/create', 'create')->middleware('permission:medium_extrusion_permission|full_extrusion_permission')->name('extrusion.create');
    Route::post('/extrusion/store', 'store')->middleware('permission:medium_extrusion_permission|full_extrusion_permission')->name('extrusion.store');
    Route::get('/extrusion/edit/{task}', 'edit')->middleware('ExtrusionTasksMiddleware')->name('extrusion.edit');
    Route::patch('/extrusion/update/{task}', 'update')->middleware('ExtrusionTasksMiddleware')->name('extrusion.update');
    Route::delete('/extrusion/destroy/{task}', 'destroy')->middleware('ExtrusionTasksMiddleware')->name('extrusion.destroy');

    Route::get('/extrusion/allWorks', 'allWorks')->name('extrusion.allWorks');
    Route::post('/extrusion/allWorks', 'allWorks');
    Route::get('/extrusion/workout', 'workout')->middleware('role:user')->name('extrusion.workout');
    Route::post('/extrusion/workout', 'workout')->middleware('role:user');
    Route::get('/extrusion/worksReport', 'worksReport')->middleware('role:user')->name('extrusion.worksReport');
    Route::post('/extrusion/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(RecyclingController::class)->middleware('auth')->group(function () {
    Route::get('/recycling/create', 'create')->middleware('permission:medium_recycling_permission|full_recycling_permission')->name('recycling.create');
    Route::post('/recycling/store', 'store')->middleware('permission:medium_recycling_permission|full_recycling_permission')->name('recycling.store');
    Route::get('/recycling/edit/{task}', 'edit')->middleware('RecyclingTasksMiddleware')->name('recycling.edit');
    Route::patch('/recycling/update/{task}', 'update')->middleware('RecyclingTasksMiddleware')->name('recycling.update');
    Route::delete('/recycling/destroy/{task}', 'destroy')->middleware('RecyclingTasksMiddleware')->name('recycling.destroy');

    Route::get('/recycling/allWorks', 'allWorks')->name('recycling.allWorks');
    Route::post('/recycling/allWorks', 'allWorks');
    Route::get('/recycling/workout', 'workout')->middleware('role:user')->name('recycling.workout');
    Route::post('/recycling/workout', 'workout')->middleware('role:user');
    Route::get('/recycling/worksReport', 'worksReport')->middleware('role:user')->name('recycling.worksReport');
    Route::post('/recycling/worksReport', 'worksReport')->middleware('role:user');
});


Route::controller(FlexoformController::class)->middleware('auth')->group(function () {
    Route::get('/flexoform/create', 'create')->middleware('permission:medium_upff_permission|full_upff_permission')->name('flexoform.create');
    Route::post('/flexoform/store', 'store')->middleware('permission:medium_upff_permission|full_upff_permission')->name('flexoform.store');
    Route::get('/flexoform/edit/{task}', 'edit')->middleware('FlexoformTasksMiddleware')->name('flexoform.edit');
    Route::patch('/flexoform/update/{task}', 'update')->middleware('FlexoformTasksMiddleware')->name('flexoform.update');
    Route::delete('/flexoform/destroy/{task}', 'destroy')->middleware('FlexoformTasksMiddleware')->name('flexoform.destroy');

    Route::get('/flexoform/allWorks', 'allWorks')->name('flexoform.allWorks');
    Route::post('/flexoform/allWorks', 'allWorks');
});


// расчет разницы тиража elog
Route::post('/backend/get-circulations', [CirculationController::class, 'getCirculations'])->middleware('auth');
