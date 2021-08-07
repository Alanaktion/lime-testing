<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RunController;
use App\Http\Controllers\RunTestController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestSuiteController;
use Illuminate\Foundation\Application;
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

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('test-suites/archived', [TestSuiteController::class, 'archived'])
    ->name('test-suites.archived');

Route::post('test-suites/{trashedTestSuite}/restore', [TestSuiteController::class, 'restore'])
    ->name('test-suites.restore');

Route::resource('test-suites', TestSuiteController::class)
    ->except(['create', 'edit']);
Route::resource('test-suites.tests', TestController::class)
    ->shallow()
    ->except(['index', 'edit']);

Route::resource('runs', RunController::class);
Route::resource('runs.run-tests', RunTestController::class)
    ->shallow();
