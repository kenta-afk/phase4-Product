<!DOCTYPE html>
<html>
<head>
    <title>来客者情報一覧</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">来客者情報一覧</h1>
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
                        <td>{{ $appointment->users->first()->name }}</td>
                        <td>{{ $appointment->room->room_name }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->comment }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">編集</a>
                            <a href="#" class="btn btn-sm btn-danger">削除</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
