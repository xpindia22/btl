<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>You are receiving this email because a password reset request was made for your account.</p>
    <p>
        Click <a href="{{ url('password/reset', $token) }}">here</a> to reset your password.
    </p>
    <p>
        This link will expire at {{ $expiresAt->toDateTimeString() }}.
    </p>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
