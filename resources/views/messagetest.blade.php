<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Slack Message</title>
</head>
<body>
    <div>
        <h1>Send Slack Message</h1>
        <form action="{{ url('/send-message') }}" method="POST">
            @csrf
            <label for="slackId">Select User:</label>
            <select id="slackId" name="slackId" required>
                @foreach($members as $member)
                    <option value="{{ $member->slack_id }}">{{ $member->host_name }}</option>
                @endforeach
            </select>
            <br><br>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <br><br>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>