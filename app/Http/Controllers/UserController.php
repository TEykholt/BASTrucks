<?php

namespace App\Http\Controllers;

use App\kpiModel;
use App\personSettingsModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function getProfilePage(Request $request){
        $user = User::where("id", auth()->user()->id)
            ->get();

        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();

        $kpiData = kpiModel::get();
        return view('profile')->with('userData', $user)->with('kpiData', $kpiData)->with('personSetting', $person_settings);
    }
    function updateUserSettings(Request $request){
        $user = User::where("id", auth()->user()->id)
            ->get();

        if (count($user) <= 0){
            abort(403);
        }

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

        return redirect('/');
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

        if ($users != null || count($users) > 1) {
            return $users[0];
        }

        return $users;
    }

    function getAutoCompleteUsers(Request $request){
        if($request->has('username')){
            return User::select("username")
            ->join("model_has_roles","model_has_roles.model_id","=","id")
            ->join("role_has_permissions","role_has_permissions.role_id","=","model_has_roles.role_id")
            ->join("permissions","permissions.id","=","role_has_permissions.permission_id")
            ->where('permissions.name', '=', 'can be assigned')
            ->where('username','like','%'.$request->input('username').'%')->get();
        }
    }

    function updateUser(Request  $request){
        $FullName = $request->firstname." ".$request->lastname;
        $password = Hash::make($request->password);
        User::where("id", auth()->user()->id)
            ->update(['username' => $FullName, "email" => $request->email, 'password' => $password, 'tell' => $request->tell]);


        $user = User::where("id", auth()->user()->id)
            ->get();

        $person_settings = personSettingsModel::where("person_id", auth()->user()->id)
            ->get();

        $kpiData = kpiModel::get();
        return view('profile')->with('userData', $user)->with('kpiData', $kpiData)->with('personSetting', $person_settings);
    }

    //Admin functions
    function getAllUsers(){
        $users = User::all();
        return view("users/users")->with('users', $users);
    }

    function create(){
        return view("users/usersCreate");
    }

    function edit($id){
        $user = User::findOrFail($id);

        $roles = Role::all();
        //$userroles = $user->getRoleNames();
        $userroles = $user->getRoleNames();

        //dd($userroles, $user);

        return view("users/usersEdit")->with('user', $user)->with('id', $id)->with('roles', $roles)->with('userroles', $userroles);
    }

    function update($id, Request $request){
        $password = Hash::make($request->password);
        User::where("id", $id)
            ->update(['username' => $request->username, "email" => $request->email,'job_title' => $request->jobtitle , 'password' => $password, 'tell' => $request->tell]);
        return redirect()->back();
    }

    function updatePermisions(Request $request){
        $user = User::findOrFail($request->id);
        if ($request->checked) {
            $user()->assignRole($request->name);
        }else{
            $user()->removeRole($request->name);
        }
    }

    function add(Request $request){
        $password = Hash::make($request->password);

        $user = new User;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->job_title = $request->jobtitle;
        $user->tell = $request->tell;
        $user->password = $password;
        $user->save();

        return Redirect::to('/admin/users');
    }

    function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        return Redirect::to('/admin/users');
    }
}
