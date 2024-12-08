<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Outlookカレンダー</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">あなたのカレンダーイベント</h1>
        @if(count($events) > 0)
            <ul class="bg-white shadow rounded-lg">
                @foreach($events as $event)
                    <li class="border-b last:border-b-0 p-4">
                        <strong class="text-xl">{{ $event['subject'] }}</strong><br>
                        <span class="text-gray-600">
                            開始: {{ \Carbon\Carbon::parse($event['start']['dateTime'])->format('Y-m-d H:i') }}<br>
                            終了: {{ \Carbon\Carbon::parse($event['end']['dateTime'])->format('Y-m-d H:i') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">カレンダーイベントが見つかりませんでした。</p>
        @endif
    </div>
</body>
</html>
