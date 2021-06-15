<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPannelController extends Controller
{
    public function Index()
    {
        if (!auth()->user()->can("admin panel")) {
            abort(403);
        }
        return view("adminpanel/adminpanel");
    }
}
