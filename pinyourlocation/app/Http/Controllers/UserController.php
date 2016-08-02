<?php

namespace App\Http\Controllers;

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

class UserController extends Controller
{
    public function index()
    {
        return view('user.index',
            array(
                "users"=>User::orderby("name","asc")->get()
            )
        );
    }
    public function location($userId)
    {
        $user=User::findOrFail($userId);
        if(Auth::user()->hasRole('manager')||$user->id===Auth::user()->id){
            return $this->getlocation($user);
        }
        else{
            abort(404);
        }
    }
    public function follow(Request $request){
        $id=$request->user;
        if(Auth::user()->following->contains($id)){
            Auth::user()->following()->detach($id);
        }else{
            Auth::user()->following()->attach($id);
        }
        return back();
    }
    public function push(Request $request){
        $id=$request->user;
        if(Auth::user()->followers->contains($id)){
            Auth::user()->followers()->detach($id);
        }else{
            Auth::user()->followers()->attach($id);
        }
        return back();
    }
    private function getlocation($user)
    {
        return $user->pinned_locations()->orderBy('date', 'asc')->get()->makeHidden(
            ['created_at','updated_at','user_id']
        )->toJson();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->hasRole('manager')){
            return view('user',
                array(
                    "user"=>User::findOrFail($id)
                )
            );
        }
        else{
            abort(404);
        }
    }
}
