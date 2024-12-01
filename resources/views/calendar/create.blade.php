<h1>Create a Calendar Event</h1>
<form action="{{ route('calendar.store') }}" method="POST">
    @csrf
    <label for="subject">Event Name:</label>
    <input type="text" id="subject" name="subject" required><br>

    <label for="start">Start Time:</label>
    <input type="datetime-local" id="start" name="start" required><br>

    <label for="end">End Time:</label>
    <input type="datetime-local" id="end" name="end" required><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea><br>

    <button type="submit">Create Event</button>
</form>
