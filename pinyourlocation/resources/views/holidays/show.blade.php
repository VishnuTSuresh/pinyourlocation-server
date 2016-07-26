@extends('layouts.app')

@section('content')
    @include('holidays.show_fields')

    <div class="form-group">
           <a href="{!! route('holidays.index') !!}" class="btn btn-default">Back</a>
    </div>
@endsection
