<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManageMailController;
use App\Http\Controllers\SaveMailController;
use App\Http\Controllers\UtilityController;
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

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/employees', [EmployeeController::class, 'index'])->name('employee')->middleware('auth');
Route::get('/archives/create', [SaveMailController::class, 'index'])->name('mails.create')->middleware('auth');
Route::get('/mails/inbox', [ManageMailController::class, 'indexInbox'])->name('mails.inbox')->middleware('auth');
Route::get('/mails/sent', [ManageMailController::class, 'indexSent'])->name('mails.sent')->middleware('auth');
Route::get('/errors/{error}', [UtilityController::class, 'errorHandler'])->name('error');

Route::get('/login', [LoginController::class, 'show'])->name('auth');
Route::post('/login', [LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
