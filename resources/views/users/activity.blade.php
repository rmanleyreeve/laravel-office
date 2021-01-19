<section class="content">

	<div class="page-heading">
		<h1>System Management</h1>
	</div>

	<div class="page-body">

		<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
			<div class="panel-heading">
				<span>Daily User Activity Log for {{ $date }}</span>
				<div class="fc fc-button-group" style="margin-left:20px;">
					<button title="Prev" type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" onClick="window.location='/users/activity/date/{{ date('Y-m-d',strtotime('-1 day',$timestamp)) }}';">Prev</button>
					@if($date != date('Y-m-d'))
					<button title="Next" type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" onClick="window.location='/users/activity/date/{{ date('Y-m-d',strtotime('+1 day',$timestamp)) }}';">Next</button>
					<button title="Today" type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" onClick="window.location='/users/activity/date/{{ date('Y-m-d') }}';">Today</button>
					@endif
					&nbsp;&nbsp; Change Date:
					<div class="form-group" style="display:inline-block; position:relative;">
						<input class="form-control input-sm datepicker" id="activity-date" value="{{ $date }}" />
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover js-exportable dataTable">
						<thead>
							<tr>
								<th>Time</th>
								<th>User</th>
								<th>Activity</th>
								<th class="no-sort no-export">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($recordset as $record)
							<tr>
								<td>{{ $record->created_at }}</td>
								<td>{{ $record->fullname }}</td>
								<td>{{ $record->activity }}</td>
								<td>
									@if($record->hasdata)
									<div class="form-group">
										<a role="button" data-toggle="modal" data-target="#bsModal" href="/users/activity/data/{{ $record->uid }}" class="btn btn-xs btn-outline btn-success"><i class="fa fa-eye"></i> View Data</a>
									</div>
                                    @endif
								</td>
							</tr>
                        @endforeach
						</tbody>
					</table>
				</div><!-- //table-responsive -->
			</div><!-- //panel-body -->
		</div><!-- //panel -->

	</div><!-- //page-body -->
</section><!-- //content -->

<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>

<script>
$(function(){

	$('.datepicker').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: false,
		useCurrent: false
	});
	$('#activity-date').on("dp.change", function (e) {
		var url = '/users/activity/date/'+e.date;
		location.href=url;
	});


	// Exportable data table
	var _title = 'Daily User Activity Log';
	var _message = 'Date: {{ $date }}';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
		buttons: [
			{
				extend: 'copyHtml5',
				header:true,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'excelHtml5',
				header:true,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'pdfHtml5',
				header:true,
				message:_message,
				title:_title,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'print',
				header:true,
				message:_message,
				title:_title,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
		]
	});

});
</script>
