<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameUser extends Model
{

    use SoftDeletes;

    // 어떠한 테이블을 사용할지 지정 가능 (table 이름이 board_table)
    protected $table = 'game_user';


    // 속성들을 보호하기
    // fillable & guarded 둘 중 하나만 써야함
    protected $guarded = [

    ];

}
