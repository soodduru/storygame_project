<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    {{--부트스트랩--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous"></head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid p-3 my-3 bg-info text-white text-center" style="margin-bottom:0">
    <h1><i class="far fa-keyboard"></i> 게임을 시작합니다 <i class="far fa-keyboard"></i></h1>
    <p>[방만들기] 또는 [입장하기] 버튼을 통해 게임을 시작해보세요</p>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<div style="display : inline-block;">
    <input class="form-control col-xs-4" type="text" name="room_name" id="room_name" placeholder="방 이름을 입력해주세요">
    <button class="btn btn-info" style="float: right;" onclick="room_make()">방만들기</button>
</div>
</nav>

<div class="container" style="margin-top:30px">

</div>

<div >
    <table class="table table-dark table-hover text-center">
        <thead>
        <tr>
            <td>방번호</td>
            <td>방이름</td>
            <td>방장</td>
            <td>방 입장하기</td>
        </tr>
        </thead>
        <tbody id="roomListTest">
        </tbody>
    </table>
</div>

<div class="jumbotron text-center" style="margin-bottom:0">
    <p>Footer</p>
</div>


<script>


    var lists = new Array();

    var list_socket = setInterval(function(){
        $.ajax({
            url: "/roomListTest",
            type: "post",
            data: {
            } ,
            success: function (response) {

                if(response.success=="200") {

                    $.each(response.room_rows, function(key, value){

                        if(lists.includes(value.id)==false){
                            lists.push(value.id);
                            $('#roomListTest').append('<tr>\n' +
                                '            <td>' + value.id+ '</td>\n' +
                                '            <td>' + value.room_name+'</td>\n' +
                                '            <td>' + value.master_nickname+'</td>\n' +
                                (value.room_status == 0 ?
                                '            <td><button class="btn btn-warning" onclick="participateRoom(' + value.id + ')">방 입장</button></td>\n' :
                                    '<td><button class="btn btn-secondary" disabled="disabled">게임 중</button></td>\n' )+
                                    '        </tr>');

                        }
                    })

                }


            },
            error: function() {
                console.log("에러");
            }
        });

    }, 1000);


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
