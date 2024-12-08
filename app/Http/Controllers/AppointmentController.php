<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Room;
use App\Models\User;


class AppointmentController extends Controller
{
    // 新規登録画面の表示
    public function create() 
    { 
        $users = User::all();
        $rooms = Room::all();
        return view('appointments.create', compact('users', 'rooms'));
    } 

    // アポ情報の保存 
    public function store(Request $request) 
    { 
        // バリデーション 
        $validatedData = $request->validate([ 
            'visitor_name' => 'required|string|max:255', 
            'visitor_company' => 'required|string|max:255', 
            'user_names' => 'required|array', 
            'user_names.*' => 'exists:users,name', 
            'room_id' => 'required|exists:rooms,id', 
            'date' => 'required|date', 
            'comment' => 'required|string|max:1000', 
        ]); 
        
        // アポ情報の保存
        $appointment = Appointment::create([
            'visitor_name' => $request->input('visitor_name'),
            'visitor_company' => $request->input('visitor_company'),
            'room_id' => $request->input('room_id'),
            'date' => $request->input('date'),
            'comment' => $request->input('comment'),
            'status' => false
        ]);

        // 対応者のIDを取得し、中間テーブルに保存
        $userIds = User::whereIn('name', $request->input('user_names'))->pluck('id');
        $appointment->users()->attach($userIds);

        // 管理画面にリダイレクトし、アラートを表示
        return redirect()->route('management')->with('success', 'アポ情報が登録されました。'); 
    }

    public function index()
    {

        $appointments = Appointment::where('status', false)
        ->with(['users', 'room'])->get();

        return view('appointments.index', compact('appointments'));
    }

}
