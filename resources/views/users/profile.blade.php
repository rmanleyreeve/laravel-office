<section class="content">
	<div class="page-heading">
		<h1>User Profile</h1>
	</div>
	<div class="page-body">

		<div class="row clearfix">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
					<div class="panel-heading"><span><{{ $selected->fullname }}</span></div>
					<div class="panel-body">

						<div data-toggle="tooltip" data-placement="right" title="Edit Profile Image" style="display: inline-block !important">
							<a role="button" data-toggle="modal" data-target="#bsModal" href="/users/{{ $selected->user_id }}/image" class="profile-image" id="edit-profile"><img class="thumbnail" src="/storage/media/user/user_{{ file_exists(storage_path("app/public/media/user/user_{$selected->user_id}.jpg")) ? $selected->user_id : 0 }}.jpg?t=<{{ time() }}"></a>
						</div>

						<div class="table-responsive">
							<table class="table table-condensed table-bordered">
								<tr>
									<td>Full Name: </td>
									<td><{{ $selected->fullname }} </td>
								</tr>
								<tr>
									<td>User ID: </td>
									<td>{{ $selected->user_id }} </td>
								</tr>
								<tr>
									<td>Username: </td>
									<td>{{ $selected->username }} </td>
								</tr>
								<tr>
									<td>Email: </td>
									<td><a href="mailto:<{{ strtolower($selected->user_email) }}">{{ strtolower($selected->user_email) }}</a> </td>
								</tr>
								<tr>
									<td>Admin Permissions: </td>
									<td>{{ ($selected->administrator) ? 'All' : $selected->permission_names_array }} </td>
								</tr>
								<tr>
									<td>Date Created: </td>
									<td><{{ $selected->created_at }} </td>
								</tr>
                                <tr>
                                    <td>Date Updated: </td>
                                    <td><{{ $selected->updated_at }} </td>
                                </tr>
								<tr>
									<td>Last Login: </td>
									<td><{{ $selected->last_login }} </td>
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
