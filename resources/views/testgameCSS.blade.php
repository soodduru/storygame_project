<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-200 leading-tight">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    {{--부트스트랩--}}
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<div class="min-h-screen flex items-center justify-center bg-gray-250 py-12 px-4 sm:px-6 lg:px-8">

<div class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 ">

    <div class="text-center space-y-2">
        <div class="space-y-3">
            <p class="text-lg text-black font-mono font-semibold">
                Welcome to jotbab game
            </p>
            <p class="text-gray-500 font-medium">

            </p>
        </div>
        <div class="space-y-1">
        <input type="text" name="nickname"  class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-indigo-600 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-mono" placeholder="Nickname" id="nickname" placeholder="닉네임을 입력해주세요"/><br>
            <button class="py-2 px-4 font-semibold font-mono rounded-lg shadow-md text-white bg-indigo-400 hover:bg-indigo-600 focus:outline-none" onclick="game_guide()">How to play</button>
        <button class="py-2 px-4 font-semibold font-mono rounded-lg shadow-md text-white bg-indigo-400 hover:bg-indigo-600 focus:outline-none" onclick="game_start()">Let's start!</button>
        </div>
    </div>
</div>
</div>

</body>
</html>
