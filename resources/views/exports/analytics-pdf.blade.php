<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Export</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #3b82f6; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Event Analytics Report</h1>
    <p>Exported on: {{ now()->format('Y-m-d H:i') }}</p>

    <h2>📊 Summary</h2>
    <table>
        @foreach($stats as $key => $value)
            <tr>
                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                <td>{{ is_numeric($value) ? number_format($value) : $value }}</td>
            </tr>
        @endforeach
    </table>

    <h2>🏆 Top Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Participants</th>
                <th>Revenue</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topEvents as $event)
                <tr>
                    <td>{{ $event['title'] }}</td>
                    <td>{{ $event['participants_count'] }}</td>
                    <td>Rp {{ number_format($event['price']) }}</td>
                    <td>{{ $event['category']['name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>