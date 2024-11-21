<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonalController extends Controller
{
    
    public function registrar()
    {
        $today = date('Y-m-d');

        return view('events.register', compact('today'));
    }

}
