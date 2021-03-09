<?php

namespace App\Http\Controllers;

use App\Room;
use App\ReadyRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameController extends Controller
{

    // 방목록
    public function roomList(){
        $selected_list_rows = Room::all();
        return view('list', ['room_rows'=>$selected_list_rows]);

    }

    // 방생성
    public function createRoom(Request $request){

        // info($request->nickname);
        $create_room=Room::create([
            'master_nickname'=> $request->nickname,
            'master_user_id' => $request->user_id,
            'user1' => $request->user_id,
            'room_name' => $request->room_name,
        ]);

        // 방장 정보를 ready_room table에도 넣어주기
        ReadyRoom::create([
            'room_id' => $create_room->id,
            'user'=> $create_room->user1,
        ]);
        info($create_room->id);

        // return false;
        // $this->enterRoom();

        return redirect('/room/'.$create_room->id);
       // return view('room', ['room_data'=>$create_room]);
    }

    // 게임참여 시 1)인원수 확인 2) user_id를 ready_room table에 넣어주기
    public function participateRoom(Request $request){

        // info($request->room_id);

        $number_of_members = ReadyRoom::where('room_id',$request->room_id)->get()->count();
        // info($number_of_members);
        if($number_of_members>=8){
            // 경고
        } else {

            if(ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->exists()){
                return response()->json(['success'=>"200"]);
            } else {
                // 다른 방 들어갔을 때 이전 방에서 기록 삭제해줌
               /* ReadyRoom::where('user',$request->user)->delete();*/
                ReadyRoom::create([
                    'room_id'=> $request->room_id,
                    'user' => $request->user_id,
                ]);
                return response()->json(['success'=>"200"]);
            }
        }


    }


    // 방에 입장했을 때
    public function joinRoom($id){
        info($id);
        /*$selected_list_rows = ReadyRoom::where('room_id',$id)->get();
        info($selected_list_rows);*/
        // return view('test');
        // return view('room', ['room_data'=>$selected_list_rows]);
        $room_data = Room::where('id',$id)->get()->last();
        info($room_data->master_nickname);
        return view('room', ['room_data'=>$room_data]);



    }

    // room id를 가지고 방에 있는 실시간 인원조회
    public function selectRoom(Request $request){

        $selected_list_row = ReadyRoom::where('room_id',$request->room_id)->get();
        // 게임 상태 체크 gameStatus

        $game_status= Room::where('id',$request->room_id)->get()->first();
        if($game_status->room_status==1){
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>1]);
        } else {
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>0]);
        }


    }

    // 게임 시작 버튼 클릭
    public function gameStart(Request $request){

        $game_id = Str::random(40);
        
        Room::where('id',$request->room_id)->update([
            'room_status'=>1,
            'game_id'=>$game_id,
            ]);



        ReadyRoom::where('room_id',$request->room_id)->update([
            'game_id'=>$game_id,
        ]);

    }


    public function getGameStart($id){

        return view('gameStart',["room_id"=>$id]);

    }

}
