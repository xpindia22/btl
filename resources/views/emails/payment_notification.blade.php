<!DOCTYPE html>
<html>
<head>
    <title>Payment Required for Tournament</title>
</head>
<body>
    <p>Dear {{ $user->username }},</p>

    <p>You have been added to the tournament <strong>{{ $tournament->name }}</strong>.</p>

    <p>To confirm your participation, please make a payment of <strong>₹{{ $amount }}</strong> via GPay/UPI to:</p>

    <p><strong>7432001215 (GPay/UPI)</strong></p>

    <p>Once you complete the payment, please enter your transaction ID in your dashboard.</p>

    <p><a href="{{ url('/dashboard') }}">Go to Dashboard</a></p>

    <p>Thank you!</p>
</body>
</html>
