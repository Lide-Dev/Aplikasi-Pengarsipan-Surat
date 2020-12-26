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

Route::get('/mails/inbox', [ManageMailController::class, 'inboxIndex'])->name('mails.inbox')->middleware('auth');
Route::get('/mails/inbox/create', [SaveMailController::class, 'inboxIndex'])->name('mails.inbox.create')->middleware('auth');
Route::get('/mails/inbox/create/copy', [SaveMailController::class, 'getTembusan'])->name('mails.inbox.copy.create')->middleware(['auth', 'snakecase']);
Route::post('/mails/inbox/create', [SaveMailController::class, 'createMail'])->name('mails.inbox.create.post')->middleware(['auth', 'snakecase']);
Route::post('/mails/inbox/create/copy', [SaveMailController::class, 'createTembusan'])->name('mails.sent.copy')->middleware(['auth', 'snakecase']);

Route::get('/mails/sent', [ManageMailController::class, 'sentIndex'])->name('mails.sent')->middleware('auth');
Route::get('/mails/sent/create', [SaveMailController::class, 'sentIndex'])->name('mails.sent.create')->middleware('auth');
Route::get('/mails/sent/create/copy', [SaveMailController::class, 'getTembusan'])->name('mails.sent.copy')->middleware(['auth', 'snakecase']);
Route::post('/mails/sent/create', [SaveMailController::class, 'createMail'])->name('mails.sent.create.post')->middleware(['auth', 'snakecase']);
Route::post('/mails/sent/create/copy', [SaveMailController::class, 'createTembusan'])->name('mails.sent.copy.create')->middleware(['auth', 'snakecase']);

Route::get('/errors/{error}', [UtilityController::class, 'errorHandler'])->name('error');

Route::get('/login', [LoginController::class, 'show'])->name('auth');
Route::post('/login', [LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
