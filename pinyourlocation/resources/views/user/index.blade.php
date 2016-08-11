@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Users</div>
    <div class="panel-body">
      <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Location Today</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                <?php
                $location=$user->pinned_locations()->where('date',\Carbon\Carbon::today())->first()
                ?>
                <tr>
                    <td>{!! Gravatar::image($user->email,"profilepic",["width"=>30,"height"=>"30"]) !!}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$location?$location->location:"unmarked"}}</td>
                    <td>
                      <form action="{{ url('user/follow') }}" method="POST">
                        {{ csrf_field() }}
                        @if(Auth::user()->following->contains($user->id))
                            <button class="btn btn-success" name="user" value="{{$user->id}}" type="submit">Unsubscribe</button>
                        @else
                            <button class="btn btn-default" name="user" value="{{$user->id}}" type="submit">Subscribe</button>
                        @endif
                      </form>
                      <form action="{{ url('user/push') }}" method="POST">
                        {{ csrf_field() }}
                        @if(Auth::user()->followers->contains($user->id))
                            <button class="btn btn-success" name="user" value="{{$user->id}}" type="submit">Unpush</button>
                        @else
                            <button class="btn btn-default" name="user" value="{{$user->id}}" type="submit">Push</button>
                        @endif
                      </form>
                      <style>
                        form {
                            display: inline-block;
                        }
                      </style>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
          </div>
    </div>
</div>
@endsection
