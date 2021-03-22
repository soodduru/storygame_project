<?php

namespace App\Http\Controllers;

use App\Room;
use App\ReadyRoom;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryStatusUpdateController extends Controller
{

    // ready_room테이블의 story_status를 typing과 finish로 바꿔주는 function
    public function statusUpdate(Request $request){

        // 흐름
        // story_status: listening -> typing
        // story_status: typing ->  finish & story update
        // story_status: waiting -> listening
        // 모두 finish이면 게임 끝

        // ready_room의 story_status
        $my_status_row = ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->get()->last();
        $my_status = $my_status_row->story_status;

        // 마지막 user 구하기
        $lastuser = ReadyRoom::where('game_id',$request->game_id)->orderBy('id', 'desc')->first();

        // 웹에서의 status
        $web_status = $request->status;

        info($my_status."   :    ".$web_status);
        info($my_status."     : 현재 입력한 스토리 ".$request->story);


        // 웹과 DB의 status가 동일해야 함
        if($my_status==$web_status){

            if($request->user == $lastuser->user){
                // 내가 마지막 유저이면
                // 마지막 유저일 경우 $next_user가 없음 ($next_user: story_status가 null인 첫번째 row)
                info("내가 마지막 유저입니다");

                // my_status 수정
                if($my_status=="typing"){
                    // typing -> finish, story update
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'finish',
                        'story'=>$request->story,
                    ]);

                    return response()->json(['success'=>"200"]);

                }else if($my_status=="listening"){
                    // listening -> typing
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'typing',
                    ]);
                }

            }else{

                // my_status 수정
                if($my_status=="typing"){
                    // typing -> finish, story update
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'finish',
                        'story'=>$request->story,
                    ]);

                    // 본인의 다음 유저의 상태를 listening으로 update 해줘야 함
                    $next_user = ReadyRoom::where('game_id',$request->game_id)->whereNull('story_status')->get()->first();
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$next_user->user)->update([
                        'story_status'=>'listening',
                    ]);


                }else if($my_status=="listening"){
                    // listening -> typing
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'typing',
                    ]);


                }

            }

            return response()->json(['success'=>"200"]);

        }
        // 동일하지 않을 때는 다시 시간이 흘러서
        // web_status와 db_status가 동일해 질 수 있도록
    }


    

}
