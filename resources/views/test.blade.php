<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-200 leading-tight">
<head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    {{--tailwind--}}
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<nav class="bg-gray-800 sticky top-0">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
            </div>
            <div class="flex-1 flex items-center  sm:items-stretch sm:justify-start">
                <div class="flex-shrink-0 flex items-center">
                    <p class="text-lg text-white font-mono font-semibold">
                        방번호 : 12
                    <div class="hidden"></div>

                    </p>
                </div>
            </div>
        </div>
    </div>
</nav>


{{--게임 화면이 붙을 곳 --}}
<div class="min-h-screen flex items-center justify-center bg-gray-250 ">
    <div class="py-10 px-8 box-border h-96 w-96 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2" id="basicField">
        <div class="text-center space-y-2" id="form-group" >
             <div>
                <div class="mt-1">
                  <textarea id="comment" name="about" rows="8" class="shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full text-lg border-gray-300 rounded-md"></textarea>
                  </div>
                </div>
              </div>
       <p class="mt-2 text-lg font-mono text-gray-500">소문을 작성해주세요</p>
    </div>
</div>
