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

    // [방만들기]버튼 클릭 시 방 생성
    public function createRoom(Request $request){

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

        // 생성한 방으로 바로 입장
        return redirect('/room/'.$create_room->id);
    }

    // 게임참여 시 1)인원수 확인 2) user_id를 ready_room table에 넣어주기
    public function participateRoom(Request $request){

        // 인원 수 확인
        $number_of_members = ReadyRoom::where('room_id',$request->room_id)->get()->count();


        if($number_of_members>=8){
            // 경고
        } else {
            //
            if(ReadyRoom::where('room_id',$request->room_id)->where('user',$request->user)->exists()){
                // 들어왔다가 나간 경우
                return response()->json(['success'=>"200"]);
            } else {
                // 다른 방 들어갔을 때 이전 방에서 기록 삭제해줌

                // ready_room 테이블에 insert
                ReadyRoom::create([
                    'room_id'=> $request->room_id,
                    'user' => $request->user_id,
                ]);
                return response()->json(['success'=>"200"]);
            }
        }


    }


    // 방에 입장 ( 1)방 생성 후 2) [방 입장] 클릭 3) 주소입력 )
    public function joinRoom($id){

        // 방에 대한 정보
        $room_data = Room::where('id',$id)->get()->last();

        return view('room', ['room_data'=>$room_data]);

    }

    // room_id를 가지고 실시간 방 인원조회
    public function selectRoom(Request $request){

        // room_id에 해당하는 인원조회
        // 게임 시작 전이므로 game_id는 없는 것들로만 조회해야함
        $selected_list_row = ReadyRoom::where('room_id',$request->room_id)->whereNull('game_id')->get();

        $room_id= $request->room_id;



        // user의 nickname 등 user 정보 조회를 위해 user table과 join
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


        // 인원이 1명일때는 게임 진행 X
        $user_count = ReadyRoom::where('room_id',$request->room_id)->whereNull('game_id')->count();

        if($user_count<=1){
            // 인원이 1명이나 1명이하 일 때
            return response()->json(['success'=>"300"]);

        } else {

            // game_id 생성하여 room table과 ready_room table에 update
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


    // 게임 시작 후 gameStart.blade.php로 이동
    public function getGameStart($game_id){

        $room_info = ReadyRoom::where('game_id',$game_id)->get()->last();

        return view('gameStart',["game_id"=>$game_id, "room_id"=>$room_info->room_id]);


    }

    // 방에 머무르기
    public function roomStay(Request $request){

        // 1. room테이블의 room_status ->0으로
        // 2. 머무르기 선택한 인원은 ready_room 테이블에 insert(updateX)
        // 3. 방장이 나갔는지 확인 후 나갔으면 다음 멤버를 방장으로 update (room / ready_room 테이블)

        // 기존의 방정보 가져오기
        $room_info = Room::where('id',$request->room_id)->get()->first();

        info("여기는 오나요");
        Room::where('id',$request->room_id)->update([
            'room_status'=>0,
            'game_id'=>null,
        ]);

        // participateRoom() 과 겹치는 부분 존재 모듈화를 할 순 없을까?
        ReadyRoom::create([
            'room_id'=> $request->room_id,
            'user' => $request->user_id,
        ]);

        // 방장이 나갔는지 확인
        $stay_users = ReadyRoom::where('room_id',$request->room_id)->whereNull('game_id')->get();


        if(isset($stay_users)){
            // 남아있는 사람이 있음
            if($stay_users->contains('user', $room_info->master_user_id)){
                info("방장 안나갔음");
                // 그대로 재개
            } else {
                info("방장 나갔음");

                // 남은 사람중의 첫사람을 방장으로~~~~~
                $stay_users[0];
                // 닉네임 가져오기 조인 사용해서
                $user_info = DB::table('game_user')
                    ->join('ready_room', 'ready_room.user', '=', 'game_user.user_id')
                    ->where('ready_room.user','=', $stay_users[0]->user)
                    ->get()->first();

                // 방장으로 만들어주기
                Room::where('id',$request->room_id)->update([
                    'master_user_id'=>$stay_users[0]->user,
                    'master_nickname'=>$user_info->nickname,
                    'user1'=>$stay_users[0]->user,
                ]);

            }

            return response()->json(['success'=>"200",'room_id'=>$request->room_id]);

        }else {
            // 남아있는 사람이 없음-> 방폭파
            // 일단 300 뱉기
            return response()->json(['success'=>"300",'room_id'=>$request->room_id]);
        }





    }






}
