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
<h1>Story Finish Page</h1>

<table class="table table-dark table-hover">
    <thead>
    <tr>
        <td>user</td>
        <td>story</td>
    </tr>
    </thead>
    <tbody>
    @foreach($stories as $story)
        <tr>
            <td>{{$story->user}}</td>
            <td>{{$story->story}}</td>
        </tr>
    @endforeach
    </tbody>
</table>ㅃ

<div>
    <button class="btn btn-success btn-sm" onclick="room_stay()">머무르기</button>
    <button class="btn btn-success btn-sm" onclick="room_out()">방나가기</button>
</div>

<script>

    const user = localStorage.getItem('user_id');

    const room_id = 39;


    // 기존 방에 머무르기 선택
    function room_stay(){

            $.ajax({
                url: "/roomStay",
                type: "get",
                data: {
                    user_id: user,
                    room_id: room_id
                },
                success: function (response) {
                    console.log("찍히낭ㅅ");


                    if(response.success=="200"){
                        console.log("들어오나");
                        // 성공 후 방으로 재진입
                        location.href="/room/"+room_id;

                    } else if(response.success=="300"){
                        // 방에 머무르는 인원이 0 이므로 일단 방목록으로 이동

                    }

                },
                error: function() {
                    console.log("에러");
                }
            });


    }

    // 방에서 나가기
    function room_out(){
        location.href="/gameRoomList";

    }



</script>
</body>
</html>
