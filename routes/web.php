<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlackController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/send-message', [SlackController::class, 'sendMessage']);
Route::get('/workspace-members', [SlackController::class,'getWorkspaceMembers']);
Route::get('/messagetest', function () {
    return view('messagetest');
});