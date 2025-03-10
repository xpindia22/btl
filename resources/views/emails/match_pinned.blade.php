<!DOCTYPE html>
<html>
<head>
    <title>Match {{ $action }} Notification</title>
</head>
<body>
    <h3>Hello {{ $user->name }},</h3>
    <p>You have <strong>{{ $action }}</strong> the following match:</p>

    <table border="1" cellpadding="5">
        <tr>
            <th>Match ID</th>
            <td>{{ $match->id }}</td>
        </tr>
        <tr>
            <th>Tournament</th>
            <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Category</th>
            <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Stage</th>
            <td>{{ $match->stage ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $match->match_date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Time</th>
            <td>{{ $match->match_time ?? 'N/A' }}</td>
        </tr>
    </table>

    <p>Thank you,<br> Badminton Tournament System</p>
</body>
</html>
