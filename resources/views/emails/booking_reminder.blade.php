<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
</head>
<body>
    <h1>Booking Reminder</h1>
    <p>Dear {{ $booking->name }},</p>
    <p>This is a reminder for your booking on {{ $booking->date }} from {{ $booking->start_time }} to {{ $booking->end_time }} for the service: {{ $booking->service->name }}.</p>
    <p>We look forward to serving you!</p>
</body>
</html>
