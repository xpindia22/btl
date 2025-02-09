<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Secondary Authentication</title>
</head>
<body>
    <h1>Admin Secondary Authentication Required</h1>
    <form method="POST" action="{{ url()->current() }}">
        @csrf
        <label for="auth_password">Enter Secondary Password:</label>
        <input type="password" name="auth_password" id="auth_password" required>
        <button type="submit">Authenticate</button>
    </form>
</body>
</html>
