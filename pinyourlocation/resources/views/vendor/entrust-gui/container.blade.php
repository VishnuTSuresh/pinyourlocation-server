@extends(Config::get('entrust-gui.layout'))
@section('content')
@include('entrust-gui::partials.navigation')
<div class="panel panel-default">
    <div class="panel-heading"><h1 class="panel-title">@yield('heading')</h1></div>
        <div class="panel-body">
        @include('entrust-gui::partials.notifications')
        @yield('entrust-content')
        </div>
    </div>
</div>
@endsection