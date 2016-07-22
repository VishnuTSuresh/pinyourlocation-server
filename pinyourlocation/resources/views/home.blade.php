@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Dashboard</div>
    <div class="panel-body">
        @if(Entrust::hasRole('verified'))
            Welcome!
        @else
            You need to verify your email to use this app. See your inbox.
        @endif
    </div>
</div>
@endsection
