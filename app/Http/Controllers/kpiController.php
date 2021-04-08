<?php

namespace App\Http\Controllers;

use App\kpiModel;
use Illuminate\Http\Request;

class kpiController extends Controller
{
    function GetAllKPIs(){
       $data = kpiModel::get();

       return view('kpi')->with('results' , $data);
    }
}
