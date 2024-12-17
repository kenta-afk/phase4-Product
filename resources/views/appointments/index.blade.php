<!DOCTYPE html>
<html>
<head>
    <title>アポ情報一覧</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script>
        function confirmDeletion() {
            return confirm('本当に削除しますか？');
        }
        function confirmVisited() {
            return confirm('来客済としてアポ情報から来客者情報に移動しますか？')
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">アポ情報一覧</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>来客者の名前</th>
                    <th>来客者の所属</th>
                    <th>対応者の名前</th>
                    <th>会議室の名前</th>
                    <th>訪問日時</th>
                    <th>要件</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->visitor_name }}</td>
                        <td>{{ $appointment->visitor_company }}</td>

                        <td>
                            @foreach ($appointment->users as $user)
                                {{ $user->name }}@if (!$loop->last), @endif
                            @endforeach
                        </td>

                        <td>{{ $appointment->room->name }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->comment }}</td>
                        <td>
                            <form action="{{ route('appointments.edit', $appointment->id) }}" method="GET" style="display:inline;"> 
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info">編集</button>
                            </form>
                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeletion();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                            <form action="{{ route('appointments.visited', $appointment->id) }}" method="GET" style="display:inline;" onsubmit="return confirmVisited();">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">来客済にする</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('management') }}" class="btn btn-primary float-right fixed-bottom">ホーム画面へ戻る</a>
    </div>
</body>
</html>
