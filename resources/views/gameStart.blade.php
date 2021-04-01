<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-200 leading-tight">
<head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    {{--tailwind--}}
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<nav class="bg-gray-800 sticky top-0">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
            </div>
            <div class="flex-1 flex items-center  sm:items-stretch sm:justify-start">
                <div class="flex-shrink-0 flex items-center">
                    <p class="text-lg text-white font-mono font-semibold">
                        방번호 : {{$room_id}}
                        <div class="hidden">{{$game_id}}</div>

                    </p>
                </div>
            </div>
        </div>
    </div>
</nav>


{{--게임 화면이 붙을 곳 --}}
<div class="min-h-screen flex items-center justify-center bg-gray-250 ">
    <div class="py-10 px-8 box-border h-96 w-96 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2" id="basicField">
    </div>
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
            $('#basicField').html('<div class="text-center space-y-2">\n' +
                '            <div>\n' +
                '                <div class="mt-1 border h-60 border-gray-300 rounded-md">\n' +
                '                    <p class="mt-2 text-lg font-mono text-gray-500  w-full ">\n' +
                '                        게임을 시작합니다.\n' +
                '                    </p>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '\n' +
                '        <p class="mt-2 text-lg font-mono text-gray-500 ">\n' +
                '           \n' +
                '        </p>'
            );
            status = response.user_status;
        },
        error: function() {
            console.log("에러");
        }
    });



    var date_now = Date.now();
    var time_out = date_now+30000;

    var interval = setInterval(function(){
        // 1초마다 흘러감
        var date_now = Date.now();
        var story = $('#comment').val();

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
                            status = response.user_status;
                            // web의 status와 db의 status 일치 여부에 따라
                            if(original_status==status){
                                time_out = date_now-1;
                            } else {
                                time_out = date_now+30000;
                                if(response.success=="200"){
                                    // 성공했을 시 화면 변경
                                    // 화면 바꾸기
                                    var user_status = response.user_status;
                                    var rumor = response.rumor;
                                    var active_user = response.activ_user;
                                    if(user_status=="listening"){
                                        $('#basicField').html('<div class=" text-center space-y-2">\n' +
                                            '            <div>\n' +
                                            '                <div class="overflow-y-auto mt-1 border h-60 border-gray-300 rounded-md">\n' +
                                            '                    <p class="mt-2 text-lg text-center font-mono text-gray-500  w-full ">\n' +
                                            rumor +
                                            '                    </p>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '        </div>\n' +
                                            '\n' +
                                            '        <p class="mt-2 text-lg font-mono text-gray-500 ">\n' +
                                            '소문에 집중해주세요' +
                                            '        </p>');


                                    }else if(user_status=="listening_waiting"){
                                        // 다른 사람이 듣고 있는 상태

                                        $('#basicField').html('<div class="text-center space-y-2">\n' +
                                            '            <div>\n' +
                                            '                <div class="mt-1 border h-60 border-gray-300 rounded-md">\n' +
                                            '                    <p class="mt-2 text-lg text-center font-mono text-gray-500  w-full ">\n' +
                                            '                        listening_waiting.\n' +
                                            '                    </p>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '        </div>\n' +
                                            '\n' +
                                            '        <p class="mt-2 text-lg font-mono text-gray-500 ">\n' +
                                            '다른 사람이 소문을 듣고 있습니다'+
                                            '        </p>');


                                        // 제거!!!
                                        $('#form-group').remove();


                                    }else if(user_status=="typing"){
                                        // 본인이 치고 있는 상태

                                        $('#basicField').html('<div class="text-center space-y-2" id="form-group" >\n' +
                                            '            <div>\n' +
                                            '                \n' +
                                            '                <div class="mt-1">\n' +
                                            '                    <textarea id="comment" name="about" rows="8" class="shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full text-lg border-gray-300 rounded-md"></textarea>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '        </div>\n' +
                                            '\n' +
                                            '        <p class="mt-2 text-lg font-mono text-gray-500">\n' +
                                            '소문을 작성해주세요'+
                                            '        </p>');



                                    }else if(user_status=="typing_waiting"){
                                        // 다른 사람이 적고 있는 상태

                                        $('#basicField').html('<div class="text-center space-y-2">\n' +
                                            '            <div>\n' +
                                            '                <div class="mt-1 border h-60 border-gray-300 rounded-md">\n' +
                                            '                    <p class="mt-2 text-lg font-mono text-gray-500  w-full ">\n' +
                                            '                        typing_waiting.\n' +
                                            '                    </p>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '        </div>\n' +
                                            '\n' +
                                            '        <p class="mt-2 text-lg font-mono text-gray-500 ">\n' +
                                            '다른 사람이 소문을 작성중입니다'+
                                            '        </p>');


                                    }
                                }  else if(response.success=="900"){
                                    console.log(response.success);
                                    location.href="/storyFinish/"+game_id;

                                }



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
