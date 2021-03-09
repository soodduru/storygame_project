<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

</head>
<body>

room_rows
<table>
    <thead>
    <tr>
        <td>idx</td>
        <td>방이름</td>
        <td>방장</td>
        <td>master_user_id</td>

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


<label for="room_name">방이름</label>
<input type="text" name="room_name" id="room_name"/><br>
<button onclick="room_make()">방만들기</button>

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
