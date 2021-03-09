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

            
        },
        error: function() {
            console.log("에러");
        }
    });


</script>
</body>
</html>
