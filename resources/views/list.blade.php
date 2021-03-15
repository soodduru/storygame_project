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


<table class="table table-dark table-hover">
    <thead>
    <tr>
        <td>idx</td>
        <td>방이름</td>
        <td>방장</td>
        <td>방 입장하기</td>

    </tr>
    </thead>
    <tbody>
    @foreach($room_rows as $room_row)
        <tr>
            <td>{{$room_row->id}}</td>
            <td>{{$room_row->room_name}}</td>
            <td>{{$room_row->master_nickname}}</td>
            <td><button onclick="participateRoom({{$room_row->id}})">방 들어가기</button></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="container-md p-3 my-3 bg-dark text-white">
    <h1>방 직접 만들기</h1>
    <input type="text" name="room_name" id="room_name" placeholder="방이름을 입력해주세요"/><br>
    <button onclick="room_make()">방만들기</button>
</div>



<script>



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
                console.log(response);
                if(response.success=="200"){
                    location.href="/room/"+room_id;
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
