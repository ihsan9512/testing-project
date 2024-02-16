<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/task/{id?}', [HomeController::class, 'task'])->name('task');
Route::post('/task_store/{id?}', [HomeController::class, 'task_store'])->name('task_store');
Route::post('/task_delete', [HomeController::class, 'task_delete'])->name('task_delete');