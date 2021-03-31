<?php

namespace App\Http\Controllers;

use App\GameUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameUserController extends Controller
{

    // user 생성
    public function createUser(Request $request){


        // 중복 닉네임 확인
        $user = GameUser::where('nickname',$request->nickname)->get()->last();

        if(isset($user)){
            // 이미 존재하는 닉네임
            return response()->json(['success'=>"300"]);
        } else {

            // 없는 닉네임일 경우 insert
            GameUser::create([
                'nickname' => $request->nickname,
                'user_id' => $request->user_id
            ]);

            return response()->json(['success'=>"200"]);
        }

        
    }

}
