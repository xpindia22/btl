<!DOCTYPE html>
<html>
<head>
    <title>Payment Pending Notification</title>
</head>
<body>
    <p>Hello {{ $player->name }},</p>

    @if (!$isAdmin)
        <p>You have been assigned to the tournament <strong>{{ $tournament->name }}</strong> in the following paid categories:</p>
    @else
        <p>The player <strong>{{ $player->name }}</strong> has been assigned to the tournament <strong>{{ $tournament->name }}</strong> in the following paid categories:</p>
    @endif

    <ul>
        @foreach ($categories as $category)
            <li>{{ $category->category_name }} - ₹{{ $category->fee }}</li>
        @endforeach
    </ul>

    <p>Total amount due: <strong>₹{{ $totalFee }}</strong></p>

    @if (!$isAdmin)
        <p>Please complete your payment as soon as possible to confirm your participation.</p>
        <p><a href="#">Click here to Pay Now</a></p>
    @else
        <p>Please monitor this player's payment status.</p>
    @endif

    <p>Regards,</p>
    <p>{{ config('app.name') }} Team</p>
</body>
</html>
