<?php

namespace App\Http\Controllers;

use App\GameUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameUserController extends Controller
{

    public function createUser(Request $request){
            info($request->nickname);
            info($request->user_id);


            $user = GameUser::where('nickname',$request->nickname)->get()->last();

            if(isset($user)){
                // 이미 존재하는 닉네임
                return response()->json(['success'=>"300"]);
            } else {

                GameUser::create([
                    'nickname' => $request->nickname,
                    'user_id' => $request->user_id
                ]);

                return response()->json(['success'=>"200"]);
            }




    }

}
