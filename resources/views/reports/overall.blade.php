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
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Date From:</label>
								<div class="col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm datepicker" name="start_date" id="start_date" value="{{ $start_date ?? date('Y-m-d',strtotime('-1 week')) }}" required />
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Date To:</label>
								<div class="col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm datepicker" name="end_date" id="end_date" value="{{ $end_date ?? date('Y-m-d',strtotime('yesterday')) }}" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
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
							<span>Overall Hours Report: &nbsp; {{ date('D j F Y',strtotime($start_date)) }} - {{ date('D j F Y',strtotime($end_date)) }}</span>
						</div>
						<div class="panel-body">

							<div class="table-responsive">
								<table class="table table-striped table-hover js-exportable dataTable">
									<thead>
										<tr>
											<th>Employee</th>
											<th>Hours Present</th>
											<th>Breaks</th>
										</tr>
									</thead>
									<tbody>
										@foreach($data as $n=>$d)
											@php $present = 0; $break = 0; @endphp
											@foreach($d as $dd)
												@php
                                                $present += $funcs->calcMinsPresent($dd);
												$break += $funcs->calcMinsBreak($dd);
                                                @endphp
                                            @endforeach
										<tr>
											<td>{{ $n }}</td>
											<td>{{ floor($present/60) }}h {{ ($present % 60) }}m</td>
											<td>{{ floor($break/60) }}h {{ ($break % 60) }}m</td>
										</tr>
                                        @endforeach
									</tbody>
								</table>
							</div><!-- //table-responsive -->

						</div><!-- //panel-body -->
					</div><!-- //panel -->
				</div><!-- //col -->
			</div><!-- //row -->
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

<script>
$(function(){

	moment.updateLocale('en', {
		week: { dow: 1 } // Monday is the first day of the week
	});

	$('#start_date').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: true,
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
	var _title = 'Overall Hours Report';
	var _message = '{{ date('D j F Y',strtotime($start_date ?? '')) }} - {{ date('D j F Y',strtotime($end_date ?? '')) }}';
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
