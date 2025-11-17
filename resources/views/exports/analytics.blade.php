<table>
    <thead>
        <tr><th colspan="2">Analytics Export</th></tr>
    </thead>
    <tbody>
        <tr><td colspan="2"><strong>📊 Stats</strong></td></tr>
        @foreach($data['stats'] as $key => $value)
            <tr><td>{{ $key }}</td><td>{{ $value }}</td></tr>
        @endforeach

        <tr><td colspan="2"><strong>🏆 Top Events</strong></td></tr>
        @foreach($data['top_events'] as $event)
            <tr>
                <td>{{ $event['title'] }}</td>
                <td>{{ $event['participants_count'] }} participants | Rp {{ number_format($event['price']) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>