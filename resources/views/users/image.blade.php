<div hidden id="dynamicModalTitle">Edit Profile Image</div>

<form class="form-horizontal validate" id="formAddEdit" method="post" enctype="multipart/form-data" action="/users/{{ $selected->user_id }}/image">
    @csrf
	<div class="form-group">
		<label class="col-sm-3 control-label">Image:</label>
		<div class="col-sm-9">
			<input type="file" class="form-control input-sm" name="user_image" id="user_image" required />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 p-l-15">
			<button type="submit" class="m-w-100 btn btn-sm btn-primary" id="btn-add">Save</button>
		</div>
	</div>
</form>
