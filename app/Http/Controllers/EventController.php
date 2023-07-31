<?php

namespace App\Http\Controllers;
use Google_Client;
use Google\Service;
use App\Models\Event;
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
        $service_account_name = getenv('GOOGLE_SERVICE_ACCOUNT_NAME');
        $key_file_location = base_path() . getenv('GOOGLE_KEY_FILE_LOCATION');

        $key = file_get_contents($key_file_location);
        $client->setAuthConfig('C:\Users\WALULEL\Downloads\event-management-system-api-34a0fb4aff73.json');
        $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS);
        $scopes = ['https://www.googleapis.com/auth/calendar'];

        $cred = new \Google_Auth_AssertionCredentials(
            $service_account_name,
            $scopes,
            $key
        );
        // Create a service to interact with the Google Calendar API
        $service = new Google_Service_Calendar($client);
        $client->setAssertionCredentials($cred);
        $this->app->singleton(\Google_Service_Calendar::class, function ($app) use ($client) {
            return new \Google_Service_Calendar($client);
        });

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

    public function calender(Google_Service_Calendar $client){
        $calendarList = $client->calendarList->listCalendarList();
        
}
public function submitFeedback(Request $request, $id)
{
    $event_id = Event::findOrFail($id);

    $validatedData = $request->validate([
        'feedback' => 'required|string|max:255',
        'event_id'  =>'required'
    ]);

    // You can store the feedback in the database or perform any other actions here.
    $feedback = Feedback ::create([
        'feedback' => $request->feedback,
        'event_id'=>$event_id
    ]);
    return $feedback;
}

public function events(){
    $events=  Event::all();
     return response()->json([
      
        "events"=>$events,
     ]);
}


public function event(Request $request, Event $event){

    $feedbacks = Feedback::where('event_id', '=', $event->id)->get();
    if($feedbacks){
            return response()->json([
                "feedbacks"=>$feedbacks,
             ]);
  
    }
   
}
}