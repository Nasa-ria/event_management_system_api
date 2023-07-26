<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Mail\TicketMail;
use App\Models\User; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public  function purchaseTicket(Request $request){
        $request->validate([
            'quantity' => 'required',
            'email' => 'required',
        ]);
        
        $ticketCode = [];
        for ($x = 0; $x < $request->quantity; $x++) {
            $ticket = $this->createTicket($request);
            // Create uuid
            $ticketCode[] = Str::uuid();
    $uniqueCode = array_push($ticketCode); // Generate a unique code as a string

            $ticket->status = "purchase"; // Set the status attribute
            $ticket->email = $request->email; // Set the email attribute
            $ticket->uniqueCode = json_encode( $ticketCode); // Set the uniqueCode attribute
            $ticket->save(); // Save the changes to the ticket
        
            $ticketCode[] = $uniqueCode; // Store the uniqueCode in the array for later use
        
            // $user= $request->user()->id;
            $event = Event::where('event', '=', request()->event_name)->first();
            $event->decrement('attendees');
            if ($event->attendees == 0) {
                return "sold out";
            }
        }
        
        $email = $request->email;
        Mail::to($email)->send(new TicketMail($ticket,$event));
        return $ticket;
    }

    public function events(){
        $events=  Event::all();
         return response()->json([
            "users"=>$events,
         ]);
    }
}
