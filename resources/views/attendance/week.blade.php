<?php
function prev_week_url($ws){
	$t = strtotime("-7 day",$ws);
	return date('o/W', $t);
}
function next_week_url($ws){
	$t = strtotime("+1 day",$ws);
	return date('o/W', $t);
}
?>
<section class="content">

	<div class="page-heading">
		<h1>Attendance - Week {{ $week }}, {{ $year }}</h1>
		<div class="fc fc-button-group" style="margin-left:20px;">
			<button title="Previous Week" data-week="{{ prev_week_url($weekstart) }}" type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left"><span class="fc-icon fc-icon-left-single-arrow"></span></button>
			@if($week != date('W'))
			<button title="Next Week" data-week="{{ next_week_url($weekstop) }}" type="button" class="fc-next-button fc-button fc-state-default fc-corner-right"><span class="fc-icon fc-icon-right-single-arrow"></span></button>
			<button title="Current Week" type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" onClick="window.location='/attendance/week/{{ date('o/W') }}';">This Week</button>
            @endif
		</div>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<!-- Bar Chart -->
			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><span>Office Attendance: {{ date('D j M Y',$weekstart) }} - {{ date('D j M Y',$weekstop) }}</span></div>
					<div class="panel-body">
						<canvas id="bar_chart" height="130"></canvas>
					</div>
				</div>
			</div>
			<!-- #END# Bar Chart -->
		</div>

	</div>
</section>

<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/chartjs/dist/Chart.bundle.js"></script>
<script>

var employees = {!! json_encode($employees) !!};
var chart;

$(function () {
	"use strict"

	$('.fc-prev-button, .fc-next-button').on('click',function(){
		var wk = $(this).data('week');
		window.location = '/attendance/week/' + wk;
	});

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
	$vals = [];
	foreach($data as $dd) {
		$vals[] = round($funcs->calcMinsPresent($dd[$d])/60,2);
	}
?>
					{ label:"{{ $d }}", backgroundColor:"{{ $barchart_colours[$n] }}", data: {!! json_encode($vals) !!} },
<?php } ?>
				]
			},
			options: {
				responsive: true,
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
		var canvas = document.getElementById("bar_chart");
		if(window.innerWidth < 640) {
			canvas.height = 400;
		}
		chart = new Chart(document.getElementById("bar_chart").getContext("2d"), config);
	}

	function handleClick(evt){
		var activeElement = chart.getElementAtEvent(evt);
		var date = chart.config.data.datasets[activeElement[0]._datasetIndex].label;
		var d = moment(date+' {{ $year }}','ddd D MMM YYYY').format('YYYY-MM-DD');
		var n = chart.config.options.scales.xAxes[0].labels[activeElement[0]._index];
		var uid = employees[n];
		//console.log(d, uid);
		var a = $('<a role="button" data-toggle="modal" data-target="#bsModal" href="/attendance/day/'+d+'/'+uid+'/view"></a>');
		$('body').append(a);
		a.trigger('click').remove();
	}

});

</script>
