<!DOCTYPE html>
<html>
<head>
    <title>来客者情報一覧</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script>
        function confirmDeletion() {
            return confirm('本当に削除しますか？');
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">来客者情報一覧</h1>
        <form action="{{ route('visitors.search') }}" method="GET" class="mb-4">
            <div class="form-row"> 
                <div class="col"> 
                    <input type="date" name="date" class="form-control" placeholder="日付"> 
                </div> 
                <div class="col"> 
                    <input type="text" name="visitor_name" class="form-control" placeholder="来客者の名前"> 
                </div>
                <div class="col"> 
                    <input type="text" name="visitor_company" class="form-control" placeholder="来客者の所属"> 
                </div>
                <div class="col"> 
                    <button type="submit" class="btn btn-primary">検索</button> 
                    <a href="{{ route('visitors.index') }}" class="btn btn-secondary ml-2">検索内容をリセット</a>
                </div> 
            </div>
        </form>
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
                @foreach ($visitors as $visitor)
                    <tr>
                        <td>{{ $visitor->visitor_name }}</td>
                        <td>{{ $visitor->visitor_company }}</td>
                        <td>
                            @foreach ($visitor->users as $user)
                                {{ $user->name }}@if (!$loop->last), @endif
                            @endforeach
                        </td>
                        <td>{{ $visitor->room->name }}</td>
                        <td>{{ $visitor->date }}</td>
                        <td>{{ $visitor->comment }}</td>
                        <td>
                            <form action="{{ route('visitors.destroy', $visitor->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeletion();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
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
