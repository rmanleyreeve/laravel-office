<section class="content">

	<div class="page-heading">
		<h1>Employee Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span>{{ $action }} Employee</span></div>
					<div class="panel-body">

						<form class="form-horizontal validate" id="formAddEdit" method="post" enctype="multipart/form-data">
                            @csrf
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">First Name:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="firstname" id="firstname" value="{{ trim($selected->firstname) }}" required />
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Surname:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="surname" id="surname" value="{{ trim($selected->surname) }}" required />
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Role:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="role" id="role" value="{{ trim($selected->role) }}" required />
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Initials:</label>
								<div class="col-sm-2">
									<input class="form-control input-sm" name="initials" id="initials" value="{{ strtoupper(trim($selected->initials)) }}" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="email" id="email" value="{{ trim($selected->email) }}" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Notes:</label>
								<div class="col-sm-9">
									<textarea rows="2" class="form-control no-resize auto-growth" name="notes" id="notes">{{ $selected->notes }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Image:</label>
								<div class="col-sm-9">
									@if($selected) <div class="user-image"><img src="/employees/{{ $selected->uid }}/image"></div> @endif
									<input type="file" class="form-control input-sm" name="image" id="image" />
								</div>
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Access Fob Code:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="rfid_code" id="rfid_code" value="{{ trim($selected->rfid_code) }}" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Active:</label>
								<div class="col-sm-9">
									<input type="hidden" name="active" value="0">
									<input<?php $utils->radio([NULL,TRUE],$selected->active); ?> type="checkbox" class="js-switch" data-size="small" data-switchery="true" name="active" value="1">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
									<a href="/employees" class="m-w-100 m-l-30 btn btn-sm btn-outline btn-warning">Cancel</a>
								</div>
							</div>
						</form>

					</div><!-- //panel-body -->
				</div><!-- //panel -->

			</div><!-- //col -->
		</div><!-- //row -->

	</div><!-- //page-body -->
</section><!-- //content -->

<script src="/assets/plugins/autosize/dist/autosize.js"></script>
<script>
$(function(){

	//Textarea auto growth
	autosize($('.auto-growth'));

});
</script>
