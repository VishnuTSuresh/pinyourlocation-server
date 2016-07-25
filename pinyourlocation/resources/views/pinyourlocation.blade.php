@extends('layouts.app')

@section('content')
<div class="container">
@if(Entrust::hasRole('verified'))
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
              Do you have any future plans?
            </div>
            <div class="panel-body">
                <form action="locations" method="post">
                  <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                  <div class="row">
                    <div class="col-sm-7">
                      <div class="input-daterange input-group input-group-justified" id="datepicker">
                          <input type="text" class="form-control" name="from" />
                          <span class="input-group-addon">to</span>
                          <input type="text" class="form-control" name="to" />
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
                  startDate:new Date()
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

                    .q2 {
                        /*home*/
                        background: #f0ad4e;
                        fill: #f0ad4e;
                    }

                    .q3 {
                        /*leave*/
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
                        _.each(_.groupBy(data, function (location) {
                            return moment(location.date, "YYYY-MM-DD").year();
                        }), function (data, year) {

                            _.each(_.groupBy(data, function (location) {
                                return moment(location.date, "YYYY-MM-DD").month();
                            }), function (data, month) {
                                ordered[year] = ordered[year] || {};
                                ordered[year][month]={};
                                for (var d in data) {
                                    ordered[year][month][moment(data[d].date, "YYYY-MM-DD").unix()] = values[data[d].location];
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
                                                return status[arg.count - 1] + " on " + arg.date;
                                            }
                                        }
                                    },
                                    onClick: function(date) {
                                        enddate = date;
                                        startdate = !d3.event.shiftKey ? date : startdate ? startdate : date; //Multi select with Shift and mouseclick.
                                        var firstdate = Math.min(startdate, enddate);
                                        var lastdate = Math.max(startdate, enddate);
                                        cal.highlight(d3.time.day.range(firstdate, lastdate+ONEDAY).filter(function(day){
                                            return day.getDay() % 6 != 0; //do not pick weekends.
                                        }));
                                        //filter selected dates from full data
                                        var newdates = data.slice(moment(firstdate).dayOfYear() - 1, moment(lastdate).dayOfYear());
                                        var status = {"home": [], "office": [], "leave": [], "wfh": [], "holiday":[], "x": [], "future": [], "Planned home":[], "Planned leave": [], "Planned office": []};
                                        newdates.forEach(function(element) {
                                            var isfuture = new Date(element.date) > new Date();
                                            if (element.type != "weekend") {
                                                if (element.type == "holiday") {
                                                    status.holiday.push(element);
                                                } else if (isfuture && element.location == "x") {
                                                    status.future.push(element);
                                                } else {
                                                    status[element.location].push(element);
                                                }
                                            }
                                        });
                                        //$("#leaveplan").html(printLeavePlan(status));
                                        //$("#reviewcommeninput").focus();
                                    },
                                });
                            }
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
