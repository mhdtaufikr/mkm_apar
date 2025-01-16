<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\AparController;

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

// Authentication Routes
// Route::get('/', [AuthController::class, 'login'])->name('login');
// Route::get('/auth/login', [AuthController::class, 'postLogin']);
// Route::get('/auth/callback', [AuthController::class, 'handleAzureCallback']);
// Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('request/access', [AuthController::class, 'requestAccess']);
Route::get('/mst/apar/detail/public/{id}', [AparController::class, 'mstAparDetailPublic'])->name('mst.Apar.detail.public')->middleware(['checkRole:IT']);


Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index'])->name('checksheet');

    //Dropdown Controller
    Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:IT']);

    //Rules Controller
    Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:IT']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:IT']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:IT']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:IT']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:IT']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:IT']);

     //Apar Controller
     Route::get('/apar/list', [AparController::class, 'index'])->name('list')->middleware(['checkRole:IT']);
     Route::post('/checksheet/scan', [AparController::class, 'checksheet'])->name('apar.check')->middleware(['checkRole:IT']);
     Route::post('/checksheet/store', [AparController::class, 'store'])->middleware(['checkRole:IT']);
     Route::get('apar/detail/{id}', [AparController::class, 'detail'])->middleware(['checkRole:IT']);
     Route::get('apar/generate-pdf/{id}', [AparController::class, 'generatePdf'])->middleware(['checkRole:IT']);


     Route::get('/mst/apar', [AparController::class, 'mstApar'])->name('mst.Apar')->middleware(['checkRole:IT']);
     Route::get('/mst/apar/detail/{id}', [AparController::class, 'mstAparDetail'])->name('mst.Apar.detail')->middleware(['checkRole:IT']);
     Route::get('/generate-qr-code-pdf', [AparController::class, 'generateQrCodePdf'])->name('generate.qr.code.pdf');




});
