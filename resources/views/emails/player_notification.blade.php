<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Player {{ ucfirst($action) }} Notification</title>
</head>
<body>
    <h2>Player {{ ucfirst($action) }} Notification</h2>
    <p><strong>Player Name:</strong> {{ $player->name }}</p>
    <p><strong>Email:</strong> {{ $player->email }}</p>
    <p><strong>Mobile:</strong> {{ $player->mobile }}</p>
    <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($player->dob)->format('Y-m-d') }}</p>
    <p><strong>Gender:</strong> {{ $player->sex }}</p>
    <p><strong>Modified By:</strong> {{ $modifiedBy }}</p>
    <hr>
    <p>If this was not initiated by you, please contact the admin at {{ $adminEmail }}.</p>
    <p>Thank you,</p>
    <p>Badminton Tournament System</p>
</body>
</html>
