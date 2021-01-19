<div hidden id="dynamicModalTitle"><?php echo $selected['fullname'];?></div>

<div class="user-image"><img class="thumbnail" src="/media/user/user_<?php echo file_exists("media/user/user_{$selected['user_id']}.jpg")?$selected['user_id']:0;?>.jpg"></div>
<div class="table-responsive">
	<table class="table table-condensed table-bordered">
		<tr>
			<td>User Ref:&nbsp;</td>
			<td><?php echo $selected['user_id'];?>&nbsp;</td>
		</tr>
		<tr>
			<td>Username:&nbsp;</td>
			<td><?php echo $selected['username'];?>&nbsp;</td>
		</tr>
		<tr>
			<td>Full Name:&nbsp;</td>
			<td><?php echo $selected['fullname'];?>&nbsp;</td>
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
			<td>Active:&nbsp;</td>
			<td><?php echo ($selected['active']==TRUE)?'Yes':'No';?>&nbsp;</td>
		</tr>
		<tr>
			<td>Notes :&nbsp;</td>
			<td><?php echo nl2br($selected['user_notes']);?>&nbsp;</td>
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