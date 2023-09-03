<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Ticket;
use App\Mail\EventMail;

use Illuminate\Http\Request;


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
            'start_time'=>'required',
            'end_time'=>'required',
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
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
           'ticketTypesAndPrices' =>json_encode($validated['ticketTypesAndPrices'])
        ]);  
        return response()->json([
            "event" => $event
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event' => 'required',
            'details' => 'required',
            'capacity' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'ticketTypesAndPrices' => 'required|array'
        ]);
    
        $event->update([
            'event' => $request->input('event'),
            'details' => $request->input('details'),
            'capacity' => $request->input('capacity'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'date' => $request->input('date'),
            'ticketTypesAndPrices' => $request->input('ticketTypesAndPrices')
        ]);
    
        return response()->json(['message' => 'Event updated successfully', 'data' => $event]);
    }
  
    
    
    
    
    

  public function destroy(Request $request,Event $event){
        $event -> delete();
        return "deleted";
  }


  public function fetchEventsByDate(Request $request)
  
  {
    $date =$request->input('targetDate') ;

      $eventdate = Event::whereDate('date',$date )->get();
      if ($eventdate ->isEmpty()) {
        return "No event happening today";
    }
      return $eventdate;
  }

  public function fetchEventsToday()
{
    $today= Carbon::now();

    $eventsToday = Event::whereDate('date', $today)->get();
    if ($eventsToday ->isEmpty()) {
        return "No event happening today";
    }
    return$eventsToday ;
     
 
}


public function fetchEventsTomorrow()
{
    $tomorrow = Carbon::tomorrow();

    $eventstomorrow = Event::whereDate('date',  $tomorrow)->get();

    if ($eventstomorrow->isEmpty()) {
        return "No event happening tomorrow";
    }
    return $eventstomorrow;
}

public function fetchEventsYesterday()
{
    $yesterday = Carbon::yesterday();

    $eventsyesterday =  Event::whereDate('date', $yesterday)->get();
    if ($eventsyesterday ->isEmpty()) {
        return "No event happening yesterday";
    }
    return  $eventsyesterday;
}
}
