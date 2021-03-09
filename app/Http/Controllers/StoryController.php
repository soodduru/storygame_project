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
        $story_null = ReadyRoom::whereNull('story')->get()->first();
        //현재 STORY가 EXISTS!
        $story_exists = ReadyRoom::whereNotNull('story')->get()->last();
        // 해당 방의 본인
        $my_status_row = ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->get()->last();

        foreach ($all_participant_status_rows as $all_participant_status_row){
            if($all_participant_status_row->user==$story_null->user){
                if(isset($story_exists)){
                    if($story_exists->story=="typing"){
                        info('typing'.$story_exists->user."가 입력중입니다.");

                        if($all_participant_status_row->user==$request->user){
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'(n)TYPING','typing'=>'active']);
                        }else{
                            return response()->json(['activ_user'=>$all_participant_status_row->user,'user_status'=>'(n)TYPING','typing'=>"N번째 유저가 소문을 치고 있습니다.."]);
                        }
                        //타이핑하는 유저만 행동하게 하는 리턴

                    }elseif($story_exists->story=="listening"){
                        info("첫번째 유저가 소문을 듣고있다.");
                        //첫번째 유저만 행동하게 하는 리턴
                    }else{
                        //story null 유저가 입력을 시작하게끔 하는 코드
                    }
                }else{
                    //첫번쨰 유저가 소문을 듣게 시작하는 코드
                    if($all_participant_status_rows[0]->user==$request->user){
                        $rumor = "조수연 바보";//template_Rumor::;
                        return response()->json(['activ_user'=>$all_participant_status_rows[0]->user,'user_status'=>'rumor_listening','rumor'=>$rumor]);
                    }else{
                        return response()->json(['activ_user'=>$all_participant_status_rows[0]->user,'user_status'=>'rumor_listening','rumor'=>"첫번째 유저가 소문을 듣고 있습니다."]);
                    }
                }
            }
        }
        return true;


    }


}
