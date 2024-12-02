<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReceptionController;

Route::get('/', function () {return view('management');})->name('management');

Route::get('/appointments.create', [AppointmentController::class, 'create'])->name('appointments.create'); #アポ情報新規登録画面に移動by米田
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store'); #アポ情報を保存by米田
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index'); #アポ情報一覧表示by米田

# 受付
Route::get('/reception', [ReceptionController::class, 'index'])->name('reception.index'); 
