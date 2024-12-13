<!-- resources/views/share_calendar.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カレンダーを共有</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>カレンダーを共有</h1>

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

        <form action="{{ route('calendar.share') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="calendar_id">カレンダーID</label>
                <input type="text" class="form-control" id="calendar_id" name="calendar_id" required>
            </div>
            <div class="form-group mb-3">
                <label for="user_email">共有相手のメールアドレス</label>
                <input type="email" class="form-control" id="user_email" name="user_email" required>
            </div>
            <div class="form-group mb-3">
                <label for="user_name">共有相手の名前</label>
                <input type="text" class="form-control" id="user_name" name="user_name" required>
            </div>
            <div class="form-group mb-3">
                <label for="role">権限</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="reader">読み取り専用</option>
                    <option value="writer">読み取り/書き込み</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">カレンダーを共有</button>
        </form>

        <!-- 戻るボタン -->
        <a href="{{ route('calendar.list') }}" class="btn btn-secondary mt-3">カレンダー一覧へ戻る</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
