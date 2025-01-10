<!DOCTYPE html>
<html>
<head>
    <title>アポ情報一覧</title>
    @vite('resources/css/app.css')
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
        <div class="mb-4">
            <a href="{{ route('appointments.index', ['sort' => 'date']) }}" class="btn btn-secondary">日時順</a> 
            <a href="{{ route('appointments.index', ['sort' => 'updated_at']) }}" class="btn btn-secondary">更新順</a>
        </div>
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
        <div class="flex justify-center mt-6">
            <a href="{{ route('management') }}" class="inline-block rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105 fixed bottom-4 left-1/2 transform -translate-x-1/2">ホーム画面へ戻る</a>
        </div>
    </div>
</body>
</html>
