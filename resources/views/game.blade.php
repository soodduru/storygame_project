<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>
<body>
<label for="nickname">닉네임</label>
<input type="text" name="nickname" id="nickname"/><br>
<button onclick="game_guide()">게임방법보기</button>
<button onclick="game_start()">게임시작</button>
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
