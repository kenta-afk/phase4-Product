<!DOCTYPE html>
<html>
<head>
    <title>管理者画面</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="flex justify-between space-x-4 p-4 bg-gray-100 rounded-md shadow-md">
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" id="role-toggle" class="sr-only peer">
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">事務の方はこちらをONにしてください。</span>
    </label>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            {{ __('Profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleToggle = document.getElementById('role-toggle');
        roleToggle.checked = {{ auth()->user()->role }} == 1;

        roleToggle.addEventListener('change', function () {
            fetch('{{ route('profile.updateRole') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    role: roleToggle.checked ? 1 : 0
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Role updated successfully');
                  } else {
                      alert('Failed to update role');
                  }
              });
        });
    });
</script>
</body>
</html>

