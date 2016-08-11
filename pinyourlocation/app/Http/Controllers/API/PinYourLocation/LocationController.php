<?php

namespace App\Http\Controllers\API\PinYourLocation;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PinnedLocation;
use Carbon\Carbon;
use App\User;

class LocationController extends Controller
{
    public function store_office_via_api(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user=User::where('token', $request->input('token'))->firstOrFail();
        if($user->pinned_locations()->where('date',Carbon::today())->count()===0){
            $p=new PinnedLocation;
            $p->location = "office";
            $p->description = "";
            $p->date = Carbon::today();
            $user->pinned_locations()->save($p);
            return "TRUE";
        }
        return "FALSE";
    }
}
