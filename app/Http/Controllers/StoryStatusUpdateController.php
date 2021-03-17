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

    // status를 typing과 finish로 바꿔주는 function

    public function statusUpdate(Request $request){

        // story_status listening -> typing
        // story_status: typing ->  finish & story update
        // story_status: waiting -> listening
        // 모두 finish이면 게임 끝

        // ready_room의 story_status column으로 비교

        $my_status_row = ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->get()->last();
        $my_status = $my_status_row->story_status;

        // 마지막 user 구하기
        $lastuser = ReadyRoom::where('game_id',$request->game_id)->orderBy('id', 'desc')->first();


        $web_status = $request->status;

        info($my_status."   :    ".$web_status);
        info($my_status."     : ".$request->story);

        /*if($my_status==$web_status){

            // 1) my_status만 가져와서 수정
            if($my_status=="typing"){
                // finish로 교체 후 story update
                ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                    'story_status'=>'finish',
                    'story'=>$request->story,
                ]);

                $next_user = ReadyRoom::where('game_id',$request->game_id)->whereNull('story_status')->get()->first();

                ReadyRoom::where('game_id',$request->game_id)->where('user',$next_user->user)->update([
                    'story_status'=>'listening',
                ]);

                return response()->json(['success'=>"200"]);

            }else if($my_status=="listening"){

                ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                    'story_status'=>'typing',
                ]);


            }else if($lastuser=="finish"){
                // 방의 마지막 사람이 finish일 경우
                // 게임 끝

            }


        } else {

            return response()->json(['success'=>"200"]);

        }*/





        if($my_status==$web_status){

            if($request->user == $lastuser->user){
                // 내가 마지막 유저이면

                info("내가 마지막유저입니다");
                // 1) my_status만 가져와서 수정
                if($my_status=="typing"){
                    // finish로 교체 후 story update
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'finish',
                        'story'=>$request->story,
                    ]);

                    // 왜 얘는 return을 안해주느거지?....ㅎ...
                    return response()->json(['success'=>"300"]);

                }else if($my_status=="listening"){
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'typing',
                    ]);
                }
            }else{
                // 1) my_status만 가져와서 수정
                if($my_status=="typing"){
                    // finish로 교체 후 story update
                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'finish',
                        'story'=>$request->story,
                    ]);

                    $next_user = ReadyRoom::where('game_id',$request->game_id)->whereNull('story_status')->get()->first();

                    ReadyRoom::where('game_id',$request->game_id)->where('user',$next_user->user)->update([
                        'story_status'=>'listening',
                    ]);

                    return response()->json(['success'=>"200"]);

                }else if($my_status=="listening"){

                    ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                        'story_status'=>'typing',
                    ]);


                }else if($lastuser=="finish"){
                    // 방의 마지막 사람이 finish일 경우
                    // 게임 끝

                }
            }

            return response()->json(['success'=>"200"]);
        }
    }





}
