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
                background: #337ab7;
                fill: #337ab7;
            }
            .history-month{
                display: inline-block;
                vertical-align: top;
            }
        </style>
        <script>
        function tm(date) {
            return moment(date, "YYYY-MM-DD");
        }
        $(document).ready(function () {
            var values = { "office": 1, "home": 2, "leave": 3, "x": 4, "holiday": 5, "Planned leave": 6, "Planned home": 7, "Planned office": 8, "weekend": 9 };
            var location=new Promise(function(resolve,reject){
                $.getJSON("location").done(function (data) {
                    resolve(data)
                });
            });
            var holidays=new Promise(function(resolve,reject){
                $.getJSON("api/v1/holidays").done(function (data) {
                    resolve(data)
                });
            });
            Promise.all([location,holidays]).then(function(arg){
                var data=arg[0],holidays=arg[1].data,holidayhash={}, minyear = tm(data[0].date),maxyear = tm(data[0].date),hash = {},description={},leave={},home={},x={};
                _.each(data,function (location) {
                    minyear = moment.min(minyear, tm(location.date));
                    maxyear = moment.max(maxyear, tm(location.date));
                    hash[moment(location.date, "YYYY-MM-DD").unix()]=location.location;
                    description[moment(location.date, "YYYY-MM-DD").unix()]=location.description;
                });
                _.each(holidays,function (holiday) {
                    holidayhash[moment(holiday.date, "YYYY-MM-DD HH:mm:ss").unix()]=holiday.name;
                });
                minyear = minyear.year();
                maxyear = maxyear.year();
                for (var year = maxyear; year >= minyear; year--) {
                    leave[year]=0;
                    home[year]=0;
                    x[year]=0;
                    var yeardiv = $("<div class='col-md-12'><h4>" + year + "</h4></div>");
                    $("#myhistory").append(yeardiv);
                    for (var month = 0; month < 12; month++) {
                        var a=moment(new Date(year, month, 1));
                        var b=a.clone().add(1, 'months');
                        var data={};
                        for (var m = moment(a); m.isBefore(b); m.add(1, 'days')) {
                            var u=m.unix();
                            if(hash[u]){
                                data[u]=values[hash[u]];
                            }else{
                                if(m.day()===0||m.day()===6){
                                    data[u]= values["weekend"];
                                }
                                if(holidayhash[u]){
                                    data[u]= values["holiday"];
                                }
                                if(m.isSameOrBefore(moment())){
                                    if(data[u]===undefined){
                                        data[u]=values["x"];
                                        x[year]++;
                                    }
                                }
                            }
                            if(hash[u]==="leave"){
                                leave[year]++;
                            }
                            if(hash[u]==="home"){
                                home[year]++;
                            }
                        }
                        var monthdiv = $("<div class='history-month'></div>");
                        yeardiv.append(monthdiv);
                        var cal = new CalHeatMap();
                        cal.init({
                            itemSelector: monthdiv.get(0),
                            data: data,
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
                                        var d=description[moment(arg.date).unix()]||holidayhash[moment(arg.date).unix()];
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