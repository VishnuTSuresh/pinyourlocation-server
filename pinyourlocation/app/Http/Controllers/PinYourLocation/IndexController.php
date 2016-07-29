<?php

namespace App\Http\Controllers\PinYourLocation;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use stdClass;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $location=Auth::user()->pinned_locations()->where('date',Carbon::today())->first();
        if(!$location){
            $location=new stdClass;
            $location->location=null;
            $location->id=null;
        }
        return view('pinyourlocation',
            array(
                "location"=>$location,
                "user"=>Auth::user(),
                "followings"=>Auth::user()->following
            )
        );
    }
    public function install()
    {
        $arr=array("token"=>Auth::user()->token);
        return response()->view('setup', $arr)->header('Content-Type', "application/vnd.vbscript; charset=utf-8")->header("Content-disposition","attachment; filename=\"setup.vbs\"");
    }
}
