<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container" style="padding: 1rem; background: #f5f5f5;">
        <div style="background-color: #f0f0f0; padding: 20px; font-family: Arial, sans-serif;">
            <h1>Event Ticket</h1>
            <p>Hello,</p>
            <p>Thank you for purchasing a ticket to our event! Below is your ticket information:</p>
            <ul>
                <li><strong>Event Name:</strong> {{ $event->name }}</li>
                <li><strong>Date:</strong> {{ $event->date }}</li>
                <li><strong>Location:</strong> {{ $event->location }}</li>
                <li><strong>Ticket Code:</strong>  @foreach (json_decode($ticket->uniqueCode) as $code)
                    <ul>
                        <li>{{$code}}</li>
                    </ul>
                    @endforeach</li>
            </ul>
            <p>Please present this ticket code at the event for entry. We look forward to seeing you there!</p>
            <p>Thank you,</p>
            <p>The Event Team</p>
           
        </p>
    </div>
</body>
</html>