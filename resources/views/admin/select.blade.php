<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Amend Employee Attendance</span></div>
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
												<option value="{{ $e->uid }}">{{ $e->firstname }} {{ $e->surname }}</option>
                                            @endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-2 control-label">Day:</label>
								<div class="col-sm-4">
									<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm datepicker" name="amend_date" id="amend_date" value="{{ date('Y-m-d') }}" required />
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
		maxDate: "{{ date('Y-m-d') }}",
		useCurrent: false
	});


});
</script>
