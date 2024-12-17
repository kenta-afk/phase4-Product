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
        $visitors = Appointment::where('status', true)
        ->with(['users', 'room'])->get();
        return view('visitors.index', compact('visitors'));
    }

    public function destroy($id)
    {
        $visitors = Appointment::findOrFail($id);
        $visitors->delete();
        return redirect()->route('visitors.index')->with('success', '来客者情報は削除されました');
    }

    public function search(Request $request)
    {
        $query = Appointment::where('status', true);
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        if ($request->filled('visitor_name')) {
            $query->where('visitor_name', 'like', '%' . $request->input('visitor_name') . '%');
        }

        $visitors = $query->get();

        return view('visitors.index', compact('visitors'));
    }
}
