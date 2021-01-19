<section class="content">

	<div class="page-heading">
		<h1>User Management</h1>
	</div>

	<div class="page-body">

		<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
			<div class="panel-heading"><span>List of Users</span></div>
			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-striped table-hover js-exportable dataTable">
						<thead>
							<tr>
								<th>Full Name</th>
								<th>Username</th>
								<th>Admin Permissions</th>
								<th>Last Login</th>
								<th class="no-sort no-export">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($recordset as $record)
							<tr>
								<td>{{ $record->fullname }}</td>
								<td>{{ $record->username }}</td>
								<td>{{ ($record->administrator)  ? "All" : $record->permissions }}</td>
								<td>{{ $record->last_login }}</td>
								<td>
									<div class="form-group">
										<a role="button" data-toggle="modal" data-target="#bsModal" href="/users/{{ $record->user_id }}/view" class="btn btn-xs btn-outline btn-success"><i class="fa fa-eye"></i> View</a>
										<a href="/users/{{ $record->user_id }}/edit" class="btn btn-xs btn-outline btn-warning"><i class="fa fa-edit"></i> Edit</a>
										<a href="/users/{{ $record->user_id }}/delete" class="btn btn-xs btn-outline btn-danger confirm-delete"><i class="fa fa-trash"></i> Delete</a>
										@if($funcs->_up('LOGS') && $record->activity_count>0)
										<a href="/users/{{ $record->user_id }}/activity" class="btn btn-xs btn-outline btn-info">Activity</a>
                                        @endif
									</div>
								</td>
							</tr>
                        @endforeach
						</tbody>
					</table>
				</div><!-- //table-responsive -->

			</div><!-- //panel-body -->
		</div><!-- //panel -->

	</div><!-- //page-body -->
</section><!-- //content -->

<script>
$(function(){

	// Exportable data table
	var _title = 'List of Users';
	var _message = '';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
		lengthChange:false,
		paging:false,
		buttons: [
			{
				extend: 'copyHtml5',
				header:true,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'excelHtml5',
				header:true,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'pdfHtml5',
				header:true,
				message:_message,
				title:_title,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
			{
				extend: 'print',
				header:true,
				message:_message,
				title:_title,
				exportOptions: {columns: $("th:not('.no-export')")}
			},
		]
	});

});
</script>
