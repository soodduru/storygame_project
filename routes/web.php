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

// 방목록으로 이동
Route::get('/gameRoomList', "GameController@roomList");

// 실시간 방목록 확인
Route::post('/roomListCheck', "GameController@roomListCheck");

// [방만들기]버튼 클릭 시 방생성
Route::get('/makeRoom', "GameController@createRoom");

// 방 조회
Route::post('/roomReadyCheck', "GameController@selectRoom");

// 방 입장
Route::get('/room/{id}', "GameController@joinRoom");

// 방들어가기 진입 시 1)인원수 확인 2) user_id를 ready_room table에 넣어주기
Route::post('/participate', "GameController@participateRoom");

// 게임시작 버튼 클릭 시 game_id 랜덤생성하여 업데이트 (게임 구별용)
Route::post('/gameStart',"GameController@gameStart");

// 성공 후 게임 화면으로 이동
Route::get('/gameStart/{game_id}',"GameController@getGameStart");

// story_status에 따라 유저의 화면을 변경
Route::post('/storyStart',"StoryController@storyStart");

// 게임 진행에 따라 story_status update
Route::post('/storyUpdate',"StoryStatusUpdateController@statusUpdate");

// 게임이 끝났을때 최종 스토리 전달을 확인 할 수 있는 페이지로 이동
Route::get('/storyFinish/{game_id}',"StoryController@storyFinish");

Route::get('/test', function () {
    return view('test');
});


