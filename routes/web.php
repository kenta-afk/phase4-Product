<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MicrosoftAuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/management', function () {
    return view('management');
})->middleware(['auth', 'verified'])->name('management');

Route::get('/management', function () {
    return view('management');
})->middleware(['auth', 'verified'])->name('management');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/appointments.create', [AppointmentController::class, 'create'])->name('appointments.create'); #アポ情報新規登録画面に移動by米田
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store'); #アポ情報を保存by米田
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index'); #アポ情報一覧表示by米田

    # 受付
    Route::get('/reception', [ReceptionController::class, 'index'])->name('reception.index'); 

    # Slack
    Route::post('/send-message', [SlackController::class, 'sendMessage'])->name('hosts.send');// Slackにメッセージを送信するby魚住
    Route::get('/workspace-members', [SlackController::class,'getWorkspaceMembers'])->name('hosts.get');// Slackのワークスペースメンバーを取得するby魚住
    Route::get('/messagetest', [SlackController::class, 'showMessageForm']);// メッセージテスト用のビューを返すby魚住

    #Visitor一覧表示
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index'); 

// Microsoft Graph API
Route::get('/auth/redirect', [MicrosoftAuthController::class, 'redirectToProvider']);
Route::get('/auth/callback', [MicrosoftAuthController::class, 'handleProviderCallback']);
Route::get('/calendar', [MicrosoftAuthController::class, 'getCalendar'])->middleware('auth');
});

require __DIR__.'/auth.php';
