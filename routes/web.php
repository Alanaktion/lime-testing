<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RunController;
use App\Http\Controllers\RunTestController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestSuiteController;
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

Route::get('/', [HomeController::class, 'index'])->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('test-suites/archived', [TestSuiteController::class, 'archived'])
    ->name('test-suites.archived');

Route::post('test-suites/{testSuite}/restore', [TestSuiteController::class, 'restore'])
    ->name('test-suites.restore')
    ->withTrashed();

Route::get('test-suites/{testSuite}/archived', [TestController::class, 'archived'])
    ->name('tests.archived');

Route::post('tests/{test}/restore', [TestController::class, 'restore'])
    ->name('tests.restore')
    ->withTrashed();

Route::resource('test-suites', TestSuiteController::class)
    ->except(['create', 'edit']);
Route::resource('test-suites.tests', TestController::class)
    ->shallow()
    ->except(['index', 'edit']);

Route::patch('runs/{run}/tests/{test}', [RunTestController::class, 'update'])
    ->name('runs.run-test.update');
Route::resource('runs', RunController::class)
    ->except(['create', 'edit']);
