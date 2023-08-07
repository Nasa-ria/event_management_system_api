<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Ticket;
use App\Mail\TicketMail;
use App\Models\Attendees;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Picqer\Barcode\BarcodeGeneratorHTML;

class TicketController extends Controller
{

    public function getTicket(Request $request, $id)
    {
        $event = Event::findorfail($id); #pass id to view
        return "hello word";
    }

    private function createTicket(Request $request)
    {
        $ticket = Ticket::create([
            'event_id' => $request->event_id
        ]);
        return $ticket;
    }

    public function purchaseTicket(Request $request)
    {
        $request->validate([
            'quantity' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'name' => 'required',
            'price' => 'required',
            'ticket_type' => 'required|in:reguler,fixed,double'
        ]);
        // dd($request->all());

        // Create a new ticket and assign the attributes based on the request data
        $ticket = $this->createTicket($request);
        dd($ticket);
        $ticket->status = "purchase";
        $ticket->quantity = $request->quantity;
        $ticket->ticket_code = json_encode($this->generateBarcode($request->quantity)); // Set the ticket_code attribute using the generated barcode
        $ticket->ticket_type = $request->input('ticket_type');
        $ticket->save();
        
        //  if('user exist'){
        //     return "pass user info ";
        //  }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact
            ]);
        //  }
        $attendees = Attendees::create([
            'user_id' =>$user->id,
            'event_id'=>$request->event_id
        ]);

        // Update the attendees count for the event
        $event = Event::where('id', '=', $attendees->event_id)->first();
        $event->decrement('capacity');
        if ($event->capacity == 0) {
            return "sold out";
        }

        // Send the ticket information to the user via email
        // Mail::to($request->email)->send(new TicketMail($ticket, $event));

        return response()->json(['ticket' => $ticket,'attendees'=> $attendees,'user'=>$user]);
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
}
