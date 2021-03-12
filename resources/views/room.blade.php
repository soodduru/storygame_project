<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

</head>
<body>
<h1>{{$room_data -> id}} 방</h1>
<h1></h1>
<div id="memberList">
    <div style="width: 200px; height: 300px;">
      {{$room_data -> master_nickname}}
    </div>
</div>

<button onclick="story_start()">게임시작</button>
<script>

    const room_id = {{$room_data -> id}};
    // 배열 선언
    var users = new Array();


    var room_socket = setInterval(function(){

        $.ajax({
            url: "/roomReadyCheck",
            type: "post",
            data: {
                room_id: room_id,
            } ,
            success: function (response) {
                if(response.gameStatus==1){
                    location.href="/gameStart/"+room_id;
                }
                if(response.success=="200") {
                        $.each(response.data, function(key, value){
                            if(users.includes(value.user)==false){
                                users.push(value.user);
                                $('#memberList').append('<div style="width: 200px; height: 300px;">\n' + value.user +
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

                // gameStart.blade.php로 넘어가야함 
            },
            error: function() {
                console.log("에러");
            }
        });
    }

</script>
</body>
</html>

