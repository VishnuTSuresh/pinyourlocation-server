<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

class ScriptController extends Controller
{
    public function scriptfinish(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user=User::where('token', $request->input('token'))->firstOrFail();
        $user->lastrun=Carbon::today();
        $user->save();
        return "TRUE";
    }
}
