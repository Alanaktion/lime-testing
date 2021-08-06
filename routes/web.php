<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestSuiteController;
use Illuminate\Foundation\Application;
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

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/test-suites', [TestSuiteController::class, 'index'])->name('test-suites');
Route::post('/test-suites', [TestSuiteController::class, 'store']);
Route::get('/test-suites/{suite}', [TestSuiteController::class, 'show']);
