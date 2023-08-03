<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class AttendeesController extends Controller

{


    public function updateAttendee(Request $request, $eventId, $attendeeId)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:attendees,email,' . $attendeeId,
        ]);

        $event = Event::findOrFail($eventId);
        $attendee = $event->attendees()->findOrFail($attendeeId);

        $attendee->update([
            'name' => $request->name,
            'email' => $request->email,
            // Update other fields specific to attendees if needed
        ]);

        return response()->json(['message' => 'Attendee updated successfully', 'data' => $attendee]);
    }

    public function deleteAttendee($eventId, $attendeeId)
    {
        $event = Event::findOrFail($eventId);
        $attendee = $event->attendees()->findOrFail($attendeeId);

        $attendee->delete();

        return response()->json(['message' => 'Attendee deleted successfully']);
    }

    // Other methods as needed for attendee management
}
