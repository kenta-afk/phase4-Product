<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Room;
use App\Models\Host;
use App\Models\Visitor;

class AppointmentController extends Controller
{
    // 新規登録画面の表示
    public function create() 
    { 
        return view('appointments.create');
    } 

    // アポ情報の保存 
    public function store(Request $request) 
    { 
        // バリデーション 
        $validatedData = $request->validate([ 
            'visitor_name' => 'required|string|max:255', 
            'visitor_company' => 'required|string|max:255', 
            'host_name' => 'required|string|max:255', 
            'room_name' => 'required|string|max:255', 
            'appointment_date' => 'required|date', 
            'purpose' => 'required|string|max:1000', 
        ]); 

        // 入力されたデータを変数に格納（分別） 
        $visitorName = $request->input('visitor_name'); 
        $visitorCompany = $request->input('visitor_company'); 
        $hostName = $request->input('host_name'); 
        $roomName = $request->input('room_name'); 
        $appointmentDate = $request->input('appointment_date'); 
        $purpose = $request->input('purpose');

        //担当者名を社員IDに変える
        $host = Host::where('host_name', $hostName)->first();
        if ($host) {
            $hostId = $host->id;
        } else {
            //見つかんなかったときの処理をここにいれる
        }

        //会議室名を会議室IDに変える
        $room = Room::where('room_name', $roomName)->first();
        if ($room) {
            $roomId = $room->id;
        } else {
            //見つかんなかったときの処理をここにいれる
        }

        //来客者情報の保存
        $visitor = Visitor::create([
            'visitor_name' => $visitorName,
            'visitor_company' => $visitorCompany
        ]);

        // 来客者IDの取得
        $visitorId = $visitor->id;
            
        // 用意した変数をまとめてアポ情報として保存する 
        Appointment::create([
            'visitor_id' => $visitorId,
            'host_id' => $hostId,
            'room_id' => $roomId,
            'appointment_date' => $appointmentDate,
            'purpose' => $purpose,
        ]); 
        
        // 管理画面にリダイレクトし、アラートを表示
        return redirect()->route('management')->with('success', 'アポ情報が登録されました。'); 
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
