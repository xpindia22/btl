<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body>
    <p>Hello,</p>

    <p>A new payment has been made for the tournament:</p>

    <p><strong>Tournament:</strong> {{ $tournament->name }}</p>
    <p><strong>Player:</strong> {{ $player->username }} ({{ $player->email }})</p>
    <p><strong>Amount:</strong> ₹{{ $payment->amount }}</p>
    <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
    <p><strong>Status:</strong> {{ $payment->status }}</p>

    <p>Visit the admin panel to verify the payment.</p>

    <p><a href="{{ url('/admin/payments') }}">View Payments</a></p>

    <p>Best regards,</p>
    <p>BTL Tournament System</p>
</body>
</html>
