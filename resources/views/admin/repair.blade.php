<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Recorded Activity: {{ $selected->firstname }} {{ $selected->surname }}, {{ $amend_date }}</span></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								@foreach($recordset as $record)
								<tr><td>{{ $record->activity }}</td><td>{{ $record->clock_time }}</td></tr>
                                @endforeach
							</table>
						</div><!-- //table-responsive -->
					</div><!-- //panel-body -->
				</div><!-- //panel -->

			</div><!-- //col -->
		</div><!-- //row -->

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Insert Record</span></div>
					<div class="panel-body">

						<form class="form-horizontal validate" id="formAddEdit" method="post" action="">
                            @csrf
							<div class="form-group has-feedback">
								<label class="col-xs-2 col-sm-2 control-label">Activity:</label>
								<div class="col-xs-4 col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-question"></i></span>
										<select class="form-control input-sm" name="activity" id="activity">
											<option value="ENTRY">ENTRY</option>
											<option value="EXIT">EXIT</option>
										</select>
									</div>
								</div>
								<div class="col-xs-4 col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
										<input class="form-control input-sm datetimepicker" name="clock_time" id="clock_time" value="{{ date('H:i:s') }}" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
									<a href="/admin/check-attendance" class="m-w-100 m-l-30 btn btn-sm btn-outline btn-warning">Cancel</a>
								</div>
							</div>
						</form>

					</div><!-- //panel-body -->
				</div><!-- //panel -->

			</div><!-- //col -->
		</div><!-- //row -->

	</div><!-- //page-body -->
</section><!-- //content -->

<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>

<script>
$(function(){

	$('.datetimepicker').datetimepicker({
		format: "HH:mm:ss",
		showClear: true,
		useCurrent: false
	});

});
</script>
