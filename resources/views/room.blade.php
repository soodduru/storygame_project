<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    {{--부트스트랩--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-md p-3 my-3 bg-dark text-white" id="memberList2">
    <h1>{{$room_data -> id}} 방</h1>
    <h1>방장 : {{$room_data -> master_nickname}}</h1>
</div>


<div id="startButton">

</div>

<script>
    // 게임시작 버튼은 방장만 볼 수 있도록
    master_id = "{{$room_data -> master_user_id}}";
    // 해당 웹의 user_id
    var my_user_id = localStorage.getItem("user_id");

    if(master_id==my_user_id){
        $('#startButton').html('<button onclick="story_start()">게임시작</button>');
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



                //
                if(response.success=="200") {
   /*                     $.each(response.data, function(key, value){
                            if(users.includes(value.user)==false){
                                users.push(value.user);
                                $('#memberList').append('<div style="width: 200px; height: 300px;">\n' + value.user +
                                    '    </div>');
                            }
                        })*/

                    // 닉네임 출력해주기


                    $.each(response.users, function(key, value){
                        console.log(value.nickname);
                        if(test_users.includes(value.nickname)==false){
                            test_users.push(value.nickname);
                            $('#memberList2').append('<div style="width: 200px; height: 300px;">\n' + value.nickname +
                                '    </div>');
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
        console.log("etst");
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

