<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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


Route::get('/', [TaskController::class, 'index'])->name('home');

Route::get('/register', [SessionsController::class, 'create']);
Route::post('/register', [SessionsController::class, 'store'])->name('sessions.store');
Route::get('/login', [SessionsController::class, 'createLogin'])->name('login');;
Route::post('/login', [SessionsController::class, 'login'])->name('sessions.login');

Route::resources([
    'task' => TaskController::class,
]);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [SessionsController::class, 'destroy']);
});


Route::middleware(['auth'])->group(function () {
    Route::post('/task',           [TaskController::class, 'store'])->name('task.store');
    Route::put('/task/{id}',       [TaskController::class, 'update']);
    Route::patch('/task/{task}/completed', [TaskController::class,'completed']);
    Route::get('/task/{task}/notify', [TaskController::class, 'notifyUser']);
})->middleware('authorize.permission:create_task,read_task,update_task,delete_task');;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('user/{user}/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');  
    Route::get('user/dashboard/admin', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
});
