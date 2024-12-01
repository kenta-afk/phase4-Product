<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // 新規登録画面の表示
    public function create() 
    { 
        return view('appointments.create');
    } // アポ情報の保存 
    public function store(Request $request) 
    { 
        // バリデーション 
        $validatedData = $request->validate([ 
            'visitor_name' => 'required|string|max:255', 
            'visitor_company' => 'required|string|max:255', 
            'host_name' => 'required|string|max:255', 
            'meeting_room' => 'required|string|max:255', 
            'appointment_date' => 'required|date', 
            'purpose' => 'required|string|max:1000', ]); 
            
            // アポ情報の保存処理（例） 
            // Appointment::create($validatedData); 
            return redirect()->route('appointments.index')->with('success', 'アポ情報が登録されました。'); 
        }

        public function index()
        {
            // すべてのアポ情報を取得（例）
            $appointments = [

                [
                    'id' => 1,
                    'visitor_name' => '山田 太郎',
                    'visitor_company' => '株式会社サンプル',
                    'host_name' => '佐藤 一郎',
                    'meeting_room' => '会議室A',
                    'appointment_date' => '2024-12-01 14:00',
                    'purpose' => '商談'
                ],
                // 他のアポ情報を追加
            ];
            return view('appointments.index', compact('appointments'));
        }

}
