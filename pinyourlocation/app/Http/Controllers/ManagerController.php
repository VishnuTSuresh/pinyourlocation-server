<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Carbon\Carbon;

class ManagerController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:manager');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $from=Carbon::now()->subDays(53); 
        $to=Carbon::now();
        $users = User::with(['pinned_locations' => function ($query) use($from,$to) {
            $query->whereBetween('date', [$from,$to])->orderBy('date', 'asc');
        }])->orderBy('name','asc')->get();
        $arr=array();
        foreach ($users as $user) {
            $locationarr=array();
            for($i=(clone $from);$i->lte($to);$i->addDay()){
                $locationstr="unmarked";
                if($i->isWeekend()){
                    $locationstr="weekend";
                }
                foreach ($user->pinned_locations as $location) {
                    if(Carbon::createFromFormat("Y-m-d",$location->date)->eq($i)){
                        $locationstr=$location->location;
                    }
                }
                array_push($locationarr,array(
                    "location"=>$locationstr,
                    "date"=>$i->format('l jS \\of F Y')
                ));
            }
            array_push($arr,array(
                "name"=>$user->name,
                "id"=>$user->id,
                "locations"=>$locationarr
            ));
        }
        return view('manager',
            array(
                "users"=>$arr
            )
        );
    }
}
