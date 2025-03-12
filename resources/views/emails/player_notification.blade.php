<!DOCTYPE html>
<html>
<head>
    <title>Player {{ $action }} Notification</title>
</head>
<body>
    <h2>Player {{ ucfirst($action) }} Notification</h2>
    <p><strong>Player Name:</strong> {{ $player->name }}</p>
    <p><strong>Category:</strong> {{ $player->category }}</p>
    <p><strong>Modified By:</strong> {{ $modifiedBy }}</p>
    <p>Thank you,</p>
    <p>Badminton Tournament System</p>
</body>
</html>
