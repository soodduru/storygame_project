<?php

namespace App\Http\Controllers;

use App\Room;
use App\ReadyRoom;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends Controller
{

    public function storyStart(Request $request){
        // 방 인원 전체
        $all_participant_status_rows = ReadyRoom::where('game_id',$request->game_id)->get();

        // 현재 story가 null
        $story_null = ReadyRoom::where('game_id',$request->game_id)->whereNull('story')->get()->first();
        //현재 STORY가 EXISTS!
        $story_exists = ReadyRoom::where('game_id',$request->game_id)->whereNotNull('story_status')->get()->last();
        // 해당 방의 본인
        $my_status_row = ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->get()->last();


        // git test
        // git commit test

        foreach ($all_participant_status_rows as $all_participant_status_row){
            if($all_participant_status_row->user==$story_null->user){
                if(isset($story_exists)){
                    // readyroom에 story가 하나라도 존재하는 것이 있으면
                    if($story_exists->story_status=="typing"){
                        info('typing'.$story_exists->user."가 입력중입니다.");

                        if($all_participant_status_row->user==$request->user){
                            // 타이핑 하는 타자가 지일때
                            // 타이핑 할 수 있는 화면을 출력 할 수 있도록
                            return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'typing','typing'=>'active']);
                        }else{
                            // 기다리는 상태 화면을 출력할 수 있도록
                            return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'typing_waiting','typing'=>"N번째 유저가 소문을 치고 있습니다.."]);
                        }

                    }elseif($story_exists->story_status=="listening"){

                        info('listening'.$story_exists->user."가 소문을 듣고 있습니다.");


                        if($all_participant_status_row->user==$request->user){
                            // 듣는 사람이 본인 일 때
                            // 루머 화면에 출력
                            // story가 null이 아닌 제일 마지막 애
                            if($all_participant_status_rows[0]->user==$request->user){
                                $rumor = "조수연 천재"; // template 에서
                            }else{
                                $last_story_row = ReadyRoom::where('game_id',$request->game_id)->whereNotNull('story')->get()->last();
                                $rumor = $last_story_row->story;
                            }
                            return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'listening','listening'=>'active','rumor'=>$rumor]);

                        }else{
                            // 기다리는 상태 화면을 출력할 수 있도록
                            return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'listening_waiting','listening'=>"번째 유저가 소문을 듣고 있습니다..",'rumor'=>"번째 유저가 소문을 듣고 있습니다."]);
                        }


                    }

                }else{
                    if($all_participant_status_rows[0]->user==$request->user){
                        // 일빠따가 본인일때
                        $rumor = "조수연 바보";// #rumor_template에서 rumor 불러 올 수 있는 코드 추가필요

                        // 1타자의 strory_satus를 listening으로 update
                        ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->update([
                            'story_status'=>'listening'
                        ]);

                        return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'GAME_START']);

                    }else{
                        // 일빠따를 제외한 참여자들
                        return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'GAME_START']);
                    }


                }
            }
        }




    }


}
