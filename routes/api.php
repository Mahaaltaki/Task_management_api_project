<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
// Route::apiResource('/users',UserController::class);
// Route::apiResource('/tasks',TaskController::class);
// Route::apiResource('/projects',ProjectController::class);

Route::get('/UsersWithTasks',[ProjectController::class,'getUsersWithTasks']);
Route::middleware(['role:manager'])->group(function () {
    Route::post('/tasks', 'TaskController@store')->name('tasks.store')->middleware('hours'); // إضافة المهام
    Route::put('/tasks/{task}', 'TaskController@update')->name('tasks.update')->middleware('hours'); // تعديل المهام
});
Route::middleware(['role:developer'])->group(function () {
    Route::patch('/tasks/{task}/status', 'TaskController@updateStatus')->name('tasks.updateStatus')->middleware('hours');
});
Route::middleware(['role:tester'])->group(function () {
    Route::post('/tasks/{task}/notes', 'TaskController@addNote')->name('tasks.addNote')->middleware('hours');
});
