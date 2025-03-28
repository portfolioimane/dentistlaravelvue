<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
            display: block;
        }
        strong {
            color: #007BFF;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Reminder</h1>
        <p>Dear {{ $booking->name }},</p>
        <p>This is a reminder for your booking:</p>
        <p><strong>Date:</strong> {{ $booking->formattedDate() }}</p>
        <p><strong>Time:</strong> From {{ $booking->formattedStartTime() }} to {{ $booking->formattedEndTime() }}</p>
        <p><strong>Service:</strong> {{ $booking->service->name }}</p>
        <p>We look forward to serving you!</p>
    </div>
    <div class="footer">
        <p>Thank you for choosing us!</p>
    </div>
</body>
</html>

