<section class="content">
	<div class="page-heading">
		<h1>User Profile</h1>
	</div>
	<div class="page-body">
		
		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span><?php echo $selected['fullname'];?></span></div>
					<div class="panel-body">

						<div data-toggle="tooltip" data-placement="right" title="Edit Profile Image" style="display: inline-block !important">
							<a role="button" data-toggle="modal" data-target="#bsModal" href="/users/<?php echo $selected['user_id'];?>/image" class="profile-image" id="edit-profile"><img class="thumbnail" src="/media/user/user_<?php echo file_exists("media/user/user_{$selected['user_id']}.jpg")?$selected['user_id']:0;?>.jpg?t=<?php echo time();?>"></a>
						</div>
						
						<div class="table-responsive">
							<table class="table table-condensed table-bordered">
								<tr>
									<td>Full Name:&nbsp;</td>
									<td><?php echo $selected['fullname'];?>&nbsp;</td>
								</tr>
								<tr>
									<td>User ID:&nbsp;</td>
									<td><?php echo $selected['user_id'];?>&nbsp;</td>
								</tr>
								<tr>
									<td>Username:&nbsp;</td>
									<td><?php echo $selected['username'];?>&nbsp;</td>
								</tr>
								<tr>
									<td>Email:&nbsp;</td>
									<td><a href="mailto:<?php echo strtolower($selected['user_email']);?>"><?php echo strtolower($selected['user_email']);?></a>&nbsp;</td>
								</tr>
								<tr>
									<td>Admin Permissions:&nbsp;</td>
									<td><?php echo ($selected['administrator'])? 'All': $selected['permissions'];?>&nbsp;</td>
								</tr>
								<tr>
									<td>Date Created:&nbsp;</td>
									<td><?php echo $selected['created'];?>&nbsp;</td>
								</tr>
								<tr>
									<td>Last Login:&nbsp;</td>
									<td><?php echo $selected['last_login'];?>&nbsp;</td>
								</tr>
							</table>  	
						</div><!-- //table-responsive -->
						
					</div><!-- //panel-body -->
				</div><!-- //panel -->
				
			</div><!-- //col -->
		</div><!-- //row -->
						

	</div>
</section>

<script>
$(function(){
	
	
	
});
</script>
