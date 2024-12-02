<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlackController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/send-message', [SlackController::class, 'sendMessage']);// Slackにメッセージを送信するby魚住
Route::get('/workspace-members', [SlackController::class,'getWorkspaceMembers']);// Slackのワークスペースメンバーを取得するby魚住
Route::get('/messagetest', function () {
    return view('messagetest');
});// メッセージテスト用のビューを返すby魚住