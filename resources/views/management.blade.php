<!DOCTYPE html>
<html>
<head>
    <title>管理者画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">管理者画面</h1>
        @if (session('success')) 
            <div class="alert alert-success"> 
                {{ session('success') }} 
            </div> 
        @endif
        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-block">アポ情報新規登録</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="/appointments" class="btn btn-primary btn-block">アポ情報一覧</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="/visitors" class="btn btn-primary btn-block">来客者情報一覧</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="/reception" class="btn btn-primary btn-block">受付画面に移動</a>
            </div>
        </div>
    </div>
</body>
</html>
