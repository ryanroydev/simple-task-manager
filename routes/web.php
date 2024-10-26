<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrashController;
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


Auth::routes();

Route::middleware(['auth'])->group(function () {
    //Task Resource
    Route::resource('tasks', TaskController::class)->only(['index','create','store','edit','destroy']);
    Route::post('tasks/{id}/trash',[TaskController::class,'trash'])->name('tasks.trash');
    Route::post('tasks/{id}/draft',[TaskController::class,'draft'])->name('tasks.draft');
    Route::post('tasks/{id}/updateStatus', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    //trash Task
    Route::resource('trash', TrashController::class)->only(['index','destroy']);

    Route::view('/','home')->name('home');

});

