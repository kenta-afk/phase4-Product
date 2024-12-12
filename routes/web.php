<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\VisitorController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/management', function () {
    return view('management');
})->middleware(['auth', 'verified'])->name('management');

Route::middleware('auth')->group(function () {
    
    # Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-role', [ProfileController::class, 'updateRole'])->name('profile.updateRole'); #事務の人が押すボタン

    # Appointment
    Route::get('/appointments.create', [AppointmentController::class, 'create'])->name('appointments.create'); #アポ情報新規登録画面に移動by米田
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store'); #アポ情報を保存by米田
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index'); #アポ情報一覧表示by米田

    # 受付
    Route::get('/reception', [ReceptionController::class, 'index'])->name('receptions.index'); 
    Route::get('/reception/create', [ReceptionController::class, 'create'])->name('receptions.create');//アポイントメント検索
    Route::post('/reception/search', [ReceptionController::class, 'search'])->name('reception.search');
    Route::post('/reception/notify', [ReceptionController::class, 'notify'])->name('reception.notify');
    
    # Slack
    Route::post('/send-message', [SlackController::class, 'sendMessage'])->name('hosts.send');// Slackにメッセージを送信するby魚住
    Route::get('/workspace-members', [SlackController::class,'getWorkspaceMembers'])->name('hosts.get');// Slackのワークスペースメンバーを取得するby魚住
    Route::get('/messagetest', [SlackController::class, 'showMessageForm'])->name('host.message');// メッセージテスト用のビューを返すby魚住
    Route::post('/send-message-to-role-users', [SlackController::class, 'sendMessagesToRoleUsers'])->name('send.message.to.role.users');

    #Visitor一覧表示
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index'); 
});

require __DIR__.'/auth.php';
