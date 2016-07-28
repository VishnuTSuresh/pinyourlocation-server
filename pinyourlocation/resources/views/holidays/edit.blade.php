@extends('layouts.app')

@section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit holiday</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            {!! Form::model($holiday, ['route' => ['holidays.update', $holiday->id], 'method' => 'patch']) !!}

            @include('holidays.fields')

            {!! Form::close() !!}
        </div>
@endsection
