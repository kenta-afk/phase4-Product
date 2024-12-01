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
            <label for="slackId">Slack ID:</label>
            <input type="text" id="slackId" name="slackId" required>
            <br><br>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <br><br>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>