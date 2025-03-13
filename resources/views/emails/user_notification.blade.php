<!DOCTYPE html>
<html>
<head>
    <title>User Account Notification</title>
</head>
<body>
    <h2>Hello, {{ $recipientType === 'admin' ? 'Admin' : ($recipientType === 'moderator' ? 'Moderator' : $user->username) }}</h2>

    @if($action === 'created')
        <p>
            {{ $recipientType === 'user' ? 'Welcome' : 'A new user has been created in the system.' }}
            {{ $recipientType === 'moderator' ? ' under your moderation.' : '' }}
        </p>
    @elseif($action === 'updated')
        <p>
            The user account for {{ $user->username }} has been updated.
        </p>
    @endif

    <p><strong>User Details:</strong></p>
    <ul>
        <li><strong>Username:</strong> {{ $user->username }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
    </ul>

    <p>Best Regards,<br>Team</p>
</body>
</html>
