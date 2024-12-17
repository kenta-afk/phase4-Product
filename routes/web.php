<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Middleware\CheckUserRole;
use App\Enums\Role;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/management', function () {
    return view('management');
})->middleware(['auth', 'verified', 'role:ADMIN,role:USER'])->name('management');

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
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy'); #アポ情報削除機能by米田
    Route::get('/appointments.edit/{id}', [AppointmentController::class, 'edit'])->name('appointments.edit'); #アポ情報編集画面に移動by米田
    Route::post('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update'); #アポ情報編集完了by米田
    Route::get('/appointments/{id}', [AppointmentController::class, 'visited'])->name('appointments.visited'); #アポ情報を来客済データにするby米田


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

    #Visitor
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index'); #一覧表示
    Route::delete('/visitors.destroy/{id}', [VisitorController::class, 'destroy'])->name('visitors.destroy'); #削除
    Route::get('/visitors/search', [VisitorController::class, 'search'])->name('visitors.search'); #検索
    
    // Microsoft Graph API
    //認証関係
    Route::get('/auth/redirect', [MicrosoftAuthController::class, 'redirectToProvider']);
    Route::get('/auth/callback', [MicrosoftAuthController::class, 'handleProviderCallback']);
    
    //カレンダー関係
    // カレンダー一覧表示ルート
    Route::get('/calendars', [MicrosoftAuthController::class, 'listCalendars'])->name('calendar.list');
    Route::get('/calendar', function () {
        return redirect('/calendars');
    });

    // 特定のカレンダーのイベント一覧表示ルート
    Route::get('/calendar/{calendar_id}/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    // イベント追加フォーム表示ルート
    Route::get('/calendar/add-event-form', [CalendarController::class, 'showAddEventForm'])->name('calendar.addEventForm');

    // イベント追加処理ルート
    Route::post('/calendar/add-event', [CalendarController::class, 'addEvent'])->name('calendar.addEvent');

    // 共有カレンダー作成ルート
    Route::get('/calendar/create-shared', [CalendarController::class, 'createSharedCalendar'])->name('calendar.createShared');

    // カレンダー共有処理ルート
    Route::post('/calendar/share', [CalendarController::class, 'shareCalendar'])->name('calendar.share');


});

require __DIR__.'/auth.php';
