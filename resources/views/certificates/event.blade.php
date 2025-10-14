<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Certificate</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .certificate {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 60px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            z-index: 0;
        }
        .certificate-content {
            position: relative;
            z-index: 1;
        }
        .header {
            margin-bottom: 40px;
        }
        .title {
            font-size: 48px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 24px;
            color: #4a5568;
            margin-bottom: 20px;
        }
        .certificate-text {
            font-size: 20px;
            color: #2d3748;
            margin: 30px 0;
            line-height: 1.6;
        }
        .participant-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #667eea;
        }
        .event-details {
            background: #f7fafc;
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #667eea;
        }
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .event-info {
            font-size: 18px;
            color: #4a5568;
            margin: 5px 0;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .signature {
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #2d3748;
            width: 200px;
            margin: 10px auto;
        }
        .date {
            font-size: 16px;
            color: #4a5568;
        }
        .rating {
            font-size: 24px;
            color: #f6ad55;
            margin: 20px 0;
        }
        .stars {
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-content">
            <div class="header">
                <div class="title">CERTIFICATE OF PARTICIPATION</div>
                <div class="subtitle">Event Connect Platform</div>
            </div>

            <div class="certificate-text">
                This is to certify that
            </div>

            <div class="participant-name">
                {{ $user->full_name }}
            </div>

            <div class="certificate-text">
                has successfully participated in the event
            </div>

            <div class="event-details">
                <div class="event-title">{{ $event->title }}</div>
                <div class="event-info"><strong>Organizer:</strong> {{ $event->organizer->full_name }}</div>
                <div class="event-info"><strong>Date:</strong> {{ $event->start_date->format('F j, Y') }}</div>
                <div class="event-info"><strong>Location:</strong> {{ $event->location }}</div>
                <div class="event-info"><strong>Category:</strong> {{ $event->category->name }}</div>
            </div>

            @if($feedback)
            <div class="rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $feedback->rating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <div>Rating: {{ $feedback->rating }}/5</div>
            </div>
            @endif

            <div class="footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div>Event Organizer</div>
                    <div>{{ $event->organizer->full_name }}</div>
                </div>
                <div class="date">
                    Issued on {{ $date }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
