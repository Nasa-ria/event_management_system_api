<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Registration Confirmation</title>
</head>
<body>
    <div style="background-color: #f0f0f0; padding: 20px; font-family: Arial, sans-serif;">
        <h1>Event Registration Confirmation</h1>
        {{-- <p>Hello {{$user->$name}},</p> --}}
        <p>Thank you for registering for the " {{$event-> $event }} " event. We are excited to have you with us!</p>
        <p>Event Details:</p>
        <ul>
            <li><strong>Event Name:</strong> {{$event->$event }}</li>
            <li><strong>Date:</strong> {{$event->$date }}</li>
            <li><strong>Location:</strong> {{$event->$location }}</li>
            <li><strong>Contact:</strong> {{$event-> $contact }}</li>
            <li><strong>Contact:</strong> {{$event-> $attendees }}</li>
        </ul>
        <p>Please present this confirmation email at the event for entry.</p>
        {{-- <p>If you have any questions or need further assistance, feel free to contact us at {{ $event-> $contact }}.</p> --}}
        <p>We look forward to seeing you at the event!</p>
        <p>Best Regards,</p>
        <p>The Event Team</p>
    </div>
</body>
</html>
