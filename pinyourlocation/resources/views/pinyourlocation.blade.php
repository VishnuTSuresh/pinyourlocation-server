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
            <div class="panel-heading">Your History</div>
            <div class="panel-body">
                <div id="myhistory"></div>
                <style>
                    .q1 {/*office*/
                        background: #5cb85c;
                        fill: #5cb85c;
                    }
                    .q2 {/*home*/
                        background: #f0ad4e;
                        fill: #f0ad4e;
                    }
                    .q3 {/*leave*/
                        background:  #ac2925;
                        fill:  #ac2925;
                    }
                    .q4 {/* not marked*/
                        background: #d9534f;
                        fill: #d9534f;
                    }
                    .q5 {
                        background: #ac2925;
                        fill: #ac2925;
                    }
                    .q6 {
                        background: red;
                        fill: red;
                    }
                    .q7 {
                        background: yellow;
                        fill: yellow;
                    }
                    .q8 {
                        background: #ededed;
                        fill: #ededed;
                    }
                    .history-month{
                        display: inline-block;
                        vertical-align: top;
                    }
                    #stat dt.office:before {
                background: green;
            }

            #stat dt.home:before {
                background: yellow;
            }

            #stat dt.leave:before {
                background: red;
            }

            #stat dt.holiday:before {
                background: darkviolet;
            }

            #stat dt.x:before {
                background: #C55;
            }

            dt, dd {
                display: inline;
            }

            dd {
                margin-right: 20px;
                font-size: x-large;
            }
            rect.highlight {
                stroke: red;
            }
            #cal-heatmap {
                margin-top: 10px;
            }
                </style>
                <script>
                $(document).ready(function () {
                    var values = { "office": 1, "home": 2, "leave": 3, "x": 4, "holiday": 5, "Planned leave": 6, "Planned home": 7, "Planned office": 8, "weekend": 9 };
                    $.getJSON("location").done(function (data) {
                        function tm(date) {
                            return moment(date, "YYYY-MM-DD");
                        }
                        var f = tm(data[0].date);
                        var minyear = f,
                            maxyear = f;

                        _.each(data, function (location) {
                            minyear = moment.min(minyear, tm(location.date));
                            maxyear = moment.max(maxyear, tm(location.date));
                        });
                        minyear = minyear.year();
                        maxyear = maxyear.year();
                        var ordered = {};
                        var description={};
                        var leave={

                        },home={

                        },x={

                        };
                        _.each(_.groupBy(data, function (location) {
                            return moment(location.date, "YYYY-MM-DD").year();
                        }), function (data, year) {
                            leave[year]=0;
                            home[year]=0;
                            x[year]=0;
                            _.each(_.groupBy(data, function (location) {
                                return moment(location.date, "YYYY-MM-DD").month();
                            }), function (data, month) {
                                ordered[year] = ordered[year] || {};
                                ordered[year][month]={};
                                for (var d in data) {
                                    if(data[d].location==="leave"){
                                        leave[year]++;
                                    }
                                    if(data[d].location==="home"){
                                        home[year]++;
                                    }
                                    ordered[year][month][moment(data[d].date, "YYYY-MM-DD").unix()] = values[data[d].location];
                                    description[moment(data[d].date, "YYYY-MM-DD").unix()]=data[d].description;
                                }
                                //ordered[year][month] = data;
                            });
                        });
                        for (var year = maxyear; year >= minyear; year--) {
                            
                            var yeardiv = $("<div class='col-md-12'><h4>" + year + "</h4></div>");
                            $("#myhistory").append(yeardiv);
                            for (var month = 0; month < 12; month++) {
                                var a=moment(new Date(year, month, 1));
                                var b=a.clone().add(1, 'months');
                                ordered[year] = ordered[year] || {};
                                ordered[year][month]=ordered[year][month]||{};
                                for (var m = moment(a); m.isBefore(b); m.add(1, 'days')) {

                                    if(m.day()===0||m.day()===6){
                                        ordered[year][month][m.unix()]= values["weekend"];
                                    }
                                    if(m.isSameOrBefore(moment())){
                                        if(ordered[year][month][m.unix()]===undefined){
                                            ordered[year][month][m.unix()]=values["x"]
                                            x[year]++;
                                        }
                                    }

                                }
                                var monthdiv = $("<div class='history-month'></div>");
                                yeardiv.append(monthdiv);
                                var cal = new CalHeatMap();
                                var startdate, enddate, ONEDAY = 1000*60*60*24;
                                cal.init({
                                    itemSelector: monthdiv.get(0),
                                    data: ordered[year][month],
                                    range: 1,
                                    domain: "month",
                                    subDomain: "x_day",
                                    weekStartOnMonday: false,
                                    cellSize: 10,
                                    domainMargin: 3,
                                    legend: [1, 2, 3, 4, 5, 6, 7],
                                    displayLegend: false,
                                    tooltip: true,
                                    start: new Date(year, month, 1),
                                    label: { position: "top" },
                                    legendTitleFormat: {
                                        lower: "Office",
                                        inner: "Home",
                                        upper: "Leave"
                                    },
                                    subDomainTitleFormat: {
                                        empty: "{date}",
                                        filled: {
                                            format: function format(arg) {
                                                var status = ["office", "home", "leave", "x", "holiday", "Planned leave", "Planned home", "Planned office", "weekend"];
                                                var d=description[moment(arg.date).unix()];
                                                return status[arg.count - 1]+(d?" ("+d+")":"") + " on " + arg.date;
                                            }
                                        }
                                    }
                                });
                            }
                            var legenddiv =
    $('<div class="col-md-12"><span title="home: '+home[year]+'"><svg width="10" height="10"><rect class=" graph-rect r2 q2" width="10" height="10"></rect></svg></span>'+
    ' <span title="leave: '+leave[year]+'"><svg width="10" height="10"><rect class=" graph-rect r2 q3" width="10" height="10"></rect></svg></span>'+
    ' <span title="unmarked: '+x[year]+'"><svg width="10" height="10"><rect class=" graph-rect r2 q4" width="10" height="10"></rect></svg></span></div>'
    );
    $("#myhistory").append(legenddiv);
                        }
                    });
                });
                </script>
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
