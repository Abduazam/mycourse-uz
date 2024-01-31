<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

/**
 * Dashboard routes.
 */
Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['role:admin'])->group(static function () {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
        // Route::get('/keyboards', [\App\Http\Controllers\Dashboard\KeyboardController::class, 'index'])->name('keyboards');
        Route::resource('courses', \App\Http\Controllers\Dashboard\CourseController::class)->except(['store', 'update', 'destroy']);
        Route::resource('lessons', \App\Http\Controllers\Dashboard\LessonController::class)->except(['store', 'update', 'destroy']);
        Route::resource('tasks', \App\Http\Controllers\Dashboard\TaskController::class)->except(['store', 'update', 'destroy']);
        Route::resource('questions', \App\Http\Controllers\Dashboard\QuestionController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('users', \App\Http\Controllers\Dashboard\BotUserController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('students', \App\Http\Controllers\Dashboard\StudentController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('applications', \App\Http\Controllers\Dashboard\ApplicationController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('user-tasks', \App\Http\Controllers\Dashboard\UserTaskController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('messages', \App\Http\Controllers\Dashboard\MessageController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('reminders', \App\Http\Controllers\Dashboard\ReminderController::class)->except(['store', 'update', 'destroy']);
    });
});

/**
 * Auth routes.
 */
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
]);
