<?php

namespace App\Http\Controllers;

use Google_Client;
use Google\Service;
use App\Models\Event;
use App\Models\Ticket;
use App\Mail\EventMail;
use App\Models\Feedback;
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
        $id = $user->id;

        $validated = $request->validate([
            'event' => 'required',
            'email' => 'required',
            'capacity' => 'required',
            'contact' => 'required',
            'date' => 'required',
            'venue' => 'required'

        ]);
        $event = Event::create([
            'user_id' => $id, // Corrected the assignment of user_id
            'event' => $validated['event'],
            'email' => $validated['email'],
            'capacity' => $validated['capacity'],
            'contact' => $validated['contact'],
            'date' => $validated['date'],
            'venue' => $validated['venue']
        ]);
        // $email = $event->email;
        // Mail::to($email)->send(new EventMail( $event,$user));
        return response()->json([
            "event" => $event,
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

    public function getfeedback(Request $request, $id)
    {
        $event = Event::findorfail($id); #pass id to view
        return "hello word";
    }
    public function submitfeedback(Request $request)
    {

        $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        // You can store the feedback in the database or perform any other actions here.
        $feedback = Feedback::create([
            'feedback' => $request->feedback,
            'event_id' => $event_id
        ]);
        return $feedback;
    }

    public function geteventwithticket(Request $request, $id)
    {
        // Get a ticket and retrieve its associated event
        $ticket = Ticket::find($id);
        $event = $ticket->event; // This will return the event to which the ticket belongs
        return $event;
    }
}
