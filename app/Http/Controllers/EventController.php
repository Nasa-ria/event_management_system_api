<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $event = Event::where('id', '=', $event->id)->get();
       
            return response()->json([
                "event" => $event,
            ]);
        }
    

    public function store(Request $request)
    {
        // $user = $request->user();
        // $id = $user->id;

        $validated = $request->validate([
            'event' => 'required',
            'details' => 'required',
            'capacity' => 'required',
            'date' => 'required',
            'time'=>'required',
            'ticketTypesAndPrices'=>'required|array'

        ]);
        // $ticketTypesAndPrices = [
        //     'General Admission' => 50.00,
        //     'VIP' => 100.00,
        //     'Student' => 25.00,
        // ];
        
        $event = Event::create([
            'event' => $validated['event'],
            'details' => $validated['details'],
            'capacity' => $validated['capacity'],
            'date' => $validated['date'],
            'time' => $validated['time'],
           'ticketTypesAndPrices' =>json_encode($validated['ticketTypesAndPrices'])
        ]);  
        return response()->json([
            "event" => $event
        ]);
    }

    public function update(Request $request, $event)
    {
        $request->validate([
            'event' => 'required|string',
            'details' => 'required|email',
            'capacity' => 'required',
            'time' => 'required',
            'date' => 'required',
            'ticketTypesAndPrices' => 'required',
        ]);

        $event = Event::findOrFail($event);

        $event->update([
            'event' => $request->event,
            'details' => $request->details,
            'capacity' => $request->capacity,
            'time' => $request->time,
            'date' => $request->date,
            'ticketTypesAndPrices' => $request->ticketTypesAndPrices
        ]);

        return response()->json(['message' => 'event updated successfully', 'data' => $event]);
    }

  public function destroy(Request $request,Event $event){
        $event -> delete();
        return "deleted";
  }

  public function fetchEventsToday()
  {
      $today = Carbon::now()->format('Y-m-d');
  
      $eventsToday = Event::whereDate('date', $today)->get();
  
      return $eventsToday;
  }

  public function fetchEventsYesterday()
{
    $yesterday = Carbon::yesterday();
    $events = Event::whereDate('date', $yesterday)->get();

    return $events;
}


public function fetchEventsTomorrow()
{
    $tomorrow = Carbon::tomorrow();
    $events = Event::whereDate('date', $tomorrow)->get();

    return $events;
}

public function fetchEventsDate(Request $request)
{
    $targetDate= $request->targetDate;
    // dd($targetDate);
    $events = Event::whereDate('date', $targetDate)->get();

    return $events;
}
}
