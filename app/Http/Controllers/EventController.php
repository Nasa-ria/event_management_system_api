<?php

namespace App\Http\Controllers;

use Google_Client;
use Google\Service;
use App\Models\Event;
use App\Mail\EventMail;
// use Google_Service_Calendar;
use Google\Service\Calendar;
use Illuminate\Http\Request;
use App\Models\Eventpromotion;
use Illuminate\Support\Facades\Mail;


class EventController extends Controller
{
    public function eventRegistration(Request $request )
    {
        $user= $request->user();
        $id = $user->id;

        $validated = $request->validate([
            'event' => 'required|string',
            'email' => 'required',
            'attendees' => 'required',
            'contact' => 'required',
            'date' => 'required',
            'location' => 'required'

        ]);


        $event = Event::create([
            'user_id' => $id, // Corrected the assignment of user_id
            'event' => $validated['event'],
            'email' => $validated['email'],
            'attendees' => $validated['attendees'],
            'contact' => $validated['contact'],
            'date' => $validated['date'],
            'location' => $validated['location']

        ]);
        // dd($event);
          // Synchronize the event date with Google Calendar
          $this->updateCalendarEvent($event);

        $email = $event->email;
        Mail::to($email)->send(new EventMail( $event,$user));
        return response()->json([
            "event" => $event,
        ]);
    }

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
    


    private function updateCalendarEvent(Event $event)
    {
        // require_once require_once './vendor/autoload.php';
        // Initialize Google API client with your credentials
        $client = new Google_Client();
        $client->setAuthConfig('C:\Users\WALULEL\Downloads\event-management-system-api-34a0fb4aff73.json');
        $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS);

        // Create a service to interact with the Google Calendar API
        $service = new Google_Service_Calendar($client);

        // Create a new event in Google Calendar
        $calendarEvent = new Google_Service_Calendar_Event([
            'summary' => $event->event_name,
            'start' => ['dateTime' => $event->event_date],
            'end' => ['dateTime' => $event->event_date],
            // Add other properties of the event as needed
        ]);

        $createdEvent = $service->events->insert('primary', $calendarEvent);

        // Save the event ID from Google Calendar to your database for future updates
        $event->update(['google_calendar_event_id' => $createdEvent->id]);
        return response()->json(['message' => 'Event added to calender successfully']);
    }
}
