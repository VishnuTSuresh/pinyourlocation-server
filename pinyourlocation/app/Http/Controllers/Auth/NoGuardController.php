<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\VerificationCode;
use App\User;
use App\Role;

class NoGuardController extends Controller
{
    public function verify($id,$token)
    {
        $user=User::find($id);
        if($user&&$user->verification_codes->where("code",$token)->first()){
            $verified_role=Role::where("name","verified")->first();
            $admin=Role::where("name","admin")->first();
            $user->attachRole($verified_role);
            if($admin->users()->count()===0){
                $user->attachRole($admin);
            }
            $user->verification_codes()->delete();
            return redirect()->action('HomeController@index');
        }
        return "Invalid verification code";
    }
}
