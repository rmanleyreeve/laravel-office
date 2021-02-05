@php
function prev_week_url($ws){
	$t = strtotime("-7 day",$ws);
	return date('o/W', $t);
}
function next_week_url($ws){
	$t = strtotime("+1 day",$ws);
	return date('o/W', $t);
}
@endphp
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
                        <div id="vue-chart"><bar-chart></bar-chart></div>
                    </div><!-- //panel-body -->
                </div><!-- //panel -->
            </div><!-- //col -->
            <!-- #END# Bar Chart -->
        </div><!-- //row -->

	</div>
</section>

<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/chartjs/dist/Chart.bundle.js"></script>
<!-- Vue.JS -->
<script src="/assets/plugins/vue/dist/vue.js"></script>
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
                            $vals = [];
                        @endphp
                        @foreach($data as $dd)
                            @php $vals[] = round($funcs->calcMinsPresent($dd[$d])/60,2); @endphp
                        @endforeach
                        { label:"{{ $d }}", backgroundColor:"{{ $barchart_colours[$n] }}", data: {!! json_encode($vals) !!} },
                    @endforeach
                    ]
                } ,
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
const employees = {!! json_encode($employees) !!};

$(function () {
	"use strict"

	$('.fc-prev-button, .fc-next-button').on('click',function(){
		let wk = $(this).data('week');
		window.location = '/attendance/week/' + wk;
	});

});

</script>
