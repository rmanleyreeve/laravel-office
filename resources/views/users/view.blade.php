<div hidden id="dynamicModalTitle">{{ $selected->fullname }}</div>
<div class="user-image"><img class="thumbnail" src="/storage/media/user/user_{{ (file_exists(storage_path("app/public/media/user/user_{$selected->user_id}.jpg"))) ? $selected->user_id : 0 }}.jpg"></div>
<div class="table-responsive">
	<table class="table table-condensed table-bordered">
		<tr>
			<td>User Ref:&nbsp;</td>
			<td>{{ $selected->user_id }}&nbsp;</td>
		</tr>
		<tr>
			<td>Username:&nbsp;</td>
			<td>{{ $selected->username }}&nbsp;</td>
		</tr>
		<tr>
			<td>Full Name:&nbsp;</td>
			<td>{{ $selected->fullname }}&nbsp;</td>
		</tr>
		<tr>
			<td>Email:&nbsp;</td>
			<td><a href="mailto:{{ strtolower($selected->user_email) }}">{{ strtolower($selected->user_email) }}</a>&nbsp;</td>
		</tr>
		<tr>
			<td>Admin Permissions:&nbsp;</td>
			<td>{{ ($selected->administrator) ? 'All' : $selected->permission_names_array }}&nbsp;</td>
		</tr>
		<tr>
			<td>Active:&nbsp;</td>
			<td>{{ (true === $selected->active) ?'Yes':'No' }}&nbsp;</td>
		</tr>
		<tr>
			<td>Notes :&nbsp;</td>
			<td>{{ nl2br($selected->user_notes) }}&nbsp;</td>
		</tr>
		<tr>
			<td>Date Created:&nbsp;</td>
			<td>{{ $selected->created_at }}&nbsp;</td>
		</tr>
        <tr>
            <td>Date Updated:&nbsp;</td>
            <td>{{ $selected->updated_at }}&nbsp;</td>
        </tr>
		<tr>
			<td>Last Login:&nbsp;</td>
			<td>{{ $selected->last_login }}&nbsp;</td>
		</tr>
	</table>
</div><!-- //table-responsive -->
