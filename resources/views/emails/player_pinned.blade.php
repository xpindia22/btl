<!DOCTYPE html>
<html>
<head>
    <title>Player {{ $action }} Notification</title>
</head>
<body>
    <h3>Hello {{ $user->name }},</h3>
    <p>You have <strong>{{ $action }}</strong> the following player:</p>

    <table border="1" cellpadding="5">
        <tr>
            <th>Player Name</th>
            <td>{{ $player->name }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $player->dob }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $player->sex }}</td>
        </tr>
    </table>

    <p>Thank you,<br> Badminton Tournament System</p>
</body>
</html>
