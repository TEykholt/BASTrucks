<?php

namespace App\Http\Controllers;

use App\kpiModel;
use App\personSettingsModel;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function getProfilePage(Request $request){
        $user = User::where("name", $request->name)
            ->get();

        $kpiData = kpiModel::get();
        return view('profile')->with('userData', $user)->with('kpiData', $kpiData);
    }
    function updateUserSettings(Request $request){
        $user = User::where("name", $request->name)
            ->get();
        foreach($request->kpi as $kpi){
            $kpiData = kpiModel::where("kpi", $kpi)->get();
            $personSetting = personSettingsModel::where("person_id", $user[0]['id'])->where("preferd_kpi", $kpiData[0]['id']);
            $personSetting->delete();

            $person_settings = new personSettingsModel;
            $person_settings->person_id = $user[0]['id'];
            $person_settings->preferd_kpi = $kpiData[0]['id'];
            $person_settings->save();
        }
        $kpiData = kpiModel::get();
        return view('profile')->with('userData', $user)->with('kpiData', $kpiData);
    }
}
