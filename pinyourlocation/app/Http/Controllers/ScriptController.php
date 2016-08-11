<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PinnedLocation;
use App\User;
use App\Models\holiday;
use Auth;
use Carbon\Carbon;

class ScriptController extends Controller
{
    public function code($token)
    {
        $user=User::where('token', $token)->firstOrFail();
        $has_no_entry_today=$user->pinned_locations()->where('date',Carbon::today())->count()===0;
        if(
            $has_no_entry_today&&
            (!$user->lastrun->isToday())&&
            Carbon::today()->isWeekday()&&
            (holiday::where('date',Carbon::today())->count()===0)&&
            Carbon::now()->between(Carbon::createFromTime(9,0,0),Carbon::createFromTime(23, 59, 59))
        ){
            return view('script',
                array(
                    "token"=>$token
                )
            );
        }else{
            return "";
        }
    }
}
