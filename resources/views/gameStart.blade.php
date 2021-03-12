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
        {{$room_id}}
    </div>
</div>


<script>

    const user = localStorage.getItem('user_id');
    const room_id = {{$room_id}};


    $.ajax({
        url: "/storyStart",
        type: "post",
        data: {
            user: user,
            room_id: room_id,
        } ,
        success: function (response) {
        //화면 바꾸기
            var user_status = response.user_status;
            var rumor = response.rumor;

            if(user_status=="LISTENING"){
                // 본인이 듣고 있는 상태
                // lisening 화면으로 room_id, user, rumor를 보내줘야 함
                // 보내주는게 아니라 제이쿼리로 여기에 창을 띄우는 거 아닌ㄱ가???
                // 그래야 밑에 interval이랑 ajax가 적용되는거 아닌가????
            }else if(user_status=="LISTENING_WAITING"){
                // 다른 사람이 듣고 있는 상태

            }else if(user_status=="TYPING"){
                // 본인이 치고 있는 상태

            }else if(user_status=="TYPING_WAITING"){
                // 다른 사람이 듣고 있는 상태
            }


        },
        error: function() {
            console.log("에러");
        }
    });

    var date_now = new Date();
    var time_out = date_now+30;//new Date(+ 30초);

    var interval = setInterval(function(){
        //1초마다 흘러감
        var date_now = new Date();

        var story = "nljlasdf";
        if(date_now > time_out){
            $.ajax({
                url: "/storyUpdate",
                type: "post",
                data: {
                    user: user,
                    room_id: room_id,
                    story : story,
                } ,
                success: function (response) {
``                  $.ajax({
                        url: "/storyStart",
                        type: "post",
                        data: {
                            user: user,
                            room_id: room_id,
                        } ,
                        success: function (response) {
                            ``//화면 바꾸기
                            time_out = date_now+30;
                            if(response.success=="200"){
                                // 성공했을 시 화면 변경 
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
    },100)

</script>
</body>
</html>
