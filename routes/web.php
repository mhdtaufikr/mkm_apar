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
Route::get('/mst/apar/detail/public/{id}', [AparController::class, 'mstAparDetailPublic'])->name('mst.Apar.detail.public');


Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index'])->name('checksheet');

    //Dropdown Controller
    Route::get('/dropdown', [DropdownController::class, 'index']);
    Route::post('/dropdown/store', [DropdownController::class, 'store']);
    Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update']);
    Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete']);

    //Rules Controller
    Route::get('/rule', [RulesController::class, 'index']);
    Route::post('/rule/store', [RulesController::class, 'store']);
    Route::patch('/rule/update/{id}', [RulesController::class, 'update']);
    Route::delete('/rule/delete/{id}', [RulesController::class, 'delete']);

    //User Controller
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner']);
    Route::patch('/user/update/{user}', [UserController::class, 'update']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke']);
    Route::get('/user/access/{user}', [UserController::class, 'access']);

     //Apar Controller
     Route::get('/apar/list', [AparController::class, 'index'])->name('list');
     Route::post('/checksheet/scan', [AparController::class, 'checksheet'])->name('apar.check');
     Route::post('/checksheet/store', [AparController::class, 'store']);
     Route::get('apar/detail/{id}', [AparController::class, 'detail']);
     Route::get('apar/generate-pdf/{id}', [AparController::class, 'generatePdf']);


     Route::get('/mst/apar', [AparController::class, 'mstApar'])->name('mst.Apar');
     Route::get('/mst/apar/detail/{id}', [AparController::class, 'mstAparDetail'])->name('mst.Apar.detail');
     Route::get('/generate-qr-code-pdf', [AparController::class, 'generateQrCodePdf'])->name('generate.qr.code.pdf');




});
