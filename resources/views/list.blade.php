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
<body class="font-mono">


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
                <div class="sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">How to play</a>
                    </div>
                </div>
            </div>
            <div class="sm:block sm:ml-6">
                <div class="flex space-x-4">
                    <input type="text" name="room_name"  id="room_name" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-indigo-600 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-mono" placeholder="Room Name"/><br>
                    <button class="py-0 px-4 font-semibold font-mono rounded-lg shadow-md text-white bg-indigo-400 hover:bg-indigo-600 focus:outline-none" onclick="room_make()">New Room</button>
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
                                방이름
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                방장
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">입장하기</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    var lists = new Array();

    var list_socket = setInterval(function(){
        $.ajax({
            url: "/roomListCheck",
            type: "post",
            data: {
            } ,
            success: function (response) {

                if(response.success=="200") {

                    $.each(response.room_rows, function(key, value){

                        if(lists.includes(value.id)==false){
                            lists.push(value.id);
                            $('#tbody').append(
                                '<tr class="text-center">\n' +
                                '                            <td class="px-6 py-4 whitespace-nowrap">\n' +
                                '                                <div class="flex-none items-center">\n' +
                                '                                        <div class="text-sm font-medium text-gray-900">\n'
                                + value.room_name +
                                '                                        </div>\n' +
                                '                                </div>\n' +
                                '                            </td>\n' +
                                '                            <td class="px-6 py-4 whitespace-nowrap">\n' +
                                '                                <div class="text-sm text-gray-900">'+ value.master_nickname +'</div>\n' +
                                '                            </td>\n' +

                                (value.room_status == 0 ?
                                '                            <td class="px-6 py-4 whitespace-nowrap">\n' +
                                '                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">\n' +
                                '                                    Active\n' +
                                '                                </span>\n' +
                                '                            </td>\n' +
                                '                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">\n' +
                                '                                <button onclick="participateRoom(' + value.id + ')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">\n' +
                                '                                        Enter\n' +
                                '                                </button>\n' +
                                '                            </td>\n' :

                                            '                            <td class="px-6 py-4 whitespace-nowrap">\n' +
                                    '                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">\n' +
                                    '                                    Inactive\n' +
                                    '                                </span>\n' +
                                    '                            </td>\n' +
                                    '                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">\n' +
                                    '                                <button disabled="disabled" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-gray bg-gray-400 focus:outline-none">\n' +
                                    '                                        Enter\n' +
                                    '                                </button>\n' +
                                    '                            </td>\n')+

                                '                        </tr>'

                            );



                        }
                    })

                }


            },
            error: function() {
                console.log("에러");
            }
        });

    }, 1000);


    // 방에 참여하기
    function participateRoom(room_id){
        var user_id = localStorage.getItem("user_id");
        //
        $.ajax({
            url: "/participate",
            type: "post",
            data: {
                room_id: room_id,
                user_id: user_id,

            } ,
            success: function (response) {
                console.log("참여");
                console.log(response);
                if(response.success=="200"){
                    location.href="/room/"+room_id;
                } else if(response.success=="300"){
                    alert("게임 인원이 다 찼습니다!");
                }

            },
            error: function() {
                console.log("에러");
            }
        });


    }

    // 방 만들기
    function room_make(){

        if(document.getElementById('room_name').value ==''){
            alert("방이름을 입력해주세요");
            return false;
        }else{

            let room_name = document.getElementById('room_name').value;
            let nickname = localStorage.getItem('nickname');
            let user_id = localStorage.getItem('user_id');
            location.href='/makeRoom?room_name='+room_name+'&nickname='+nickname+'&user_id='+user_id;

            return false;
        }
    }


</script>

</body>
</html>
