<?php

namespace App\Http\Controllers;

use App\Room;
use App\ReadyRoom;
use App\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameController extends Controller
{

    // 방목록 불러오기
    public function roomList(){
        $selected_list_rows = Room::all();
        return view('list', ['room_rows'=>$selected_list_rows]);
    }

    // 방목록을 실시간 확인
    public function roomListCheck(){
        $selected_list_rows = Room::all();
        return response()->json(['success'=>"200", 'room_rows'=>$selected_list_rows]);
    }

    // [방만들기]버튼 클릭 시 방 생성
    public function createRoom(Request $request){

        $create_room=Room::create([
            'master_nickname'=> $request->nickname,
            'master_user_id' => $request->user_id,
            'user1' => $request->user_id,
            'room_name' => $request->room_name,
        ]);

        // 방장 정보를 ready_room 테이블에 넣어주기
        ReadyRoom::create([
            'room_id' => $create_room->id,
            'user'=> $create_room->user1,
        ]);

        // 생성한 방으로 바로 입장
        return redirect('/room/'.$create_room->id);
    }

    // 게임참여 시 1)인원수 확인 2) user_id를 ready_room 테이블에 넣어주기
    public function participateRoom(Request $request){

        // 인원 수 확인
        $number_of_members = ReadyRoom::where('room_id',$request->room_id)->get()->count();


        if($number_of_members>=8){
            // 경고
            return response()->json(['success'=>"300"]);
        } else {
            //
            if(ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->exists()){
                // 들어왔다가 나간 경우
                return response()->json(['success'=>"200"]);
            } else {

                // ready_room 테이블에 insert
                ReadyRoom::create([
                    'room_id'=> $request->room_id,
                    'user' => $request->user_id,
                ]);
                return response()->json(['success'=>"200"]);
            }
        }


    }


    // 방에 입장 ( 입장 종류 : 1)방 생성 후 2) [방 입장] 클릭 3) 주소입력 )
    public function joinRoom($id){

        // 방에 대한 정보
        $room_data = Room::where('id',$id)->get()->last();

        return view('room', ['room_data'=>$room_data]);

    }

    // room_id를 가지고 실시간 방 인원조회
    public function selectRoom(Request $request){

        // room_id에 해당하는 인원조회
        // 게임 시작 전이므로 game_id는 없는 것들만 조회해야함
        $selected_list_row = ReadyRoom::where('room_id',$request->room_id)->whereNull('game_id')->get();
        $room_id= $request->room_id;

        // user의 nickname 등 user 정보 조회를 위해 user 테이블과 join
        $users = DB::table('game_user')
            ->join('ready_room', 'ready_room.user', '=', 'game_user.user_id')
            ->where('ready_room.room_id','=',$room_id)
            ->where('ready_room.game_id','=',null)
            ->get();

        $game_status= Room::where('id',$request->room_id)->get()->first();

        if($game_status->room_status==1){
            // 게임 중
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>1,'game_id'=>$game_status->game_id, 'users'=>$users]);
        } else {
            // 게임 아직 시작 안한 경우
            return response()->json(['data'=>$selected_list_row, 'success'=>"200",'gameStatus'=>0,'users'=>$users]);
        }

    }

    // [게임시작] 버튼 클릭
    public function gameStart(Request $request){
        // 인원조회
        $user_count = ReadyRoom::where('room_id',$request->room_id)->whereNull('game_id')->count();

        $users = DB::table('game_user')
            ->join('ready_room', 'ready_room.user', '=', 'game_user.user_id')
            ->where('ready_room.game_id','=', null)
            ->where('ready_room.room_id','=', $request->room_id)
            ->get();

        if($user_count<=1){
            // 인원이 1명이나 1명이하 일 때 게임 시작 X
            return response()->json(['success'=>"300"]);

        } else {
            // game_id 생성하여 room 테이블과 ready_room 테이블에 update
            $game_id = Str::random(40);

            // room table과 ready_room 테이블에 둘 다 update
            Room::where('id',$request->room_id)->update([
                'room_status'=>1,
                'game_id'=>$game_id,
            ]);

            ReadyRoom::where('room_id',$request->room_id)->update([
                'game_id'=>$game_id,
            ]);

            // story 테이블에 바로 넣어주기 (게임 끝났을 때 story update를 위해)
            foreach ($users as $user) {
                Story::create([
                    'room_id'=> $user->room_id,
                    'user_id' => $user->user_id,
                    'user_nickname' => $user->nickname,
                    'game_id' => $game_id,
                ]);
            }
            // 생성한 random game_id를 다시 보내주기
            return response()->json(['success'=>"200",'game_id'=>$game_id]);
        }

    }


    // 게임 시작 후 gameStart.blade.php로 이동
    public function getGameStart($game_id){

        // 방 정보
        $room_info = ReadyRoom::where('game_id',$game_id)->get()->last();

        return view('gameStart',["game_id"=>$game_id, "room_id"=>$room_info->room_id]);

    }



}
