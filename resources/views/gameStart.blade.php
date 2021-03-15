<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>
<body>
<div id="game">
    <div id="storyBoard" style="width: 500px; height: 500px;">
        방번호 : {{$room_id}}
        {{$game_id}}

    </div>
</div>

// test용
<div>
    <button onclick="test()";>다음단계로 이동</button>
</div>
<div id="basicBoard">
</div>


<script>

    const user = localStorage.getItem('user_id');
    const room_id = {{$room_id}};
    const game_id = "{{$game_id}}";

    // 최초의
    var status="";

    $.ajax({
        url: "/storyStart",
        type: "post",
        data: {
            user: user,
            room_id: room_id,
            game_id: game_id,
        },
        success: function (response) {

            console.log(response.success);
            $('#basicBoard').text(response.success);

            status = response.user_status;
            // 잠시 후 게임이 시작됩니다
        },
        error: function() {
            console.log("에러");
        }
    });






    function test(){

        var story = "와리바리 test";

        $.ajax({
            url: "/storyUpdate",
            type: "post",
            data: {
                user: user,
                room_id: room_id,
                status: status,
                story : story,
                game_id: game_id,
            } ,
            success: function (response) {
                $.ajax({
                    url: "/storyStart",
                    type: "post",
                    data: {
                        user: user,
                        room_id: room_id,
                        game_id: game_id,
                    } ,
                    success: function (response) {
                        //화면 바꾸기

                        var original_status = status;
                        console.log(response.user_status);
                        status = response.user_status;
                        $('#basicBoard').text(response.user_status);

                        if(response.success=="200"){
                            // 성공했을 시 화면 변경
                            //화면 바꾸기
                            var user_status = response.user_status;
                            var rumor = response.rumor;
                            var active_user = response.activ_user;

                            if(user_status=="listening"){

                                $('#basicBoard').html('<div style="width: 200px; height: 300px;">\n' + rumor + '</div>');


                            }else if(user_status=="listening_waiting"){
                                // 다른 사람이 듣고 있는 상태
                                $('#basicBoard').html('<div style="width: 200px; height: 300px;">\n' + rumor + '</div>');

                            }else if(user_status=="typing"){
                                // 본인이 치고 있는 상태

                            }else if(user_status=="typing_waiting"){
                                // 다른 사람이 듣고 있는 상태
                            }
                        }
                           /* if(original_status==status){
                                time_out = date_now-1;
                            } else {
                                time_out = date_now+30;
                            }*/
                        //
                    },
                    error: function() {
                        console.log("에러");
                    }
                });

            },
            error: function() {
                console.log("에러");
            }
        });

    }


    var date_now = Date.now();
    var time_out = date_now+5000;//new Date(+ 30초);


    var interval = setInterval(function(){
        //1초마다 흘러감


        var date_now = Date.now();
        console.log("now : " + date_now + "time out : " + time_out);

        if(date_now > time_out){
        var story = "와리바리 test";

        $.ajax({
            url: "/storyUpdate",
            type: "post",
            data: {
                user: user,
                room_id: room_id,
                status: status,
                story : story,
                game_id: game_id,
            } ,
            success: function (response) {
                $.ajax({
                    url: "/storyStart",
                    type: "post",
                    data: {
                        user: user,
                        room_id: room_id,
                        game_id: game_id,
                    } ,
                    success: function (response) {
                        //화면 바꾸기

                        var original_status = status;
                        console.log(response.user_status);
                        status = response.user_status;
                        $('#basicBoard').text(response.user_status);

                        if(response.success=="200"){
                            // 성공했을 시 화면 변경
                            //화면 바꾸기
                            var user_status = response.user_status;
                            var rumor = response.rumor;
                            var active_user = response.activ_user;

                            if(user_status=="listening"){

                                $('#basicBoard').html('<div style="width: 200px; height: 300px;">\n' + rumor + '</div>');


                            }else if(user_status=="listening_waiting"){
                                // 다른 사람이 듣고 있는 상태
                                $('#basicBoard').html('<div style="width: 200px; height: 300px;">\n' + rumor + '</div>');

                            }else if(user_status=="typing"){
                                // 본인이 치고 있는 상태

                            }else if(user_status=="typing_waiting"){
                                // 다른 사람이 듣고 있는 상태
                            }
                        }

                        // web의 status와 db의 status 일치 여부에 따라
                            if(original_status==status){
                                time_out = date_now-1;
                            } else {
                                time_out = date_now+5000;
                            }

                    },
                    error: function() {
                        console.log("에러");
                    }
                });

            },
            error: function() {
                console.log("에러");
            }
        });
            }
    },1000)

</script>
</body>
</html>
