<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Reporting</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Select Criteria</span></div>
					<div class="panel-body">
						<form class="form-horizontal validate" id="formAddEdit" method="post">
                            @csrf
							<div class="form-group" has-feedback>
								<label class="col-sm-2 control-label">Employee:</label>
								<div class="col-sm-6">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
										<select class="form-control input-sm" name="uid" id="uid" required>
											<option value="">-- Please select --</option>
											@foreach($employees as $e)
												<option value="{{ $e->uid }}"@php $utils->select($e->uid,$uid); @endphp>{{ $e->firstname }} {{ $e->surname }}</option>
                                            @endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-2 control-label">Date From:</label>
								<div class="col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm datepicker" name="start_date" id="start_date" value="{{ $start_date ?? date('Y-m-d',strtotime('-1 week')) }}" required />
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-2 control-label">Date To:</label>
								<div class="col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm datepicker" name="end_date" id="end_date" value="{{ $end_date ?? date('Y-m-d',strtotime('yesterday')) }}" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 p-l-15">
									<button id="btn-select" type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
								</div>
							</div>
						</form>
					</div><!-- //panel-body -->
				</div><!-- //panel -->

			</div><!-- //col -->
		</div><!-- //row -->

		@if($posted) @if($data)
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
					<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
						<div class="panel-heading">
							<span>Daily Attendance Breakdown: &nbsp; {{ $employee_name }} ({{ date('j F Y',strtotime($start_date)) }} - {{ date('j F Y',strtotime($end_date)) }})</span>
						</div>
						<div class="panel-body">

							<div class="table-responsive">
								<table class="table table-striped table-hover js-exportable dataTable">
									<thead>
										<tr>
											<th>Day</th>
											<th>Hours Present</th>
											<th>Breaks</th>
										</tr>
									</thead>
									<tbody>
										@php $t_present = 0; $t_break = 0; @endphp
										@foreach($data as $day=>$dd)
                                            @php
											$present = $funcs->calcMinsPresent($dd);
											$break = $funcs->calcMinsBreak($dd);
											$t_present += $present;
											$t_break += $break;
											$hp = floor($present/60); $mp = ($present % 60);
											$hb = floor($break/60); $mb = ($break % 60);
											$chart_data[$day] = round($present/60,2);
                                            @endphp
										<tr>
											<td>{{ $day }}</td>
											<td>{{ $hp }}h {{ $mp }}m</td>
											<td>
											{{ $hb }}h {{ $mb }}m
											@if($present>360 && $break<30)
                                                &nbsp;&nbsp;<i class="fa fa-lg fa-warning col-{{ ($break<25) ? "danger" : "warning" }}" title="Worked over 6 hrs without 30 min break"></i>
                                            @endif
											</td>
										</tr>
                                        @endforeach
									</tbody>
									<tfoot>
										<tr>
											<th>Total</th>
											<th>{{ floor($t_present/60) }}h {{ ($t_present % 60) }}m</th>
											<th>{{ floor($t_break/60) }}h {{ ($t_break % 60) }}m</th>
										</tr>
									</tfoot>
								</table>
							</div><!-- //table-responsive -->

							<div class="col-xs-6">
								<div class="alert icon-alert alert-success col-xs-12" role="alert">
									<i class="fa fa-fw fa-check-circle"></i>
									<span class="font-bold">Total hours present: {{ floor($t_present/60) }}h {{ ($t_present % 60) }}m</span>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="alert icon-alert alert-warning col-xs-12" role="alert">
									<i class="fa fa-fw fa-info-circle"></i>
									<span class="font-bold">Total hours break: {{ floor($t_break/60) }}h {{ ($t_break % 60) }}m</span>
								</div>
							</div>

						</div><!-- //panel-body -->
					</div><!-- //panel -->
				</div><!-- //col -->
			</div><!-- //row -->

			<div class="row clearfix">
				<!-- Line Chart -->
				<div class="col-xs-12">
					<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
						<div class="panel-heading"><span>Attendance Summary: &nbsp; {{ $employee_name }} ({{ date('j F Y',strtotime($start_date)) }} - {{ date('j F Y',strtotime($end_date)) }})</span></div>
						<div class="panel-body">
                            <div id="vue-chart"><line-chart></line-chart></div>
						</div>
					</div>
				</div>
				<!-- #END# Line Chart -->
			</div>

		@else
			<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-6"><div class="alert alert-warning" role="alert">
				<i class="fa fa-fw fa-warning"></i><strong>No results found</strong>
			</div></div></div>
		@endif
		@endif

	</div><!-- //page-body -->
</section><!-- //content -->


<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>
<script src="/assets/plugins/chartjs/src/chart.js"></script>
<!-- Vue.JS -->
<script src="/assets/plugins/vue/dist/vue.js"></script>
<script src="/assets/js/vendor/vue-chartjs.min.js"></script>
@if($posted && $data)
<script>
    Vue.component('line-chart', {
        extends: VueChartJs.Line,
        mounted () {
            this.renderChart(
                {
                    labels: {!! json_encode(array_keys($chart_data)) !!},
                    datasets: [{
                        label: "Hours Present",
                        data: {!! json_encode(array_values($chart_data)) !!},
                        pointBorderWidth: 1,
                        lineTension: 0,
                        fill: 'origin',
                        borderWidth: 1,
                        borderColor: 'rgba(200, 0, 0, 1)',
                    }]
                },
                {
                    legend: {
                        display:false,
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            stacked: false,
                            beginAtZero: true,
                            ticks: {stepSize:1,min:0,autoSkip:false},
                            scaleLabel: {display:true,labelString:'HOURS'},
                        }]
                    },
                }
            )
        }
    })
    new Vue({
        el: '#vue-chart',
        data: {},
    })
</script>
@endif

<script>

$(function(){

	moment.updateLocale('en', {
		week: { dow: 1 } // Monday is the first day of the week
	});

	$('#start_date').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: true,
		maxDate: "{{ date('Y-m-d',strtotime('yesterday')) }}",
		useCurrent: false
	});
	$('#end_date').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: true,
		maxDate: "{{ date('Y-m-d',strtotime('yesterday')) }}",
		useCurrent: false
	});
	$("#start_date").on("dp.change", function (e) {
		$('#end_date').data("DateTimePicker").minDate(e.date);
	});
	$("#end_date").on("dp.change", function (e) {
		$('#start_date').data("DateTimePicker").maxDate(e.date);
	});

	// Exportable data table
	const _title = 'Daily Attendance Breakdown';
	const _message = '{{ ($employee_name ?? '') }} ({{ date('j F Y',strtotime($start_date ?? '')) }} - {{ date('j F Y',strtotime($end_date ?? '')) }})';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
		lengthChange:false,
		paging:false,
		info:false,
		buttons: [
			{ extend: 'copyHtml5', footer: true, header:true },
			{ extend: 'excelHtml5', footer: true, header:true },
			{ extend: 'pdfHtml5', footer: true, header:true, message:_message, title:_title },
			{ extend: 'print', footer: true, header:true, message:_message, title:_title },
		]
	});

});
</script>
