<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventpromotion;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // private function updateCalendarEvent(Event $event)
    // {
    //     // require_once require_once './vendor/autoload.php';
    //     // Initialize Google API client with your credentials
    //     $client = new Google_Client();
    //     $service_account_name = getenv('GOOGLE_SERVICE_ACCOUNT_NAME');
    //     $key_file_location = base_path() . getenv('GOOGLE_KEY_FILE_LOCATION');

    //     $key = file_get_contents($key_file_location);
    //     $client->setAuthConfig('C:\Users\WALULEL\Downloads\event-management-system-api-34a0fb4aff73.json');
    //     $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS);
    //     $scopes = ['https://www.googleapis.com/auth/calendar'];

    //     $cred = new \Google_Auth_AssertionCredentials(
    //         $service_account_name,
    //         $scopes,
    //         $key
    //     );
    //     // Create a service to interact with the Google Calendar API
    //     $service = new Google_Service_Calendar($client);
    //     $client->setAssertionCredentials($cred);
    //     $this->app->singleton(\Google_Service_Calendar::class, function ($app) use ($client) {
    //         return new \Google_Service_Calendar($client);
    //     });

    //     // Create a new event in Google Calendar
    //     $calendarEvent = new Google_Service_Calendar_Event([
    //         'summary' => $event->event_name,
    //         'start' => ['dateTime' => $event->event_date],
    //         'end' => ['dateTime' => $event->event_date],
    //         // Add other properties of the event as needed
    //     ]);

    //     $createdEvent = $service->events->insert('primary', $calendarEvent);

    //     // Save the event ID from Google Calendar to your database for future updates
    //     $event->update(['google_calendar_event_id' => $createdEvent->id]);
    //     return response()->json(['message' => 'Event added to calender successfully']);
    // }

    // public function calender(Google_Service_Calendar $client){
    //     $calendarList = $client->calendarList->listCalendarList();
        
    //    }

    public function createEventPromotion(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string',
            'promotion_type' => 'required|string|in:discount,free_item,other_promo',
            'discount' => 'required_if:promotion_type,discount|numeric',
            // Add more specific validation rules for other promotion types if needed
        ]);
    
        // Save the event promotion to the database
        $eventPromotion = Eventpromotion::create([
            'event_name' => $request->event_name,
            'promotion_type' => $request->promotion_type,
            'discount' => $request->discount,
            // Add other fields for other promotion types if needed
        ]);
    
        return response()->json(['message' => 'Event promotion created successfully', 'data' => $eventPromotion]);
    }
    
    // public function processPayment(Request $request)
    // {
    //     // Get payment gateway credentials from configuration
    //     $gatewayKey = config('app.payment_gateway_key');
    //     $gatewaySecret = config('app.payment_gateway_secret');

    //     // Initialize the payment gateway SDK
    //     $paymentGateway = new AwesomeSDK($gatewayKey, $gatewaySecret);

    //     // Gather payment data from the request (e.g., amount, currency, etc.)
    //     $amount = $request->input('amount');
    //     $currency = $request->input('currency');
    //     // ...

    //     // Create a payment transaction with the gateway
    //     $transactionId = $paymentGateway->createTransaction($amount, $currency);

    //     // Handle successful payment response
    //     if ($transactionId) {
    //         // Process the successful payment and update your database accordingly
    //         // For example, save the transaction ID and update the order status

    //         return response()->json(['message' => 'Payment successful!', 'transaction_id' => $transactionId]);
    //     } else {
    //         // Handle failed payment
    //         // For example, redirect back with an error message
    //         return back()->with('error', 'Payment failed. Please try again.');
    //     }
    // }
}
