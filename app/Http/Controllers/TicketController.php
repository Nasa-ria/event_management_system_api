<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User; 
use App\Models\Ticket;
use App\Mail\TicketMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Picqer\Barcode\BarcodeGeneratorHTML;

class TicketController extends Controller
{ 

    private function createTicket(Request $request){
        $user= $request->user()->id;
        $request->validate([
            'event_name' => 'required',
        ]);
    
        // Fetch the event based on the event name
        $event = Event::where('event', '=', $request->event_name)->first();
    
        if (!$event) {
            // Handle the case where the event is not found
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        $ticket = Ticket::create([
            'event_name' => $request->event_name,
            'event_id' => $event->id
        ]);
    
        return $ticket;

    }

    public function purchaseTicket(Request $request)
{
    $request->validate([
        'quantity' => 'required',
        'email' => 'required',
        'ticket_type' => 'required' // Corrected the field name 'ticket type' to 'ticket_type'
    ]);

    // Create a new ticket and assign the attributes based on the request data
    $ticket = $this->createTicket($request);
    $ticket->purchase = $ticket->status; // Set the status attribute (assuming status exists as an attribute in the Ticket model)
    // If you want to set the status based on some specific condition, update this line accordingly.
    $ticket->email = $request->email; // Set the email attribute
    $ticket->ticket_code = json_encode($this->generateBarcode($request->quantity)); // Set the ticket_code attribute using the generated barcode
    $ticket->ticket_type = $request->input('ticket_type'); // Set the ticket_type attribute

    // Save the changes to the ticket
    $ticket->save();

    // Update the attendees count for the event
    $event = Event::where('event', '=', request()->event_name)->first();
    $event->decrement('attendees');
    if ($event->attendees == 0) {
        return "sold out";
    }

    // Send the ticket information to the user via email
    Mail::to($request->email)->send(new TicketMail($ticket, $event));

    return $ticket;
}
    private function generateBarcode($quantity)
    {
        // Assuming you want to generate multiple barcodes based on the given quantity.
        $generator = new BarcodeGeneratorHTML();
        $barcodes = [];
        
        for ($i = 0; $i < $quantity; $i++) {
        
            $ticketCode = 'TCK-' . strtoupper(Str::random(8));
            $barcodes[] = $ticketCode;
        }
        
        return $barcodes;
    }
    

      public function scanTicket($ticketCode)
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->first();
        
        if ($ticket) {
            // Ticket found, do something with the ticket information
            return response()->json(['ticket' => $ticket]);
        } else {
            // Ticket not found in the database
            return response()->json(['error' => 'Ticket not found'], 404);
        }
    }

    public function processPayment(Request $request)
    {
        // Get payment gateway credentials from configuration
        $gatewayKey = config('app.payment_gateway_key');
        $gatewaySecret = config('app.payment_gateway_secret');

        // Initialize the payment gateway SDK
        $paymentGateway = new AwesomeSDK($gatewayKey, $gatewaySecret);

        // Gather payment data from the request (e.g., amount, currency, etc.)
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        // ...

        // Create a payment transaction with the gateway
        $transactionId = $paymentGateway->createTransaction($amount, $currency);

        // Handle successful payment response
        if ($transactionId) {
            // Process the successful payment and update your database accordingly
            // For example, save the transaction ID and update the order status

            return response()->json(['message' => 'Payment successful!', 'transaction_id' => $transactionId]);
        } else {
            // Handle failed payment
            // For example, redirect back with an error message
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }
   
}
