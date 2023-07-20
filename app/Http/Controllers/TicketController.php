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
            $ticket['event_id'] = $event->id
        ]);
          return $ticket;

    }
    public  function purchaseTicket(Request $request, Event $event){
        $quantity = $request->input('quantity');
        $email = $request->input('email');

          $ticketCode =[];
            for ($x = 0; $x < $quantity ; $x++) {
                $ticket = $this-> createTicket($request, $event);
            //   create uuid
            $ticketCode[] =Str::uuid();;
                // updating balance
                $ticket->update([
                    'status' => "purchase",
                    'email' => $email,
                    'uniqueCode'=>$ticketCode
                ]);

                $event->decrement('attendees');
         
                Mail::to($email)->send(new TicketMail($ticket));
                if($event->attendees == 0){
                    return "sold out";
                }
              }

    }


}
