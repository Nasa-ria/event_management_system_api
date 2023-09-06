<?php

use App\Models\User;
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
    Route::post('event/store', [EventController::class,'store'])->name('store_event');
    Route::put('event/update/{event}', [EventController::class,'update'])->name('update_event');
});

Route::get('events', [EventController::class,'index'])->name('events');
Route::get('event/{id}', [EventController::class, 'events'])->name('events');
Route::get('fetchEventsToday', [EventController::class, 'fetchEventsToday'])->name('fetchEventsToday');
Route::get('fetchEventsYesterday', [EventController::class, 'fetchEventsYesterday'])->name('fetchEventsYesterday');
Route::get('fetchEventsTomorrow', [EventController::class, 'fetchEventsTomorrow'])->name('fetchEventsTomorrow');
Route::get('fetchEventsDate', [EventController::class, 'fetchEventsByDate'])->name('fetchEventsDate');
Route::get('event/singleEvent', [EventController::class,'singleEvent'])->name('singleEvent');

Route::post('user/registration', [UserController::class,'registration'])->name('registration');
Route::put('user/update/{user}', [UserController::class,'update'])->name('update');
Route::post('signin', [UserController::class,'signIn'])->name('signin');
Route::get('logout', [UserController::class,'logout'])->name('logout');
Route::get('user/profile/{user}', [UserController::class,'profile'])->name('profile');
Route::get('login/google', [UserController::class,'loginWithGoogle'])->name('loginWithGoogle');
Route::get('login/google/callback', [UserController::class,'loginWithGoogleCallback'])->name('loginWithGoogleCallback');  
  
// Route::get('events', [TicketController::class,'events'])->name('events');
Route::get('singleEvent', [TicketController::class,'singleEvent'])->name('singleEvent');
Route::post('purchaseTicket', [TicketController::class,'purchaseTicket'])->name('purchaseTicket');

 

// For barcode scanning
Route::get('/scan-ticket/{ticketCode}', [TicketController::class,'scanTicket'])->name('scanTicket');

Route::post('/payment', [PaymentController::class, 'payment'])->name('payment');