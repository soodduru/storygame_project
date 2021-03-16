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

    // 방목록을 실시간 확인 할 수 있도록
    public function roomListTest(){
        $selected_list_rows = Room::all();
        return response()->json(['success'=>"200", 'room_rows'=>$selected_list_rows]);
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

        // 방장 정보를 ready_room table에 넣어주기
        ReadyRoom::create([
            'room_id' => $create_room->id,
            'user'=> $create_room->user1,
        ]);
        // info($create_room->id);

        // 바로 생성한 방으로 입장
        return redirect('/room/'.$create_room->id);
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

        // nickname 출력을 위한 join 절
        // room_id가 $request->room_id인 ready_room의 user == game_user의 user_id 의 닉네임
        // room_id 가

        $room_id= $request->room_id;


/*        $users = DB::table('game_user')
            ->join('ready_room', function($join) {
            $join->on('game_user.user_id','=','ready_room.user')
                ->where('ready_room.room_id','=',$room_id);
        })->get();*/

        $users = DB::table('game_user')
            ->join('ready_room', 'ready_room.user', '=', 'game_user.user_id')
            ->where('ready_room.room_id','=',$room_id)
            ->get();



        $game_status= Room::where('id',$request->room_id)->get()->first();
        if($game_status->room_status==1){
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>1,'game_id'=>$game_status->game_id, 'users'=>$users]);
        } else {
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>0,'users'=>$users]);
        }


    }

    // 게임 시작 버튼 클릭
    public function gameStart(Request $request){


        // 인원이 1명일때는 게임 진행 X

        $user_count = ReadyRoom::where('room_id',$request->room_id)->count();



        if($user_count<=1){
            // 인원이 1명이나 1명이하 일 때

            return response()->json(['success'=>"300"]);


        } else {

            // game_id 생성
            $game_id = Str::random(40);

            Room::where('id',$request->room_id)->update([
                'room_status'=>1,
                'game_id'=>$game_id,
            ]);


            ReadyRoom::where('room_id',$request->room_id)->update([
                'game_id'=>$game_id,
            ]);

            // 생성한 random game_id를 다시 보내주기
            return response()->json(['success'=>"200",'game_id'=>$game_id]);
        }



    }


    public function getGameStart($game_id){

        $room_info = ReadyRoom::where('game_id',$game_id)->get()->last();

        return view('gameStart',["game_id"=>$game_id, "room_id"=>$room_info->room_id]);


    }

}
