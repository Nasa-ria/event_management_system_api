<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('registration', [RegistrationController::class,'registration'])->name('registration');
Route::post('signin', [RegistrationController::class,'signIn'])->name('signin');
Route::get('index', [RegistrationController::class,'index'])->name('index');
Route::get('/generate-barcode', [ProductController::class, 'index'])->name('generate.barcode');