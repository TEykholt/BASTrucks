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
        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();
        $kpiData = kpiModel::get();

        return view('profile')->with('userData', $user)->with('kpiData', $kpiData)->with('personSetting', $person_settings);
    }
    function updateUserSettings(Request $request){
        $user = User::where("name", $request->name)
            ->get();
        $kpiData = kpiModel::get();
        foreach($kpiData as $kpi){
            $kpiRequest = "kpi".$kpi['id'];
            if($request->$kpiRequest == "yes"){
                $personSetting = personSettingsModel::where("person_id", $user[0]['id'])->where("preferd_kpi", $kpi['id']);
                $personSetting->delete();

                $person_settings = new personSettingsModel;
                $person_settings->person_id = $user[0]['id'];
                $person_settings->preferd_kpi = $kpi['id'];
                $person_settings->save();
            }
            else if($request->$kpiRequest == "no"){
                $personSetting = personSettingsModel::where("person_id", $user[0]['id'])->where("preferd_kpi", $kpi['id']);
                $personSetting->delete();
            }
        }
        $kpiDataReturn = kpiModel::get();
        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();

        return view('profile')->with('userData', $user)->with('kpiData', $kpiDataReturn)->with('personSetting', $person_settings);
    }

    function getUser(Request $request)
    {
        $users = User::where("id", $request->id)->get();

        if ($users != null) {
            return $users[0];
        }

        return $users;
    }

    function getUserByUserName(Request $request)
    {
        $users = User::where("username", $request->username)->get();

        if ($users != null) {
            return $users[0];
        }

        return $users;
    }

    function getAutoCompleteUsers(Request $request){
        if($request->has('username')){
            return User::select("username")->where('username','like','%'.$request->input('username').'%')->get();
        }
    }
}
