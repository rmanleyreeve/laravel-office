<section class="content">

	<?php
	if($selected['user_id']) {
		if($selected['administrator']) {
			foreach($user_permissions as $p) {
				$sel_user_permissions[] = $p['id'];
			}		
		} else {
			$sel_user_permissions = explode(',',$selected['permissions']);
		}
	}
	//pp($sel_user_permissions);
	?>

	<div class="page-heading">
		<h1>User Management</h1>
	</div>
	
	<div class="page-body">
	
		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span><?php echo $action;?> User</span></div>
					<div class="panel-body">
		
						<form class="form-horizontal validate" id="formAddEdit" method="post" enctype="multipart/form-data">
						
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Username:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="username" id="username" value="<?php echo trim($selected['username']);?>" required />
									<input type="hidden" name="original_username" id="original_username" value="<?php echo trim($selected['username']);?>" />
								</div>
							</div>
							<div class="form-group has-feedback">
							<?php if('Edit'==$action) { ?>
								<label class="col-sm-3 control-label">New Password:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" autocomplete="new-password" type="password" name="password" id="password" placeholder="Leave blank to keep current password" />
								</div>
							<?php } else { ?>
								<label class="col-sm-3 control-label">Password:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" autocomplete="new-password" type="password" name="password" id="password" required />
								</div>
							<?php } ?>								
							</div>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label">Full Name:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="fullname" id="fullname" value="<?php echo trim($selected['fullname']);?>" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email:</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="user_email" id="user_email" value="<?php echo trim($selected['user_email']);?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Administrator:</label>
								<div class="col-sm-9">
									<input type="hidden" name="administrator" value="0">
									<input<?php radio(TRUE,$selected['administrator']);?> type="checkbox" class="js-switch" data-switchery="true" data-size="small" name="administrator" id="administrator" value="1">
								</div>
							</div>						
							<div class="form-group" has-feedback>
								<label class="col-sm-3 control-label">Admin Permissions:</label>
								<div class="col-sm-9">
									<select class="form-control" name="permission_fk[]" id="permission_fk" multiple="multiple" required>
										<?php foreach($user_permissions as $up) { ?>
											<option value="<?php echo $up['id'];?>"<?php selectmulti($up['id'],$sel_user_permissions);?>><?php echo $up['permission_name'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Active:</label>
								<div class="col-sm-9">
									<input type="hidden" name="active" value="0">
									<input<?php radio([NULL,TRUE],$selected['active']);?> type="checkbox" class="js-switch" data-size="small" data-switchery="true" name="active" value="1">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Notes:</label>
								<div class="col-sm-9">
									<textarea rows="3" class="form-control no-resize auto-growth" name="user_notes" id="user_notes"><?php echo $selected['user_notes'];?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Image:</label>
								<div class="col-sm-9">
									<?php if($selected['user_id'] && file_exists("media/user/user_{$selected['user_id']}.jpg")){ ?><div class="user-image"><img src="/media/user/user_<?php echo $selected['user_id'] ?? 0;?>.jpg"></div><?php } ?>
									<input type="file" class="form-control input-sm" name="user_image" id="user_image" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 p-l-15">
									<button type="submit" class="m-w-100 btn btn-sm btn-primary" id="btn-add">Save</button>
									<a href="/users" class="m-w-100 m-l-30 btn btn-sm btn-outline btn-warning">Cancel</a>
								</div>
							</div>
						</form>
		
					</div><!-- //panel-body -->
				</div><!-- //panel -->
				
			</div><!-- //col -->
		</div><!-- //row -->
		
	</div><!-- //page-body -->
</section><!-- //content -->

<link href="/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" />
<script src="/assets/plugins/multiselect/js/jquery.multi-select.js"></script>


<script>
var edit = <?php echo ($selected['user_id']) ? 'true' : 'false'; ?>;
var unsaved = false;
$(":input").change(function(){ 
	unsaved = true;
});
function unloadPage(){ 
	if(edit && unsaved){
		var message = "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
		e.returnValue = message; // Cross-browser compatibility (src: MDN)
		return message;
	}
}
window.onbeforeunload = unloadPage;
</script>

<script>
$(function(){

	//Multi-select
	$('#permission_fk').multiSelect({});

	$('#btn-add').on('click',function(){
		unsaved = false;
	});

	<?php if($selected['administrator']) { ?>
		$('#permission_fk').prop('disabled',true);
		$('#permission_fk').multiSelect('select_all');
		$('#permission_fk').multiSelect('refresh');
	<?php } ?>
	
	$('#administrator').on('change',function(){
		if($(this).is(':checked')) {
			$('#permission_fk').multiSelect('select_all');
			$('#permission_fk').prop('disabled',true);
		} else {
			$('#permission_fk').prop('disabled',false);
			$('#permission_fk option').prop('selected',false);
		}
		$('#permission_fk').multiSelect('refresh');
		
	});

});
</script>
