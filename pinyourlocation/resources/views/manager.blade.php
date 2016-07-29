@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
    <table class="table">
        <thead>
            <tr>
                <th>User History</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>
                <a href="{{url('user/'.$user['id'])}}">{{ $user["name"] }}</a>
                </td>
                <td class="text-center">
                @foreach ($user["locations"] as $id => $location)
                <div class='location {{$location["location"]}}' title="{{$location['date']}}" data-toggle="tooltip"></div>
                @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <style>
        .location{
            display:inline-block;
            width:11px;
            height:11px;
            margin:2px;
        }
    </style>
    <script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    </script>
    </div>
</div>
@endsection
