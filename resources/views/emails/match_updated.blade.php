<!DOCTYPE html>
<html>
<head>
    <title>Match Update Notification</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>A match you pinned has been updated:</p>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th align="left">Match ID</th>
            <td>{{ $match->id }}</td>
        </tr>
        <tr>
            <th align="left">Tournament</th>
            <td>{{ $match->tournament->name }}</td>
        </tr>
        <tr>
            <th align="left">Category</th>
            <td>{{ $match->category->name }}</td>
        </tr>
        <tr>
            <th align="left">Stage</th>
            <td>
                @if(isset($changes['stage']))
                    <strong>{{ $changes['stage']['old'] }}</strong> ➝ <strong>{{ $changes['stage']['new'] }}</strong>
                @else
                    {{ $match->stage }}
                @endif
            </td>
        </tr>
        <tr>
            <th align="left">Date</th>
            <td>
                @if(isset($changes['match_date']))
                    <strong>{{ $changes['match_date']['old'] }}</strong> ➝ <strong>{{ $changes['match_date']['new'] }}</strong>
                @else
                    {{ $match->match_date }}
                @endif
            </td>
        </tr>
        <tr>
            <th align="left">Time</th>
            <td>
                @if(isset($changes['match_time']))
                    <strong>{{ $changes['match_time']['old'] }}</strong> ➝ <strong>{{ $changes['match_time']['new'] }}</strong>
                @else
                    {{ $match->match_time }}
                @endif
            </td>
        </tr>
    </table>

    <p>
        <a href="{{ url('/favorites') }}" 
           style="display: inline-block; padding: 10px 15px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
            View Your Favorites
        </a>
    </p>

    <p>Thank you,</p>
    <p><strong>Badminton Tournament System</strong></p>
</body>
</html>
