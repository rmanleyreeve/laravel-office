<section class="content">

	<div class="page-heading">
		<h1>Employee Absence Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Add Absence</span></div>
					<div class="panel-body">

						<form class="form-horizontal validate" id="formAddEdit" method="post">
                            @csrf
							<div class="form-group" has-feedback>
								<label class="col-sm-2 control-label">Employee:</label>
								<div class="col-sm-6">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
										<select class="form-control input-sm" name="employee_fk" id="employee_fk" required>
											<option value="">-- Please select --</option>
											@foreach($employees as $e)
												<option value="{{ $e->uid }}">{{ $e->firstname }} {{ $e->surname }}</option>
                                            @endforeach
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Type:</label>
								<div class="col-sm-6">
									<label class="radio-inline" for="absence_type_holiday">
										<input type="radio" name="absence_type" id="absence_type_holiday" value="HOLIDAY" checked> Holiday
										</label>
									<label class="radio-inline" for="absence_type_sickness">
									<input type="radio" name="absence_type" id="absence_type_sickness" value="SICKNESS"> Sickness
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Notes:</label>
								<div class="col-sm-6">
									<textarea rows="2" class="form-control no-resize auto-growth" name="notes" id="notes"></textarea>
								</div>
							</div>
							<hr class="divider">
							<ul class="nav nav-tabs">
								<li role="presentation" class="active"><a href="#single" data-toggle="tab" id="clear-range">Single Date</a></li>
								<li role="presentation"><a href="#range" data-toggle="tab" id="clear-single">Date Range</a></li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="single">
									<div class="form-group has-feedback">
										<label class="col-sm-2 control-label">Date:</label>
										<div class="col-sm-4">
											<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control input-sm datepicker" name="absence_date" id="absence_date" value="{{ date('Y-m-d') }}" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Duration:</label>
										<div class="col-sm-4">
											<select class="form-control input-sm" name="duration" id="duration" required>
												@foreach($durations as $e)
													<option value="{{ $e }}">{{ $utils->hrname($e) }}</option>
                                                @endforeach
											</select>
										</div>
									</div>

								</div>
								<div role="tabpanel" class="tab-pane fade in" id="range">
									<div class="form-group has-feedback">
										<label class="col-sm-2 control-label">Date From:</label>
										<div class="col-sm-4">
											<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control input-sm datepicker" name="start_date" id="start_date" value="" />
											</div>
										</div>
									</div>
									<div class="form-group has-feedback">
										<label class="col-sm-2 control-label">Date To:</label>
										<div class="col-sm-4">
											<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input class="form-control input-sm datepicker" name="end_date" id="end_date" value="" />
											</div>
										</div>
										<div class="col-sm-4"><em>(select first day back)</em></div>
									</div>
								</div>
							</div>
							<hr class="divider">
							<div class="form-group">
								<div class="col-sm-offset-2 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
									<a href="/absences" class="m-w-100 m-l-30 btn btn-sm btn-outline btn-warning">Cancel</a>
								</div>
							</div>
						</form>

					</div><!-- //panel-body -->
				</div><!-- //panel -->

			</div><!-- //col -->
		</div><!-- //row -->



	</div>

</section>

<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>
<script src="/assets/plugins/autosize/dist/autosize.js"></script>
<script>
$(function(){

	//Textarea auto growth
	autosize($('.auto-growth'));

	moment.updateLocale('en', {
		week: { dow: 1 } // Monday is the first day of the week
	});

	$('#start_date,#absence_date').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: false,
		useCurrent: false,
		daysOfWeekDisabled: [0,6]
	});
	$('#end_date').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: false,
		useCurrent: false,
		daysOfWeekDisabled: [0,6]
	});
	$("#start_date").on("dp.change", function (e) {
		$('#end_date').data("DateTimePicker").minDate(e.date);
	});
	$("#end_date").on("dp.change", function (e) {
		$('#start_date').data("DateTimePicker").maxDate(e.date);
	});

	$('#clear-range').on('click',function(){
		$('#start_date, #end_date').val('');
	});
	$('#clear-single').on('click',function(){
		$('#absence_date').val('');
	});


});
</script>
