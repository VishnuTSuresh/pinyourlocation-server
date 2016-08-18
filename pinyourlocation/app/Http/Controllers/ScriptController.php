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
        $has_no_location_entry_today=$user->pinned_locations()->where('date',Carbon::today())->count()===0;
        $has_not_popup_today=(!$user->lastrun->isToday());
        $is_allowed_to_disturb_now=Carbon::now()->between(Carbon::createFromTime(13,0,0),Carbon::createFromTime(23, 59, 59));
        $should_show_popup=$is_allowed_to_disturb_now&&$has_not_popup_today;
        if(
            $has_no_location_entry_today&&
            Carbon::today()->isWeekday()&&
            (holiday::where('date',Carbon::today())->count()===0)
        ){
            return view('script',
                array(
                    "token"=>$token,
                    "should_show_popup"=>$should_show_popup
                )
            );
        }else{
            return "";
        }
    }
}
