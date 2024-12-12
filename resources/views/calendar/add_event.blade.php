<!-- resources/views/add_event.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>イベントを追加</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>イベントを追加</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('calendar.addEvent') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="calendar_id">カレンダーID</label>
                <input type="text" class="form-control" id="calendar_id" name="calendar_id" value="{{ $calendar_id }}" readonly>
            </div>
            <div class="form-group mb-3">
                <label for="subject">件名</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group mb-3">
                <label for="content">内容</label>
                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="start_datetime">開始日時</label>
                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
            </div>
            <div class="form-group mb-3">
                <label for="end_datetime">終了日時</label>
                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
            </div>
            <div class="form-group mb-3">
                <label for="location">場所</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group mb-3">
                <label for="attendee_email">参加者メールアドレス</label>
                <input type="email" class="form-control" id="attendee_email" name="attendee_email" required>
            </div>
            <div class="form-group mb-3">
                <label for="attendee_name">参加者名</label>
                <input type="text" class="form-control" id="attendee_name" name="attendee_name" required>
            </div>
            <button type="submit" class="btn btn-primary">イベントを追加</button>
        </form>

        <!-- 戻るボタン -->
        <a href="{{ route('calendar.list') }}" class="btn btn-secondary mt-3">カレンダー一覧へ戻る</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
