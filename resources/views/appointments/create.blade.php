<!DOCTYPE html>
<html lang="ja">
<head>
    <title>アポ情報新規登録</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
</head>
<body>

  <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
    <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]" aria-hidden="true">
      <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>

    </div>
    <div class="mx-auto max-w-2xl text-center">
      <h2 class="text-balance text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">アポイントメント登録</h2>
    </div>
    <form action="/appointments" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
    @csrf
      <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label for="visitor_name" class="block text-sm/6 font-semibold text-gray-900">来客者の名前</label>
          <div class="mt-2.5">
            <input type="text" name="visitor_name" id="visitor_name" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="visitor_company" class="block text-sm/6 font-semibold text-gray-900">来客者の所属</label>
          <div class="mt-2.5">
            <input type="text" name="visitor_company" id="visitor_company" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="user_names" class="block text-sm/6 font-semibold text-gray-900">対応者の名前</label>
          <div class="mt-2.5">
            <select name="user_names[]" id="user_names" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" multiple required>
              @foreach($users as $user)
                <option value="{{ $user->name }}">{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="room_id" class="block text-sm/6 font-semibold text-gray-900">会議室の名前</label>
          <div class="mt-2.5">
            <select name="room_id" id="room_id" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
              @foreach($rooms as $room)
                <option value="{{ $room->id }}" data-name="{{ $room->name }}">{{ $room->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="date" class="block text-sm/6 font-semibold text-gray-900">訪問日時</label>
          <div class="mt-2.5">
            <input type="datetime-local" name="date" id="date" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="purpose" class="block text-sm/6 font-semibold text-gray-900">要件</label>
          <div class="mt-2.5">
            <textarea name="comment" id="comment" rows="4" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required></textarea>
          </div>
        </div>
        <div class="sm:col-span-2 mt-10">
          <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">登録</button>
        </div>
    </form>
    <h2 class="mt-6">選択された会議室のイベント一覧</h2>
    <div id="events-list" class="mt-4 space-y-4 bg-gray-100 p-4 rounded-lg">
      <!-- イベントがここに表示されます -->
    </div>
    <div class="flex justify-center mt-6">
      <a href="{{ route('management') }}" class="inline-block rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105 fixed bottom-4 left-1/2 transform -translate-x-1/2">ホーム画面へ戻る</a>
    </div>
</div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- Tom Selectの初期化 -->
    <script>
        new TomSelect('#user_names', {
            maxItems: null, // 選択できる最大アイテム数を設定（nullは無制限）
            placeholder: "対応者を選択してください",
            allowClear: true
        });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          const roomSelect = document.getElementById('room_id');
          const eventsList = document.getElementById('events-list');

          roomSelect.addEventListener('change', function() {
              const selectedRoomName = roomSelect.options[roomSelect.selectedIndex].getAttribute('data-name');
              console.log(selectedRoomName);
              fetchEvents(selectedRoomName);
          });

          function fetchEvents(roomName) {
              fetch('/calendar/events')
                  .then(response => {
                      if (!response.ok) {
                          redirect('/auth/redirect');
                          throw new Error('Network response was not ok ' + response.statusText);
                      }
                      return response.json();
                  })
                  .then(data => {
                      if (data.error) {
                          throw new Error(data.error);
                      }
                      const events = data.events.filter(event => event.location === roomName);
                      displayEvents(events);
                  })
                  .catch(error => {
                      console.error('Error fetching events:', error);
                      eventsList.innerHTML = `<p>イベントの取得に失敗しました: ${error.message}</p>`;
                  });
          }

          function displayEvents(events) {
              eventsList.innerHTML = '';
              if (events.length === 0) {
                  eventsList.innerHTML = '<p>該当するイベントはありません。</p>';
                  return;
              }

              events.forEach(event => {
                  const eventElement = document.createElement('div');
                  eventElement.classList.add('event');
                    eventElement.innerHTML = `
                      <p><strong>件名:</strong> ${event.subject}</p>
                      <p><strong>開始:</strong> ${new Date(event.start.dateTime).toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })}</p>
                      <p><strong>終了:</strong> ${new Date(event.end.dateTime).toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })}</p>
                  `;
                  eventsList.appendChild(eventElement);
              });
          }
      });
    </script>
</body>
</html>
