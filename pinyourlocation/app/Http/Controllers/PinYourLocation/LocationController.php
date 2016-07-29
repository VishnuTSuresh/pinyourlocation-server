<?php

namespace App\Http\Controllers\PinYourLocation;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PinnedLocation;
use Auth;
use Carbon\Carbon;
use Mail;
use App\Role;
use App\User;
use Session;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getlocation(Auth::user());
    }
    public function user($userId)
    {
        $user=User::findOrFail($userId);
        if(Auth::user()->hasRole('manager')||$user->id===Auth::user()->id){
            return $this->getlocation($user);
        }
        else{
            abort(404);
        }
    }
    private function getlocation($user)
    {
        return $user->pinned_locations()->orderBy('date', 'asc')->get()->makeHidden(
            ['created_at','updated_at','user_id']
        )->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r('hello');
        $this->validate($request, [
            'location' => 'required'
        ]);
        $p=new PinnedLocation;
        $p->location = $request->input('location');
        $p->description = $request->input('description');
        $p->date = Carbon::today();
        Auth::user()->pinned_locations()->save($p);
        return back();
    }
    public function insert(Request $request)
    {
        // print_r('hello');
        $this->validate($request, [
            'from' => 'required|date_format:m/d/Y',
            'to' => 'required|date_format:m/d/Y|after:from',
            'location' => 'required'
        ]);
        $from=Carbon::createFromFormat('m/d/Y', $request->input('from'))->startOfDay();
        $to=Carbon::createFromFormat('m/d/Y', $request->input('to'))->startOfDay();
        $now=Carbon::today();
        if($from->lte($to)){
            if($from->lt($now)){
                Mail::send('emails.request', [
                        'from' => $from,
                        'to' => $to->min($now),
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'location' => $request->input('location'),
                        'description' => $request->input('description')
                    ], function ($m){
                    $m->from('pinyourlocation@visualiq.com', 'PinYourLocation Admin');
                    foreach (Role::where("name","admin")->first()->users as $adminuser) {
                        $m->to($adminuser->email, $adminuser->name);
                    }
                    $m->subject('Location change request');
                });
                Session::flash('mailsent', true);
            }
            for($date=$from->max(Carbon::tomorrow());$date->lte($to);$date->addDay()){
                $p=Auth::user()->pinned_locations()->firstOrNew(['date' =>$date]);
                if($request->input('location')==="office"){
                    $p->delete();
                }else{
                    $p->location = $request->input('location');
                    $p->description = $request->input('description');
                    $p->date = $date;
                    Auth::user()->pinned_locations()->save($p);
                }
            }
        }
        return back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $p = PinnedLocation::find($id);
        $p->location = $request->input('location');
        $p->description = $request->input('description');
        $p->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
