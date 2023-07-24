<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RegistrationController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:api'])->group(function () {
    Route::post('eventRegistration', [RegistrationController::class,'eventRegistration'])->name('eventRegistration');
});
    


Route::post('registration', [RegistrationController::class,'registration'])->name('registration');
Route::post('signin', [RegistrationController::class,'signIn'])->name('signin');
Route::get('index', [RegistrationController::class,'users'])->name('index');
// ticket
Route::post('purchaseTicket/{event}', [TicketController::class,'purchaseTicket'])->name('purchaseTicket');
Route::get('events', [TicketController::class,'events'])->name('events');
// Route::get('/generate-barcode', [ProductController::class, 'index'])->name('generate.barcode');