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
            .q5 { /*holiday*/
                background: #337ab7;
                fill: #337ab7;
            }
            .history-month{
                display: inline-block;
                vertical-align: top;
            }
            .history-legend{

            }
            .history-legend>span{
                display:inline-block;
                width: 15px;
                height:15px;
                text-align: center;
                vertical-align: middle;
                line-height: 15px;
                color:white;
                font-size:10px;
                cursor:default;
            }
        </style>
        <script>
        function tm(date) {
            return moment(date, "YYYY-MM-DD");
        }
        $(document).ready(function () {
            var values = { "office": 1, "home": 2, "leave": 3, "x": 4, "holiday": 5, "Planned leave": 6, "Planned home": 7, "Planned office": 8, "weekend": 9 };
            var location=new Promise(function(resolve,reject){
                $.getJSON("/user/{{$user->id}}/location").done(function (data) {
                    resolve(data)
                });
            });
            var holidays=new Promise(function(resolve,reject){
                $.getJSON("/api/v1/holidays").done(function (data) {
                    resolve(data)
                });
            });
            Promise.all([location,holidays]).then(function(arg){
                if(arg[0].length===0){
                    return ;
                }
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
                    $(`<div class='history-legend'>
                        <span title="Home" class="q2">${home[year]}</span><span title="Leave" class="q3">${leave[year]}</span><span title="Unmarked" class="q4">${x[year]}</span>
                     </div>
                    `);
                    yeardiv.find("h4").append(legenddiv);
                    legenddiv.find('span').tooltip()
                }
            });
        });
        </script>