<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    public function index() 
    { 
        return view('receptions.index');
    } 
}
