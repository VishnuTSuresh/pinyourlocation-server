@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Profile</div>
    <div class="panel-body">
        <a class="ui raised card" href="https://en.gravatar.com/">
            <div class="image">
                {!! Gravatar::image(Auth::user()->email,"profile",['width' => 290, 'height' => 290]) !!}
            </div>
            <div class="content">
                <div class="header">{{Auth::user()->name}}</div>
            </div>
        </a>
    </div>
</div>
@endsection
