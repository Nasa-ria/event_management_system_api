<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
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
    Route::post('eventRegistration', [EventController::class,'eventRegistration'])->name('eventRegistration');
    Route::post('purchaseTicket', [TicketController::class,'purchaseTicket'])->name('purchaseTicket');
    Route::get('events', [TicketController::class,'events'])->name('events');
});
    


Route::post('registration', [UserController::class,'registration'])->name('registration');
Route::post('signin', [UserController::class,'signIn'])->name('signin');
Route::get('index', [UserController::class,'users'])->name('index');
Route::get('calender', [EventController::class,'calender'])->name('calender');
Route::get('event/{id}', [EventController::class, 'events'])->name('events');
Route::post('event/{id}/feedback', [EventController::class, 'submitFeedback'])->name('submitFeedback');


Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');

// ticket


// For barcode scanning
Route::get('/scan-ticket/{ticketCode}', [TicketController::class,'scanTicket'])->name('scanTicket');

// Route::get('/generate-barcode', [ProductController::class, 'index'])->name('generate.barcode');
#replace awesomepaymentgateway with actual payment package
// composer require awesomepaymentgateway/awesome-sdk
