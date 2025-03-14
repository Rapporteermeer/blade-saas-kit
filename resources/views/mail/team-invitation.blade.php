<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Team Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>You've Been Invited to Join a Team</h1>

    <p>You have been invited to join the <strong>{{ $invitation->team->name }}</strong> team on {{ config('app.name')
        }}.</p>

    <a href="{{ url('/invitations/' . $invitation->token) }}" class="button">Accept Invitation</a>

    <p>This invitation will expire in 7 days.</p>

    <div class="footer">
        Thanks,<br>
        {{ config('app.name') }}
    </div>
</body>

</html>