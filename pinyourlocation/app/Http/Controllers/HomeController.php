<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
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
        return view('home');
    }
    public function install()
    {
        $token=array("token"=>"123123123");
        return response()->view('setup', $token)->header('Content-Type', "application/vnd.vbscript; charset=utf-8")->header("Content-disposition","attachment; filename=\"setup.vbs\"");
    }
}
