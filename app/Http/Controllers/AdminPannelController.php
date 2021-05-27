<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPannelController extends Controller
{
    public function Index()
    {
        return view("adminpanel/adminpanel");
    }
}
