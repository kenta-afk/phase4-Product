<!DOCTYPE html>
<html>
<head>
    <title>受付画面</title>
    @vite('resources/css/app.css')
</head>
<body>
  <div class="relative isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
    <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
        <div class="mx-auto aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>
    <div class="mx-auto max-w-4xl text-center">
        <p class="mt-2 text-balance text-5xl font-semibold tracking-tight text-gray-900 sm:text-6xl">受付画面</p>
    </div>
    <div class="mx-auto mt-16 grid max-w-lg grid-cols-1 items-center gap-y-6 gap-x-10 sm:mt-20 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-2">
        <div class="relative rounded-3xl bg-white p-12 shadow-2xl ring-1 ring-gray-900/10 sm:p-14 h-80">
            <p class="mt-4 flex items-baseline gap-x-2">
                <span class="text-4xl font-semibold tracking-tight text-black">来客の方はこちら</span>
            </p>
        </div>
        <div class="relative rounded-3xl bg-white p-12 shadow-2xl ring-1 ring-gray-900/10 sm:p-14 h-80">
            <p class="mt-4 flex items-baseline gap-x-2">
                <span class="text-4xl font-semibold tracking-tight text-black">その他の方はこちら</span>
            </p>
        </div>
    </div>
  </div>

</body>
</html>