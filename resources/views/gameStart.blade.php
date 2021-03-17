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


<div id="gameBoard">


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





    var date_now = Date.now();
    var time_out = date_now+5000;//new Date(+ 30초);


    var interval = setInterval(function(){
        //1초마다 흘러감


        var date_now = Date.now();
       // console.log("now : " + date_now + "time out : " + time_out);

        var story = $('#comment').val();
        console.log("interval"story);
        if(date_now > time_out){

            var story = $('#comment').val();
            console.log(story);
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

                                $('#basicBoard').html('<div id="rumorField" style="width: 200px; height: 300px;">\n' + rumor + '</div>');


                            }else if(user_status=="listening_waiting"){
                                // 다른 사람이 듣고 있는 상태
                                $('#basicBoard').html('<div id="rumorField" style="width: 200px; height: 300px;">\n' + rumor + '</div>');
                                $('.form-group').remove();

                            }else if(user_status=="typing"){
                                // 본인이 치고 있는 상태
                                $('#rumorField').remove();
                                $('#gameBoard').html('<div class="form-group"> \n' +
                                    '                                    <label for="comment">Comment:</label> \n' +
                                    '                                <textarea class="form-control" rows="5" id="comment"></textarea>\n' +
                                    '                                    </div>');

                                //var textarea = document.getElementById('#comment');

                                //console.log(textarea.value);

                            }else if(user_status=="typing_waiting"){
                                // 다른 사람이 듣고 있는 상태
                                $('#basicBoard').html('<div id="rumorField" style="width: 200px; height: 300px;">\n' + rumor + '</div>');

                            }
                        } else if(response.success=="300") {

                            location.href="/";
                        } else if(response.success=="900"){
                            console.log(response.success);
                            location.href="/storyFinish/"+game_id;

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
