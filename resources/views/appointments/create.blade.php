<!DOCTYPE html>
<html>
<head>
    <title>アポ情報新規登録</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">アポ情報新規登録</h1>
        <form action="/appointments" method="POST">
            @csrf
            <div class="form-group">
                <label for="visitor_name">来客者の名前</label>
                <input type="text" class="form-control" id="visitor_name" name="visitor_name" required>
            </div>
            <div class="form-group">
                <label for="visitor_company">来客者の所属</label>
                <input type="text" class="form-control" id="visitor_company" name="visitor_company" required>
            </div>
            <div class="form-group">
                <label for="host_name">対応者の名前</label>
                <input type="text" class="form-control" id="host_name" name="host_name" required>
            </div>
            <div class="form-group">
                <label for="room_name">会議室の名前</label>
                <input type="text" class="form-control" id="room_name" name="room_name" required>
            </div>
            <div class="form-group">
                <label for="appointment_date">訪問日時</label>
                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required>
            </div>
            <div class="form-group">
                <label for="purpose">要件</label>
                <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">登録</button>
        </form>
    </div>
</body>
</html>
