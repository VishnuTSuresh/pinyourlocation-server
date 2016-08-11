@extends('layouts.app')

@section('content')
<div class="container">
    @if(Entrust::hasRole('verified'))
    <div class="row">
        <div class='col-sm-12'>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::get('mailsent'))
            <div class="alert alert-success">
                Mail has been sent successfully
            </div>
        @endif
        </div>
    </div>
    <div class="row">
        <div class='col-sm-6 col-lg-4 col-md-5'>
            <div class="panel panel-default">
                <div class="panel-heading">Where will you be working today?</div>
                <div class="panel-body">

                        <form action="location{{is_numeric($location->id)?'/'.$location->id:''}}" method="post">
                        <div class="row">
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            @if(is_numeric($location->id))
                                <input type="hidden" name="_method" value="PATCH">
                            @endif
                            <div class="btn-group btn-group-justified btn-group-lg col-sm-12" role="group">
                                <div class="btn-group" role="group">
                                    <button type='submit' name="location" value="home" class="btn btn-{{$location->location==='home'?'warning':'default'}}"><span class="glyphicon glyphicon-home"></span> Home</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type='submit' name="location" value="office" class="btn btn-{{$location->location==='office'?'success':'default'}}"><span class="glyphicon glyphicon-briefcase"></span> Office</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type='submit' name="location" value="leave" class="btn btn-{{$location->location==='leave'?'danger':'default'}}"><span class="glyphicon glyphicon-off"></span> Leave</button>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class='col-sm-12'>
                            @if($location->location)
                            <div class="input-group">
                                <input type="text" name="description" class="form-control" placeholder="Leave a comment if you like" value='{{$location->description}}'>
                                <span class="input-group-btn">
                                    <button title="Submit Comment" type='submit' name="location" value="{{$location->location}}" class="btn btn-default"><span class="glyphicon glyphicon-comment"></span></button>
                                </span>
                            </div>
                            @else
                                <input type="text" name="description" class="form-control" placeholder="Leave a comment if you like">
                            @endif
                            </div>
                        </div>
                        </form>
                </div>
            </div>
        </div>
        <div class='col-sm-6 col-md-7 col-lg-8'>
            <div class="panel panel-default">
                                    <div class="panel-heading">
                                    Do you have any future plans? Or, would you like to request a change in your history?
                                    </div>
                                    <div class="panel-body">
                                        <form action="locations" method="post">
                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                        <div class="row">
                                            <div class="col-sm-7">
                                            <div class="input-daterange input-group input-group-justified" id="datepicker">
                                                <input type="text" class="form-control" name="from" placeholder="From"/>
                                                <span class="input-group-addon">to</span>
                                                <input type="text" class="form-control" name="to" placeholder="To"/>
                                            </div>
                                            </div>
                                            <div class="col-sm-5">
                                            <div class="btn-group btn-group-justified btn-group-lg" role="group">
                                                <div class="btn-group" role="group">
                                                    <button type='submit' name="location" value="home" class="btn btn-default"><span class="glyphicon glyphicon-home"></span> Home</button>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button type='submit' name="location" value="office" class="btn btn-default"><span class="glyphicon glyphicon-briefcase"></span> Office</button>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button type='submit' name="location" value="leave" class="btn btn-default"><span class="glyphicon glyphicon-off"></span> Leave</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <input type="text" name="description" class="form-control" placeholder="Leave a comment if you like">
                                            </div>
                                        </div>
                                        </form>
                                        <script>
                                        $('.input-daterange').datepicker({

                                        });
                                        </script>
                                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class='col-sm-12'>
            <div class="panel panel-default">
                <div class="panel-heading">You are following to these people</div>
                <div class="panel-body">
                    @forelse ($followings as $user)
                        <?php
                            $location = $user->pinned_locations()->where('date',\Carbon\Carbon::today())->first();
                            if(!$location){
                                $location = "unmarked";
                            }else{
                                $location = $location->location;
                            }
                        ?>
                        <a class="ui image label {{$location}}">
                            {!! Gravatar::image($user->email) !!}
                            {{ $user->name }}
                        </a>
                    @empty

                    @endforelse
                    <a href="{{url('user')}}">+Add people</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class='col-sm-12'>
            <div class="panel panel-default">
                <div class="panel-heading">Your History</div>
                <div class="panel-body">
                        @include('components.history',['user' => Auth::user()])
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger" role="alert">
        You need to verify your email to use this app. See your inbox.
    </div>
@endif
</div>
@endsection
