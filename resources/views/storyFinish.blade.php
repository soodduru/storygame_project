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
                        [story game]
                    </p>
                </div>
            </div>
            <div class="sm:block sm:ml-6">
                <div class="flex space-x-4">
                    <button class="py-0 px-4 font-semibold font-mono rounded-lg shadow-md text-white bg-indigo-400 hover:bg-indigo-600 focus:outline-none" onclick="room_out()">OUT</button>
                </div>
            </div>
        </div>
    </div>
</nav>



<div class="min-h-screen flex items-center justify-center bg-gray-250 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 items-center">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Number
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nickname
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Story
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stories as $story)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-none items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        1
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-none items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{$story->user_nickname}}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$story->story}}</div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>






<script>

    const user = localStorage.getItem('user_id');
    const room_id = {{$room_id}};


    // 방에서 나가기
    function room_out(){
        location.href="/gameRoomList";

    }



</script>
</body>
</html>
