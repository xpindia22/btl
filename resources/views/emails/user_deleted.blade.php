<!DOCTYPE html>
<html>
<head>
    <title>User Deleted</title>
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
        <h2>User Deleted</h2>
        <p>The following user has been deleted:</p>

        <table>
            @foreach ($deletedUserDetails as $key => $value)
                <tr>
                    <th>{{ $key }}</th>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <p>If this was a mistake, please take immediate action.</p>

        <p>Thank you!</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
