<!DOCTYPE html>
<html>
<head>
    <title>管理者画面</title>
    @vite('resources/css/app.css')
</head>
<body>
  <form method="POST" action="{{ route('logout') }}">
    @csrf

    <a href="route('logout')"
            onclick="event.preventDefault();
                        this.closest('form').submit();">
        {{ __('Log Out') }}
    </a>
</form>

  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:text-center">
        <p class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl lg:text-balance">管理者画面</p>
        @if (session('success')) 
            <div class="alert alert-success"> 
                {{ session('success') }} 
            </div> 
        @endif
      </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
         <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
            <a href="{{ route('appointments.create') }}" class="group relative block pl-20 bg-white border border-gray-200 rounded-lg shadow-lg px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
              <div class="absolute left-1 top-1 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 16 16" fill="currentColor">
                  <path 
                    d="M4.5 13a3.5 3.5 0 0 1-1.41-6.705A3.5 3.5 0 0 1 9.72 4.124a2.5 2.5 0 0 1 3.197 3.018A3.001 3.001 0 0 1 12 13H4.5Zm.72-5.03a.75.75 0 0 0 1.06 1.06l.97-.97v2.69a.75.75 0 0 0 1.5 0V8.06l.97.97a.75.75 0 1 0 1.06-1.06L8.53 5.72a.75.75 0 0 0-1.06 0L5.22 7.97Z" clip-rule="evenodd" />
                </svg>
              </div>
              <dt class="text-base/7 font-semibold text-gray-900">
                アポイントメント登録
              </dt>
              <dd class="mt-2 text-base/7 text-gray-600 mb-4">
                アポイントメント情報の登録をします。
              </dd>
            </a>
            <a href="/appointments"
              class="group relative block pl-20 bg-white border border-gray-200 rounded-lg shadow-lg px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
              <div class="absolute left-1 top-1 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 16 16" fill="currentColor">
                  <path
                    d="M3 4.75a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM6.25 3a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM6.25 7.25a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM6.25 11.5a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5h-7ZM4 12.25a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM3 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                </svg>
              </div>
              <dt class="text-base/7 font-semibold text-gray-900">アポイントメント情報一覧</dt>
              <dd class="mt-2 text-base/7 text-gray-600 mb-4">アポイントメント情報の一覧を表示します。</dd>
            </a>
            
            <a href="/visitors"
              class="group relative block pl-20 bg-white border border-gray-200 rounded-lg shadow-lg px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
              <div class="absolute left-1 top-1 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 16 16" fill="currentColor">
                  <path
                    d="M8.5 4.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10.9 12.006c.11.542-.348.994-.9.994H2c-.553 0-1.01-.452-.902-.994a5.002 5.002 0 0 1 9.803 0ZM14.002 12h-1.59a2.556 2.556 0 0 0-.04-.29 6.476 6.476 0 0 0-1.167-2.603 3.002 3.002 0 0 1 3.633 1.911c.18.522-.283.982-.836.982ZM12 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                </svg>
              </div>
              <dt class="text-base/7 font-semibold text-gray-900">来客者情報一覧</dt>
              <dd class="mt-2 text-base/7 text-gray-600 mb-4">訪問された方の一覧を表示します。</dd>
            </a>
            
            <a href="/reception"
              class="group relative block pl-20 bg-white border border-gray-200 rounded-lg shadow-lg px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
              <div class="absolute left-1 top-1 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 16 16" fill="currentColor">
                  <path
                    d="M2 8c0 .414.336.75.75.75h8.69l-1.22 1.22a.75.75 0 1 0 1.06 1.06l2.5-2.5a.75.75 0 0 0 0-1.06l-2.5-2.5a.75.75 0 1 0-1.06 1.06l1.22 1.22H2.75A.75.75 0 0 0 2 8Z"
                    clip-rule="evenodd" />
                </svg>
              </div>
              <dt class="text-base/7 font-semibold text-gray-900">受付画面</dt>
              <dd class="mt-2 text-base/7 text-gray-600 mb-4">受付画面は、来客時にお客様が操作する画面です。</dd>
            </a>
            
         </dl>
      </div>
    </div>
  </div>
</body>
</html>

