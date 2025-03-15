<!DOCTYPE html>
<html>
<head>
    <title>New User Registration</title>
</head>
<body>
    <h2>New User Registered</h2>

    <p><strong>Username:</strong> {{ $user->username }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Mobile No:</strong> {{ $user->mobile_no ?? 'N/A' }}</p>
    <p><strong>DOB:</strong> {{ $user->dob }}</p>
    <p><strong>Sex:</strong> {{ $user->sex }}</p>
    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
    <p><strong>Created By:</strong> {{ $createdBy }}</p>

    <p>If you did not request this registration, please contact support immediately.</p>

    <br>
    <p>Thank you!</p>
    <p>© 2025 Your Company. All rights reserved.</p>
</body>
</html>
