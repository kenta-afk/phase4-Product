<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Room;
use App\Models\User;

class VisitorController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('status', true)
        ->with(['users', 'room'])->get();
        return view('visitors.index', compact('appointments'));
    }
}
