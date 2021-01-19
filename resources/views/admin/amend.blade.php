<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>Amend Attendance: {{ $selected->firstname }} {{ $selected->surname }}, {{ $amend_date }}</span></div>
					<div class="panel-body">

						<form class="form-horizontal validate" id="formAddEdit" method="post" action="/admin/execute-amend-attendance">
                            @csrf
							<input type="hidden" name="day" value="{{ $amend_date }}">
							@foreach($recordset as $record)
								<div class="form-group has-feedback">
									<label class="col-sm-2 control-label">{{ $record->activity }}</label>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<input @if($record->updated) disabled @endif class="form-control input-sm datetimepicker" name="changes[{{ $record->uid }}]" data-uid="{{ $record->uid }}" value="{{ $record->clock_time }}" data-original_value="{{ $record->clock_time }}" required />
										</div>
									</div>
									<label class="col-sm-2 control-label">Reason for change:</label>
									<div class="col-sm-5">
										<input @if($record->updated) disabled @endif class="form-control input-sm" name="reason[{{ $record->uid }}]" id="reason-{{ $record->uid }}" value="{{ $record->update_reason }}" />
									</div>
								</div>
                            @endforeach
							<div class="form-group">
								<div class="col-sm-offset-2 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Save</button>
									<a href="/admin/amend-attendance" class="m-w-100 m-l-30 btn btn-sm btn-outline btn-warning">Cancel</a>
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

	$('#formAddEdit').on('submit',function(e){
		var changed = false;
		$('.datetimepicker').each(function(i,e) {
			if( $(this).val() == $(this).data('original_value') ) {
				$('#reason-'+$(this).data('uid')).attr('required',false);
			} else {
				changed = true;
				$('#reason-'+$(this).data('uid')).attr('required',true);
				return;
			}
		});
		if(!changed) {
			bootbox.alert("Nothing was changed!"); return false;
		} else {
			return true;
		}
	});

});
</script>
