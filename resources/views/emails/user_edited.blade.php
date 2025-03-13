<!DOCTYPE html>
<html>
<head>
    <title>Account Updated</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #2c3e50; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Account Details Have Been Updated</h2>
        <p>Hello <strong>{{ $username }}</strong>,</p>

        <p>Your account details have been updated by <strong>{{ $updatedBy }}</strong>.</p>
        
        <p><strong>Updated Email:</strong> {{ $email }}</p>

        <p>If you did not request this change, please contact support immediately.</p>

        <p>Thank you!</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
