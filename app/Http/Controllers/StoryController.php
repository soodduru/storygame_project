<?php

namespace App\Http\Controllers;

use App\Room;
use App\ReadyRoom;
use App\Story;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends Controller
{

    // story_status에 따라 유저의 화면을 변경
    public function storyStart(Request $request){

        // 방 인원 전체
        $all_participant_status_rows = ReadyRoom::where('game_id',$request->game_id)->get();

        // 현재 story가 null
        $story_null = ReadyRoom::where('game_id',$request->game_id)->whereNull('story')->get()->first();
        //현재 story_status가 EXISTS!
        $story_exists = ReadyRoom::where('game_id',$request->game_id)->whereNotNull('story_status')->get()->last();
        // 해당 방의 본인
        $my_status_row = ReadyRoom::where('game_id',$request->game_id)->where('user',$request->user)->get()->last();


        //
        if(isset($story_null)){
            foreach ($all_participant_status_rows as $all_participant_status_row){
                if($all_participant_status_row->user==$story_null->user){
                    if(isset($story_exists)){
                        if($story_exists->story_status=="typing"){
                            if($all_participant_status_row->user==$request->user){
                                // 타이핑 하는 타자가 본인 일 때
                                // 타이핑 할 수 있는 화면을 출력 할 수 있도록
                                return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'typing','typing'=>'active']);
                            }else{
                                // 기다리는 상태 화면을 출력할 수 있도록
                                return response()->json(['success'=>"200",'activ_user'=>$all_participant_status_row->user,'user_status'=>'typing_waiting','typing'=>"N번째 유저가 소문을 치고 있습니다.."]);
                            }

                        }elseif($story_exists->story_status=="listening"){



                            if($all_participant_status_row->user==$request->user){
                                // 듣는 사람이 본인 일 때
                                // 루머 화면에 출력
                                // story가 null이 아닌 제일 마지막 애
                                if($all_participant_status_rows[0]->user==$request->user){
                                    $rumor = "진경이가 하루랑 해리라고 강아지 키우거든? 근데 강현이가 강아지를 하루만 데리고 오자고 언주한테 말했대. 강현이가 개 좋아하거든. 그래서 강아지를 그렇게 싫어하는 언주가 허락해줘서 성공했데 잘됐지?
                                                아 근데 진경이는 이번주 휴가라서 강아지 어떡하지??? 진수한테 해리 맡길거라고 하던데 떡볶이 1인분 사달라그러더라
                                                떡볶이 맛있겠다 지금 먹으러가자"; // template 에서 이야기 제공
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


        } else {

            info("900부분");
            $stories = ReadyRoom::where('game_id',$request->game_id)->get();

            foreach ($stories as $story) {
                Story::where('game_id',$request->game_id)->where('user_id',$story->user)->update([
                    'story'=>$story->story,
                ]);

            }

            return response()->json(['success'=>"900"]);

        }



    }

    // 게임 종료 시 최종 이야기 전달을 확인할 수 있는 화면으로 이동
    public function storyFinish($game_id){

        $stories = Story::where('game_id',$game_id)->get();

        // room_id 전달을 위해
        $story = Story::where('game_id',$game_id)->get()->first();

        return view('storyFinish',["stories"=>$stories,"room_id"=>$story->room_id]);

    }




}
