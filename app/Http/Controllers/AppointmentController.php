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
        //dd($request->all());
        
        // バリデーション 
        $validatedData = $request->validate([ 
            'visitor_name' => 'required|string|max:255', 
            'visitor_company' => 'required|string|max:255', 
            'user_name' => 'required|exists:users,name', 
            'room_id' => 'required|exists:rooms,id', 
            'date' => 'required|date', 
            'comment' => 'required|string|max:1000', 
        ]); 

        // Userのidを取得
        $user = User::where('name', $request->input('user_name'))->first();

        // 用意した変数をまとめてアポ情報として保存する 
        $appointment = Appointment::create([
            'status' => false,
            'room_id' => $request->input('room_id'),
            'visitor_name' => $request->input('visitor_name'),
            'visitor_company' => $request->input('visitor_company'),
            'date' => $request->input('date'),
            'comment' => $request->input('comment'),
        ]); 

        // 中間テーブルに保存
        $appointment->users()->attach($user->id);
        
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
