<!DOCTYPE html>
<html>
<head>
    <title>User Account Updated</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #2c3e50; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Account Updated</h2>
        <p>Hello <strong>{{ $user->username }}</strong>,</p>

        <p>Your account details have been updated by <strong>{{ $updatedBy }}</strong>. Below are the updated details:</p>

        <h3>🔹 User Information</h3>
        <table>
            <tr><th>ID</th><td>{{ $user->id }}</td></tr>
            <tr><th>Username</th><td>{{ $user->username }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Mobile No</th><td>{{ $user->mobile_no ?? 'N/A' }}</td></tr>
            <tr><th>DOB</th><td>{{ $user->dob ?? 'N/A' }}</td></tr>
            <tr><th>Sex</th><td>{{ ucfirst($user->sex) }}</td></tr>
            <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
        </table>

        @if (!empty($updatedFields))
            <h3>🔹 Changes Made</h3>
            <table>
                <tr><th>Field</th><th>Old Value</th><th>New Value</th></tr>
                @foreach ($updatedFields as $field => $values)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                        <td style="color: red;">{{ $values['old'] ?? 'N/A' }}</td>
                        <td style="color: green;">{{ $values['new'] }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <h3>🔹 Moderated Tournaments</h3>
        @if($moderatedTournaments->isNotEmpty())
            <ul>
                @foreach ($moderatedTournaments as $tournament)
                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                @endforeach
            </ul>
        @else
            <p>No moderated tournaments.</p>
        @endif

        <h3>🔹 Created Tournaments</h3>
        @if($createdTournaments->isNotEmpty())
            <ul>
                @foreach ($createdTournaments as $tournament)
                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                @endforeach
            </ul>
        @else
            <p>No created tournaments.</p>
        @endif

        <p>If you did not request this change, please contact support immediately.</p>

        <p>Thank you!</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
