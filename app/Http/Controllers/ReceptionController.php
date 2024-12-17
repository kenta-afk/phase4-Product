<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Http;

class ReceptionController extends Controller
{
    public function index() 
    { 
        return view('receptions.index');
    } 

    public function create()
    {
        return view('receptions.create');
    }

    public function search(Request $request)
    {
        $visitorName = $request->input('visitor_name');
        $appointments = Appointment::where('visitor_name', 'like', '%' . $visitorName . '%')->with('users')->get();

        return response()->json(['appointments' => $appointments]);
    }

    public function notify(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::with('users')->findOrFail($appointmentId);

        foreach ($appointment->users as $user) {
            app(SlackController::class)->sendMessage(new Request([
                'slackId' => $user->slack_id,
                'message' => "来客者: {$appointment->visitor_name}\n会社: {$appointment->visitor_company}\n訪問日時: {$appointment->date}\n要件: {$appointment->comment}"
            ]));
        }

        return response()->json(['status' => '通知が送信されました。']);
    }
}
