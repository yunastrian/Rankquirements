<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequirementController;

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

Auth::routes();

// GET
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/project/{id}', [ProjectController::class, 'index'])->name('project');
Route::get('/project/{idProject}/phase/{phaseNumber}', [PhaseController::class, 'index'])->name('phase');

// POST
Route::post('/project/add', [ProjectController::class, 'add']);
Route::post('/project/addmember', [ProjectController::class, 'addMember']);
Route::post('/project/updatephase', [ProjectController::class, 'updatePhase']);
Route::post('/requirement/add', [RequirementController::class, 'add']);

// Redirect
Route::redirect('/home', '/');
