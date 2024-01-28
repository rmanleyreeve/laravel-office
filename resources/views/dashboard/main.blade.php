<section class="content">

	<div class="page-heading">
		<h1>DASHBOARD</h1>
		<small><span class="hour" id="cal-time"></span> <span class="date" id="cal-date"></span></small>
	</div>

	<div class="page-body" id="draggable">

		<div class="row clearfix">
			<!-- Table -->
			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><i class="fa fa-arrows m-r-5"></i><span> Office Attendance: {{ date('l j F Y') }}</span></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table js-exportable">
								<thead>
									<tr>
										<th></th>
										<th>Employee</th>
										<th>Role</th>
										<th>Status</th>
										<th>Time In</th>
										<th>Time Out</th>
										<th>Hours</th>
										<th>Breaks</th>
									</tr>
								</thead>
                                <tbody id="v_attendance">
                                    <tr v-for="r in vAttendance" :key="r.uid">
                                        <td v-if="r.activity_log">
                                            <piety :mins_pres=getDurations(r.activity_log).mins_pres :mins_break=getDurations(r.activity_log).mins_break :pie=getDurations(r.activity_log).pie></piety>
                                        </td> <td v-else></td>
                                        <td nowrap>
                                            @{{ r.name }}
                                            <a v-bind:href="'/attendance/day/@php echo date('Y-m-d'); @endphp/'+r.uid+'/view'" role="button" data-toggle="modal" data-target="#bsModal"><i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" title="View Activity"></i></a>
                                        </td>
                                        <td>@{{ r.role }}</td>
                                        <td v-if="r.activity_log">
                                            <span v-if="checkStatus(r.activity_log)"><i class="fa fa-circle m-r-5 col-success"></i>IN</span>
                                            <span v-else><i class="fa fa-circle m-r-5 col-warning"></i>OUT</span>
                                        </td>
                                        <td v-else><i class="fa fa-circle m-r-5 col-danger"></i>OFF</td>
                                        <td v-if="r.activity_log">@{{ getTimes(r.activity_log).t1 }}</td>
                                        <td v-if="r.activity_log">@{{ getTimes(r.activity_log).t2 }}</td>
                                        <td v-if="r.activity_log">@{{ getDurations(r.activity_log).hrs }}</td>
                                        <td v-if="r.activity_log">@{{ getDurations(r.activity_log).breaks }}</td>
                                    </tr>
                                </tbody>
							</table>
						</div><!-- //table-responsive -->
					</div><!-- //panel-body -->
				</div><!-- //panel -->
			</div><!-- //col -->
			<!-- #END# Table -->
		</div><!-- //row -->

		<div class="row clearfix">
			<!-- Bar Chart -->
			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><i class="fa fa-arrows m-r-5"></i> <span>ATTENDANCE SUMMARY: Past 7 Days</span></div>
					<div class="panel-body">
                        <div id="vue-chart"><bar-chart></bar-chart></div>
					</div><!-- //panel-body -->
				</div><!-- //panel -->
			</div><!-- //col -->
			<!-- #END# Bar Chart -->
		</div><!-- //row -->

		<div class="row clearfix">
			<!-- Calendar -->
			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><i class="fa fa-arrows m-r-5"></i> <span>ABSENCES: Next 7 Days</span></div>
					<div class="panel-body">
						<div id="calendar" class="dashboard-cal"></div>
					</div><!-- //panel-body -->
				</div><!-- //panel -->
			</div><!-- //col -->
			<!-- #END# Calendar -->
		</div><!-- //row -->

	</div>
</section>

<!-- Vue.JS -->
<script src="/assets/plugins/vue/dist/vue.js"></script>

<template id="piety_tpl">
    <span class="pie">@{{ mins_pres }},@{{ mins_break }},@{{ pie }}</span>
</template>

<script>
Vue.component('piety', {
    template: '#piety_tpl',
    props: ['mins_pres', 'mins_break', 'pie'],
    mounted(){
        $(this.$el).peity('pie',{ fill:['#16a085','#DA4453','#ccc'] });
    }
});

new Vue({
    el: '#v_attendance',
    data: {
        vAttendance: []
    },
    created: function(){
        let self = this;
        self.getData();
        setInterval(function(){
            self.getData();
        },15000);
    },
    methods: {
        getData: function(){
            let self = this;
            fetch('/ajax/dashboard-attendance')
            .then(function(response) {
                return response.json()
            }).then(function(responseJson) {
                console.log(responseJson);
                self.vAttendance = responseJson;
            }).catch(function(ex) {
                console.log('vue getData failed', ex);
            });
        },
        checkStatus: function(alog) {
            let start = alog[0];
            let end = alog.slice(-1)[0];
            if('ENTRY' == end['activity']) {
                return true; //present
            } else if('EXIT' == end['activity']) {
                return false;
            }
        },
        getTimes: function(alog){
            let start = alog[0];
            let end = alog.slice(-1)[0];
            let t1 = (start && 'ENTRY' == start['activity']) ? start['time'] : '';
            let t2 = (end && 'EXIT' == end['activity']) ? end['time'] : '';
            return { "t1": t1, "t2": t2 };
        },
        getDurations: function(alog){
            let mins_pres=0; let mins_break=0;
            let start = alog[0]; let end = alog.slice(-1)[0];
            var self = this;
            $.each(alog,function(i,e){
                if('EXIT' == e.activity) {
                    let t_in = self.sqlToJsDate(alog[i-1].time_logged);
                    let t_out = self.sqlToJsDate(e.time_logged);
                    mins_pres += Math.ceil((t_out - t_in)/1000/60);
                }
                if('ENTRY' == e.activity) {
                    if(alog[i-1]) { // previous exit
                        let t_out = self.sqlToJsDate(alog[i-1].time_logged).getTime();
                        let t_in = self.sqlToJsDate(e.time_logged).getTime();
                        mins_break += Math.ceil((t_in - t_out)/1000/60);
                    }
                    if(e.time_logged == end['time_logged']) { // still present
                        let t_in = self.sqlToJsDate(e.time_logged);
                        let now = new Date();
                        mins_pres += Math.ceil((now - t_in)/1000/60);
                    }
                }
            });
            let hrs = (mins_pres) ? Math.floor(mins_pres/60) +'h ' + (mins_pres % 60) + 'm' : '';
            let breaks = (mins_break) ? Math.floor(mins_break/60) +'h ' + (mins_break % 60) + 'm' : '';
            return { "mins_pres": mins_pres, "mins_break": mins_break, "hrs": hrs, "breaks": breaks, "pie": (480-mins_break-mins_pres) };
        },
        sqlToJsDate: function(sqlDate){
            // sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss")
            var sqlDateArr1 = sqlDate.split("-"); // sqlDateArr1[] = ['yyyy','mm','dd hh:mm']
            var sYear = sqlDateArr1[0];
            var sMonth = (Number(sqlDateArr1[1]) - 1).toString();
            var sqlDateArr2 = sqlDateArr1[2].split(" "); // sqlDateArr2[] = ['dd', 'hh:mm:ss']
            var sDay = sqlDateArr2[0];
            var sqlDateArr3 = sqlDateArr2[1].split(":"); // sqlDateArr3[] = ['hh','mm','ss']
            var sHour = sqlDateArr3[0];
            var sMinute = sqlDateArr3[1];
            var sSecond = sqlDateArr3[2];
            return new Date(sYear,sMonth,sDay,sHour,sMinute,sSecond);
        }
    }

});
</script>

<script src="/assets/plugins/peity/jquery.peity.js"></script>
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="/assets/plugins/fullcalendar/dist/locale/en-gb.js"></script>
<script src="/assets/plugins/chartjs/dist/Chart.bundle.js"></script>
<script src="/assets/js/vendor/vue-chartjs.min.js"></script>
<script>
    Vue.component('bar-chart', {
        extends: VueChartJs.Bar,
        mounted () {
            this.renderChart(
                {
                    datasets: [
                    @foreach($days as $i=>$d)
                        @php
                            $n = substr($d,0,3);
                            $vals = array();
                        @endphp
                        @foreach($data as $dd)
                            @php $vals[] = round($funcs->calcMinsPresent($dd[$d])/60,2); @endphp
                        @endforeach
                        { label:"{{ $d }}", backgroundColor:"{{ $barchart_colours[$n] }}", data: {{ json_encode($vals) }} },
                    @endforeach
                    ]
                },
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            type: 'category',
                            labels: {!! json_encode(array_keys($data)) !!},
                            stacked: false,
                            ticks: {stepSize:1,min:0,autoSkip:false},
                        }],
                        yAxes: [{
                            stacked: false,
                            beginAtZero: true,
                            ticks: {stepSize:1,min:0,autoSkip:false},
                            scaleLabel: {display:true,labelString:'HOURS'},
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(i, data) {
                                let label = data.datasets[i.datasetIndex].label || '';
                                if (label) {
                                    label += ': ' + Math.floor(i.yLabel) +'h ' + Math.round((i.yLabel*60)%60) + 'm';
                                }
                                return label;
                            }
                        }
                    },
                    onClick: function (evt){
                        let activeElement = this.getElementAtEvent(evt);
                        let date = this.data.datasets[activeElement[0]._datasetIndex].label;
                        let d = moment(date,'ddd D MMM YYYY').format('YYYY-MM-DD');
                        console.log(d);
                        let n = this.options.scales.xAxes[0].labels[activeElement[0]._index];
                        let uid = employees[n];
                        //console.log(d, uid);
                        let a = $('<a role="button" data-toggle="modal" data-target="#bsModal" href="/attendance/day/'+d+'/'+uid+'/view"></a>');
                        $('body').append(a);
                        a.trigger('click').remove();
                    }
                }
            )
        }
    })

    new Vue({
        el: '#vue-chart',
        data: {},
    })

</script>

<script>
    const employees = {!! htmlspecialchars_decode(json_encode($employees)) !!};

    $(function () {
        "use strict"

        $('#draggable').sortable({
            handle: '.panel .panel-heading'
        });
        $('#draggable').disableSelection();

        // display time in header bar
        updateClock();
        setInterval('updateClock()', 3000);
        $('#cal-date').html($.datepicker.formatDate('DD d MM yy', new Date()));

        // calendar
        $('#calendar').fullCalendar({
            events: {!! json_encode($events) !!},
            defaultView: 'basic',
            dayOfMonthFormat: 'ddd D MMM',
            weekends: false,
            visibleRange: {
                start: moment('{{ date('Y-m-d') }}'),
                end: moment('{{ date('Y-m-d', strtotime('+7 days')) }}'),
            },
            header: {
                left: '',
                center: 'title',
                right: ''
            },
            eventClick: function(e) {
                //$('<button type="button" data-toggle="modal" data-target="#bsModal" href="/absences/'+e.id+'/edit"></button>').appendTo('body').trigger('click');
            },
            eventRender: function(event, element) {
                element.tooltip({
                    title: event.info,
                    container: 'body',
                    trigger : 'hover'
                });
            },
            editable: false,
            droppable: false,
            contentHeight: "auto",
        });

    });
</script>
