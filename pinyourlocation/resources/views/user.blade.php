@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{$user->name}}'s history</div>
    <div class="panel-body">
    @include('components.history',['user' => Auth::user()])
    </div>
</div>
@endsection
