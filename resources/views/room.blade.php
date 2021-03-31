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

    <link href="/css/style.css" rel="stylesheet" type="text/css">
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
                        {{$room_data -> id}} 방 <br>
                        {{$room_data -> master_nickname}}
                    </p>
                </div>
                <div class="sm:block sm:ml-6">
                    <div class="flex space-x-4">
                       <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">방 나가기</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>
<div class="min-h-screen flex items-center justify-center bg-gray-250 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class=" overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="game-box" id="memberList">
                        <div class="nickname-wrap" id="memberList">
                            <div class="nickname-A" id="nickname-A">
                            </div>
                            <div class="nickname-B" id="nickname-B">
                            </div>
                        </div>
                    </div>



                </div>
                <div id="startButton">

                </div>
            </div>
        </div>
    </div>
</div>







<script>
    // 게임시작 버튼은 방장만 볼 수 있도록
    master_id = "{{$room_data -> master_user_id}}";
    // 해당 웹의 user_id
    var my_user_id = localStorage.getItem("user_id");
    var my_user_nickname = localStorage.getItem("nickname");

    if(master_id==my_user_id){
        $('#startButton').html('<button class="py-2 px-4 font-semibold rounded-lg shadow-md font-mono text-white bg-green-500 hover:bg-green-700" onclick="story_start()">게임시작</button>');
    }

    const room_id = {{$room_data -> id}};
    // 배열 선언
    var users = new Array();
    var test_users = new Array();


    var room_socket = setInterval(function(){

        $.ajax({
            url: "/roomReadyCheck",
            type: "post",
            data: {
                room_id: room_id,
            } ,
            success: function (response) {
                if(response.gameStatus==1){
                    location.href="/gameStart/"+response.game_id;
                }


                if(response.success=="200") {
                    var i = 0;
                    $.each(response.users, function(key, value){

                        if(test_users.includes(value.nickname)==false){
                            test_users.push(value.nickname);
                            i++;
                            if(test_users.length<=4){
                                $('#nickname-A').append(
                                    '<div class="nickname" id="nickname0' + i +'">'+value.nickname+'</div>'
                                );
                            } else {
                                $('#nickname-B').append(
                                    '<div class="nickname" id="nickname0' + i +'">'+value.nickname+'</div>'
                                );
                            }

                        }

                    })

                }


            },
            error: function() {
                console.log("에러");
            }
        });

    }, 1000);


    function story_start(){

        // ajax
        $.ajax({
            url: "/gameStart",
            type: "post",
            data: {
                room_id: room_id,
            } ,
            success: function (response) {

                if(response.success=="200"){
                    // 성공 시 gameStart.blade.php로 이동
                    // game_id만 들고가기
                    location.href="/gameStart/"+response.game_id;

                } else if (response.success=="300") {

                    alert("2명 이상이어야 게임이 가능합니다");
                }

            },
            error: function() {
                console.log("에러");
            }
        });
    }

</script>



</body>
</html>

