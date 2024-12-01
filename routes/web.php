<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlackController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/send-message/{slackId}', [SlackController::class, 'sendMessage']);
Route::get('/workspace-members', [SlackController::class,'getWorkspaceMembers']);