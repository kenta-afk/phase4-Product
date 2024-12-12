<!-- resources/views/calendars.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カレンダー一覧</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>カレンダー一覧</h1>

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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>カレンダー名</th>
                    <th>カレンダーID</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendars as $calendar)
                    <tr>
                        <td>{{ $calendar['name'] }}</td>
                        <td>{{ $calendar['id'] }}</td>
                        <td>
                            <!-- イベント一覧ページへのリンク -->
                            <a href="{{ route('calendar.events', ['calendar_id' => $calendar['id']]) }}" class="btn btn-primary">イベント一覧</a>
                            <!-- イベントを追加するボタン -->
                            <a href="{{ route('calendar.addEventForm', ['calendar_id' => $calendar['id']]) }}" class="btn btn-success">イベントを追加</a>
                            <!-- 他のアクション（例: カレンダーの編集、削除） -->
                            <!--
                            <a href="#" class="btn btn-warning">編集</a>
                            <a href="#" class="btn btn-danger">削除</a>
                            -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('calendar.createShared') }}" class="btn btn-primary">共有カレンダーを作成</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
