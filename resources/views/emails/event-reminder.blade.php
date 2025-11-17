<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin: -30px -30px 20px -30px;
        }
        .event-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #333;
        }
        .reminder-badge {
            display: inline-block;
            background-color: #ff9800;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🔔 Event Reminder</h1>
        </div>

        <p>Hello {{ $user->name }},</p>

        <div class="reminder-badge">
            ⏰ Tomorrow's Event
        </div>

        <p>This is a friendly reminder that you have registered for the following event which starts <strong>tomorrow</strong>:</p>

        <div class="event-details">
            <h2 style="margin-top: 0; color: #4CAF50;">{{ $event->title }}</h2>
            
            <div class="detail-row">
                <span class="detail-label">📅 Start Date:</span>
                <span class="detail-value">{{ $eventDate }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">🕐 Start Time:</span>
                <span class="detail-value">{{ $eventTime }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">📅 End Date:</span>
                <span class="detail-value">{{ $eventEndDate }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">🕐 End Time:</span>
                <span class="detail-value">{{ $eventEndTime }}</span>
            </div>
            
            @if($event->location)
            <div class="detail-row">
                <span class="detail-label">📍 Location:</span>
                <span class="detail-value">{{ $event->location }}</span>
            </div>
            @endif

            @if($event->event_type)
            <div class="detail-row">
                <span class="detail-label">📋 Event Type:</span>
                <span class="detail-value">{{ ucfirst($event->event_type) }}</span>
            </div>
            @endif

            @if($event->contact_info)
            <div class="detail-row">
                <span class="detail-label">📞 Contact:</span>
                <span class="detail-value">{{ $event->contact_info }}</span>
            </div>
            @endif

            @if($event->description)
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                <strong>Description:</strong>
                <p style="margin: 10px 0;">{{ Str::limit($event->description, 200) }}</p>
            </div>
            @endif

            @if($event->requirements)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0;">
                <strong>Requirements:</strong>
                <p style="margin: 10px 0;">{{ $event->requirements }}</p>
            </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ config('app.frontend_url') }}/events/{{ $event->id }}" class="button">
                View Event Details
            </a>
        </div>

        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <strong>⚠️ Important Reminders:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Please arrive 15 minutes before the event starts</li>
                <li>Bring your registration confirmation</li>
                <li>Check the weather if it's an outdoor event</li>
            </ul>
        </div>

        <p>We look forward to seeing you tomorrow!</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>

        <div class="footer">
            <p>You received this email because you registered for an event on {{ config('app.name') }}.</p>
            <p>If you have any questions, please contact us at {{ config('mail.from.address') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>