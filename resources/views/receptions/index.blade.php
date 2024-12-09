<!DOCTYPE html>
<html>
<head>
    <title>受付画面</title>
    @vite('resources/css/app.css')
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .overlay-message {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .spinner {
            margin-bottom: 20px;
        }
    </style>
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
        <div class="relative rounded-3xl bg-white p-12 shadow-2xl ring-1 ring-gray-900/10 sm:p-14 h-80 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
            <a href="{{ route('receptions.create') }}" class="block h-full w-full">
                <p class="mt-4 flex items-baseline gap-x-2">
                    <span class="text-4xl font-semibold tracking-tight text-black">来客の方はこちら</span>
                </p>
            </a>
        </div>
        <div class="relative rounded-3xl bg-white p-12 shadow-2xl ring-1 ring-gray-900/10 sm:p-14 h-80 hover:bg-gray-100 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200 ease-in-out">
            <a href="#" class="block h-full w-full" id="other-button">
                <p class="mt-4 flex items-baseline gap-x-2">
                    <span class="text-4xl font-semibold tracking-tight text-black">その他の方はこちら</span>
                </p>
            </a>
        </div>
    </div>
  </div>

  <div class="overlay" id="overlay">
    <div class="overlay-message">
        <div role="status" class="spinner">
            <svg aria-hidden="true" class="w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
        只今、担当者に連絡していますので少々お待ちください。
    </div>
  </div>

  <script>
    document.getElementById('other-button').addEventListener('click', function(event) {
        event.preventDefault();
        var overlay = document.getElementById('overlay');
        overlay.style.display = 'flex';
        setTimeout(function() {
            overlay.style.display = 'none';
        }, 7000);
    });
  </script>
</body>
</html>