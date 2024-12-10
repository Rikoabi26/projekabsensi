<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IzinsakitController extends Controller
{
    //
    public function create()
    {
        return view('sakit.create');
    }
}
