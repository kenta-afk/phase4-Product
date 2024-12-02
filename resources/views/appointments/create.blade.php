<!DOCTYPE html>
<html>
<head>
    <title>アポ情報新規登録</title>
    @vite('resources/css/app.css')
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
          <label for="host_name" class="block text-sm/6 font-semibold text-gray-900">対応者の名前</label>
          <div class="mt-2.5">
            <input type="text" name="host_name" id="host_name" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="room_name" class="block text-sm/6 font-semibold text-gray-900">会議室の名前</label>
          <div class="mt-2.5">
            <input type="text" name="room_name" id="room_name" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="appointment_date" class="block text-sm/6 font-semibold text-gray-900">訪問日時</label>
          <div class="mt-2.5">
            <input type="datetime-local" name="appointment_date" id="appointment_date" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>
          </div>
        </div>
        <div class="sm:col-span-2">
          <label for="purpose" class="block text-sm/6 font-semibold text-gray-900">要件</label>
          <div class="mt-2.5">
            <textarea name="purpose" id="purpose" rows="4" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required></textarea>
          </div>
        </div>
        <div class="sm:col-span-2 mt-10">
          <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">登録</button>
        </div>
    </form>
</div>
</body>
</html>
