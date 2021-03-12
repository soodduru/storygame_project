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
        $all_participant_status_rows = ReadyRoom::where('room_id',$request->room_id)->get();

        // 현재 story가 null
        $story_null = ReadyRoom::whereNull('story_status')->get()->first();
        //현재 STORY가 EXISTS!
        $story_exists = ReadyRoom::whereNotNull('story_status')->get()->last();
        // 해당 방의 본인
        $my_status_row = ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->get()->last();

        // room_id로 특정해줘야 하는 것 아닌가?
        // $story_null = ReadyRoom::where('room_id',$request->room_id)->whereNull('story_status')->get()->first();
        // $story_exists = ReadyRoom::where('room_id',$request->room_id)->whereNotNull('story_status')->get()->last();


        foreach ($all_participant_status_rows as $all_participant_status_row){
            if($all_participant_status_row->user==$story_null->user){
                if(isset($story_exists)){
                    // readyroom에 story가 하나라도 존재하는 것이 있으면
                    if($story_exists->story=="typing"){
                        info('typing'.$story_exists->user."가 입력중입니다.");

                        if($all_participant_status_row->user==$request->user){
                            // 타이핑 하는 타자가 지일때
                            // 타이핑 할 수 있는 화면을 출력 할 수 있도록
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'TYPING','typing'=>'active']);
                        }else{
                            // 기다리는 상태 화면을 출력할 수 있도록
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'TYPING_WAITING','typing'=>"N번째 유저가 소문을 치고 있습니다.."]);
                        }

                    }elseif($story_exists->story=="listening"){
                        // 모든 listening하고 있는 유저들에 해당하는 코드
                        // 본인과 본인이 아닐때로 구분
                        // 본인이면 listening해서 루머를 보여주고 본인이 아니면 기다리는 상태 출력해주기
                        // typing으로 넘기는 부분은 여기가 아님!
                        info('listening'.$story_exists->user."가 소문을 듣고 있습니다.");

                        if($all_participant_status_row->user==$request->user){
                            // 듣는 사람이 본인 일 때
                            // 루머 화면에 출력
                            $rumor = "조수연 바보";
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'LISTENING','listening'=>'active','rumor'=>$rumor]);

                        }else{
                            // 기다리는 상태 화면을 출력할 수 있도록
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'LISTENING_WAITING','listening'=>"번째 유저가 소문을 듣고 있습니다..",'rumor'=>"번째 유저가 소문을 듣고 있습니다."]);
                        }


                    }else{


                    }
                }else{
                    if($all_participant_status_rows[0]->user==$request->user){
                        // 일빠따가 본인일때
                        $rumor = "조수연 바보";// #rumor_template에서 rumor 불러 올 수 있는 코드 추가필요

                        // 1타자의 strory_satus를 listening으로 update
                        ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->update([
                            'story_status'=>'listening'
                        ]);

                        return response()->json(['activ_user'=>$all_participant_status_rows[0]->user,'user_status'=>'rumor_listening','rumor'=>$rumor]);
                        // 이 리턴 부분이 필요한지 모르겠음....

                    }else{
                        // 일빠따를 제외한 참여자들
                        return response()->json(['activ_user'=>$all_participant_status_rows[0]->user,'user_status'=>'rumor_listening','rumor'=>"첫번째 유저가 소문을 듣고 있습니다."]);

                        // user_status로 화면 구성을 구별할 수 있도록
                        // rumor_listening -> rumor_wating으로
                        // rumor를 통해 화면에 출력해주는 것
                    }
                }
            }
        }

        return true;


    }


}
