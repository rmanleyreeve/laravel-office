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
								<tbody id="attendance"></tbody>
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
						<canvas id="bar_chart" height="300"></canvas>
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

<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="/assets/plugins/fullcalendar/dist/locale/en-gb.js"></script>
<script src="/assets/plugins/chartjs/dist/Chart.bundle.js"></script>
<script src="assets/plugins/peity/jquery.peity.js"></script>
<script>

var employees = {!! htmlspecialchars_decode(json_encode($employees)) !!};
var chart;

$(function () {
	"use strict"

	$('#draggable').sortable({
		handle: '.panel .panel-heading'
	});
	$('#draggable').disableSelection();

	// bar chart
	initBarChart();
	function initBarChart() {
		var config = {
			type: 'bar',
			legend: {display:false,reverse:true},
			data: {
				datasets: [
<?php
foreach($days as $i=>$d) {
	$n = substr($d,0,3);
	$vals = array();
	foreach($data as $dd) {
		$vals[] = round($funcs->calcMinsPresent($dd[$d])/60,2);
	}
?>
					{ label:"{{ $d }}", backgroundColor:"{{ $barchart_colours[$n] }}", data: {{ json_encode($vals) }} },
<?php } ?>
				]
			},
			options: {
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
							var label = data.datasets[i.datasetIndex].label || '';
							if (label) {
								label += ': ' + Math.floor(i.yLabel) +'h ' + Math.round((i.yLabel*60)%60) + 'm';
							}
							return label;
						}
					}
        },
				onClick: handleClick
			}
		}
		chart = new Chart(document.getElementById("bar_chart").getContext("2d"), config);
	}

	function handleClick(evt){
		var activeElement = chart.getElementAtEvent(evt);
		var date = chart.config.data.datasets[activeElement[0]._datasetIndex].label;
		var d = moment(date,'ddd D MMM YYYY').format('YYYY-MM-DD');
		console.log(d);
		var n = chart.config.options.scales.xAxes[0].labels[activeElement[0]._index];
		var uid = employees[n];
		//console.log(d, uid);
		var a = $('<a role="button" data-toggle="modal" data-target="#bsModal" href="/attendance/day/'+d+'/'+uid+'/view"></a>');
		$('body').append(a);
		a.trigger('click').remove();
	}

	//populate attendance list
	var attendance = {};

	var checkAttendance = function(){
		fetch('/ajax/dashboard-attendance')
		.then(function(response) {
			return response.json()
		}).then(function(responseJson) {
			//console.log(responseJson);
			if(responseJson != attendance) {
				attendance = responseJson;
				updateAttendanceTable(responseJson);
			}
		}).catch(function(ex) {
			console.log('checkAttendance failed', ex)
		});
	}

	// ensure strict JS date format
	var sqlToJsDate = function(sqlDate){
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

	var updateAttendanceTable = function(json) {
		//console.clear();
		$('#attendance').children().remove();
		$.each(json, function(k,r) {
			var start=null, end=null, t1='',t2='', mins_pres=0, mins_break=0;
			var tr = $('<tr><td></td></tr>');
			tr.append('');
			tr.append('<td nowrap>'+r.name+' <a role="button" data-toggle="modal" data-target="#bsModal" href="/attendance/day/{{ date('Y-m-d') }}/'+r.uid+'/view"><i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" title="View Activity"></i></a></td>');
			tr.append('<td>'+r.role+'</td>');
			if(r.activity_log) {
				start = r.activity_log[0];
				end = r.activity_log.slice(-1)[0];
				if('ENTRY' == end['activity']) { //present
					tr.append('<td><i class="fa fa-circle m-r-5 col-success"></i>IN</td>');
				} else if('EXIT' == end['activity']) { //absent
					tr.append('<td><i class="fa fa-circle m-r-5 col-warning"></i>OUT</td>');
				}
			} else {
				tr.append('<td><i class="fa fa-circle m-r-5 col-danger"></i>OFF</td>');
			}
			t1 = (start && 'ENTRY' == start['activity']) ? start['time']:'';
			t2 = (end && 'EXIT' == end['activity']) ? end['time']:'';
			tr.append('<td>'+t1+'</td><td>'+t2+'</td>');
			$.each(r.activity_log,function(i,e){
				if('EXIT' == e.activity) {
					var t_in = sqlToJsDate(r.activity_log[i-1].time_logged);
					var t_out = sqlToJsDate(e.time_logged);
					mins_pres += Math.ceil((t_out - t_in)/1000/60);
				}
				if('ENTRY' == e.activity) {
					if(r.activity_log[i-1]) { // previous exit
						var t_out = sqlToJsDate(r.activity_log[i-1].time_logged).getTime();
						var t_in = sqlToJsDate(e.time_logged).getTime();
						mins_break += Math.ceil((t_in - t_out)/1000/60);
					}
					if(e.time_logged == end['time_logged']) { // still present
						var t_in = sqlToJsDate(e.time_logged);
						var now = new Date();
						mins_pres += Math.ceil((now - t_in)/1000/60);
					}
				}
			});
			var hrs = (mins_pres) ? Math.floor(mins_pres/60) +'h ' + (mins_pres % 60) + 'm' : '';
			tr.append('<td>'+hrs+'</td>');
			var breaks = (mins_break) ? Math.floor(mins_break/60) +'h ' + (mins_break % 60) + 'm' : '';
			tr.append('<td>'+breaks+'</td>');
			tr.append( (r.activity_log) ? '<td><span class="pie">'+mins_pres+','+mins_break+','+(480-mins_break-mins_pres)+'</span></td>' : '<td></td>' );
			$('#attendance').append(tr);
			$('span.pie').peity('pie',{ fill:['#16a085','#DA4453','#ccc'] });
			//console.log(r.name + " IN: "+mins_pres + " OUT: " + mins_break);
		});
	}
	// check db every 15 secs
	checkAttendance();
	setInterval(checkAttendance, 15000);

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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // your code here
}, false);
</script>
