<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Manual Attendance Entry (use if card reader system is down)</span></div>
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
							<div class="form-group has-feedback">
								<label class="col-sm-2 control-label">Activity:</label>
								<div class="col-sm-6">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-question"></i></span>
										<select class="form-control input-sm" name="activity" id="activity" required>
											<option value="">-- Please select --</option>
											<option value="ENTRY">ENTRY</option>
											<option value="EXIT">EXIT</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
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

<script>
$(function(){

	$('.datepicker').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: true,
		maxDate: "<?php echo date('Y-m-d');?>",
		useCurrent: false
	});


});
</script>
