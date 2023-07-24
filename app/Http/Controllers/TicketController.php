<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Mail\TicketMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{ 

    private function createTicket(Request $request, Event $event){
        $request->validate([
            'event_name' => 'required',
        ]);
        $ticket = Ticket::create([
            'event_name' => request()->event_name,
            'event_id' => $event->id
        ]);
          return $ticket;

    }

    public  function purchaseTicket(Request $request, Event $event){
        // $quantity = $request->input('quantity');
        // $email = $request->input('email');
        $request->validate([
            'quantity' => 'required',
            'email' => 'required',
        ]);
        
        $ticketCode = [];
        for ($x = 0; $x < $request->quantity; $x++) {
            $ticket = $this->createTicket($request, $event);
            // Create uuid
            $ticketCode[] = Str::uuid();
    $uniqueCode = array_push($ticketCode); // Generate a unique code as a string

            $ticket->status = "purchase"; // Set the status attribute
            $ticket->email = $request->email; // Set the email attribute
            $ticket->uniqueCode = json_encode( $ticketCode); // Set the uniqueCode attribute
            $ticket->save(); // Save the changes to the ticket
        
            $ticketCode[] = $uniqueCode; // Store the uniqueCode in the array for later use
        
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
