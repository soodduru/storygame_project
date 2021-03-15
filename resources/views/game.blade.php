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



<div class="container-md p-3 my-3 bg-dark text-white">
    <h1>Story Game</h1>
    <p>아무말이나 즐겨보세요~</p>
    {{--<label for="nickname">닉네임</label>--}}
    <input type="text" name="nickname"  class="form-control" id="nickname" placeholder="닉네임을 입력해주세요"/><br>
    <button class="btn btn-success btn-sm" onclick="game_guide()">게임방법보기</button>
    <button class="btn btn-success btn-sm" onclick="game_start()">게임시작</button>
</div>



<script>
    // 1. 게임설명
    function game_guide(){
        location.href = "/guide";
    }

    // 2. user_id용 random string
    // dec2hex :: Integer -> String
    // i.e. 0-255 -> '00'-'ff'
    function dec2hex (dec) {
        return dec.toString(16).padStart(2, "0")
    }

    // generateId :: Integer -> String
    function generateId (len) {
        var arr = new Uint8Array((len || 40) / 2)
        window.crypto.getRandomValues(arr)
        return Array.from(arr, dec2hex).join('')
    }

    // 3. 게임시작
    function game_start(){

        if(document.getElementById('nickname').value ==''){
            // 닉네임 입력 안했을시
            alert("닉네임을 입력해주세요");
        }else{
            let user_id = generateId();
            let nickname = document.getElementById('nickname').value;

            localStorage.setItem('user_id', user_id);
            localStorage.setItem('nickname', nickname);

            // 게임 시작 전 user_id, nickname을 game_user 테이블에 넣어주기
            $.ajax({
                url: "gameUserCreate",
                type: "post",
                data: {
                    user_id: user_id,
                    nickname: nickname
                },
                success: function (response) {
                    console.log(response);
                    if(response.success=="200"){
                        location.href="gameRoomList";
                    } else if(response.success=="300"){
                        // 중복 닉네임
                        alert("이미 존재하는 닉네임입니다");
                    }
                },
                error: function() {
                    console.log("에러");
                }
            });

        }

    }
</script>
</body>
</html>
