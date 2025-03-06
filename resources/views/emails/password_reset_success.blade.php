<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Successful</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Your password has been successfully reset.</p>
    <p>If you did not perform this action, please contact our support immediately.</p>
    <p>Thank you,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
