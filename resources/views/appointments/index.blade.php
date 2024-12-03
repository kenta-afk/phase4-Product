<!DOCTYPE html>
<html>
<head>
    <title>アポ情報一覧</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">アポ情報一覧</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td>{{ $appointment['id'] }}</td>
                        <td>{{ $appointment['visitor_name'] }}</td>
                        <td>{{ $appointment['visitor_company'] }}</td>
                        <td>{{ $appointment['host_name'] }}</td>
                        <td>{{ $appointment['meeting_room'] }}</td>
                        <td>{{ $appointment['appointment_date'] }}</td>
                        <td>{{ $appointment['purpose'] }}</td>
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
