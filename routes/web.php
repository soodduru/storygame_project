<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 누구나 접속 가능
Route::get('/', function () {
    return view('game');
});

// 게임 설명 보기
Route::get('/guide', function () {
    return view('guide');
});

// 게임시작 전 user_id, nickname을 game_user 테이블에 넣어주기
Route::post('/gameUserCreate', "GameUserController@createUser");

// 위에 로직 성공 시 방목록으로 이동
Route::get('/gameRoomList', "GameController@roomList");

Route::post('/roomListTest', "GameController@roomListTest");
// 방생성
Route::get('/makeRoom', "GameController@createRoom");

Route::post('/roomReadyCheck', "GameController@selectRoom");

// 방에 입장
Route::get('/room/{id}', "GameController@joinRoom");

// 방들어가기 진입 시 1)인원수 확인 2) user_id를 ready_room table에 넣어주기
Route::post('/participate', "GameController@participateRoom");

Route::post('/gameStart',"GameController@gameStart");

//
Route::get('/gameStart/{game_id}',"GameController@getGameStart");


Route::post('/storyStart',"StoryController@storyStart");

Route::post('/storyUpdate',"StoryStatusUpdateController@statusUpdate");

Route::get('/test', function () {
    return view('test');
});

Route::get('/storyFinish/{game_id}',"StoryController@storyFinish");

Route::get('/css', function () {
    return view('testgameCSS');
});

// 방에 머무르기 선택
Route::get('/roomStay',"GameController@roomStay");
