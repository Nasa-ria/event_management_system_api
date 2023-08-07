<?php

namespace App\Http\Controllers;

use Google_Client;
use Google\Service;
use App\Models\Event;
use App\Models\Ticket;
use App\Mail\EventMail;
use App\Models\Feedback;
use App\Models\TicketPrice;
use Google\Service\Calendar;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Models\Eventpromotion;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
// use App Google\Service\Calendar\Calendar;


class EventController extends Controller
{

    public function index(Request $request)
    {
        $events = Event::all();
        return response()->json([
            'events' => $events,
        ]);
    }



    public function singleEvent(Request $request, Event $event)
    {
        $feedbacks = Feedback::where('event_id', '=', $event->id)->get();
        if ($feedbacks) {
            return response()->json([
                "feedbacks" => $feedbacks,
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = $request->user();
        // $id = $user->id;

        $validated = $request->validate([
            'event' => 'required',
            'email' => 'required',
            'capacity' => 'required',
            'contact' => 'required',
            'date' => 'required',
            'venue' => 'required',
            'ticketTypesAndPrices'=>'required'

        ]);
        // $ticketTypesAndPrices = [
        //     'General Admission' => 50.00,
        //     'VIP' => 100.00,
        //     'Student' => 25.00,
        // ];
        
        $event = Event::create([
            'event' => $validated['event'],
            'email' => $validated['email'],
            'capacity' => $validated['capacity'],
            'contact' => $validated['contact'],
            'date' => $validated['date'],
            'venue' => $validated['venue']
        ]);  

        $ticketPrice = new TicketPrice();
        $ticketPrice->event_id = $event->id;
        $ticketPrice->ticket_types_and_prices =  $validated['ticketTypesAndPrices'];
        $ticketPrice->save();
        // $email = $event->email;
        // Mail::to($email)->send(new EventMail( $event,$user));
        return response()->json([
            "event" => $event,
            "ticket_price"=>$ticketPrice
        ]);
    }

    public function update(Request $request, $event)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'capacity' => 'required',
            'contact' => 'required',
            'date' => 'required',
            'venue' => 'required',
        ]);

        $event = Event::findOrFail($event);

        $event->update([
            'event' => $request->name,
            'email' => $request->email,
            'capacity' => $request->capacity,
            'contact' => $request->contact,
            'date' => $request->date,
            'venue' => $request->venue
        ]);

        return response()->json(['message' => 'event updated successfully', 'data' => $event]);
    }

  public function destroy(Request $request,Event $event){
        $event -> delete();
        return "deleted";
  }
}
