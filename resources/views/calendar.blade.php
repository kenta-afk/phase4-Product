<!-- resources/views/calendar.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>イベント一覧</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Carbonの使用 -->
    @php
        use Carbon\Carbon;
    @endphp
</head>
<body>
    <div class="container mt-5">
        <h1>イベント一覧</h1>

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
                    <th>件名</th>
                    <th>内容</th>
                    <th>開始日時</th>
                    <th>終了日時</th>
                    <th>場所</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event['subject'] }}</td>
                        <td>{{ $event['body']['content'] }}</td>
                        <td>{{ Carbon::parse($event['start']['dateTime'])->format('Y-m-d H:i') }}</td>
                        <td>{{ Carbon::parse($event['end']['dateTime'])->format('Y-m-d H:i') }}</td>
                        <td>{{ $event['location']['displayName'] ?? 'N/A' }}</td>
                        <td>
                            <!-- 編集や削除のアクションを追加可能 -->
                            <!--
                            <a href="#" class="btn btn-warning btn-sm">編集</a>
                            <a href="#" class="btn btn-danger btn-sm">削除</a>
                            -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">イベントがありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- イベント追加ボタン -->
        <a href="{{ route('calendar.addEventForm', ['calendar_id' => $calendar_id]) }}" class="btn btn-success">イベントを追加</a>

        <!-- カレンダー一覧への戻るボタン -->
        <a href="{{ route('calendar.list') }}" class="btn btn-secondary">カレンダー一覧へ戻る</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
