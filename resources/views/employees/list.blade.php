<section class="content">

	<div class="page-heading">
		<h1>Employee Management</h1>
	</div>

	<div class="page-body">

		<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
			<div class="panel-heading"><span>List of Employees</span></div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover js-exportable dataTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th class="no-sort">Role</th>
								<th>Active</th>
								<th class="no-sort no-export">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($recordset as $record)
							<tr>
								<td>{{ $record->uid }}</td>
								<td>{{ $record->firstname }} {{ $record->surname }}</td>
								<td>{{ $record->role }}</td>
								<td>@if($record->active) Y @else N @endif</td>
								<td>
									<div class="form-group">
										<a role="button" data-toggle="modal" data-target="#bsModal" href="/employees/{{ $record->uid }}/view" class="btn btn-xs btn-outline btn-success"><i class="fa fa-eye"></i> View</a>
										<a href="/employees/{{ $record->uid }}/edit" class="btn btn-xs btn-outline btn-warning"><i class="fa fa-edit"></i> Edit</a>
										<a href="/employees/{{ $record->uid }}/delete" class="btn btn-xs btn-outline btn-danger confirm-delete"><i class="fa fa-trash"></i> Delete</a>
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
$(function () {

    // Exportable data table
    var _title = 'List of Employees';
    var _message = '';
    $('.js-exportable').DataTable({
        aaSorting: [],
        lengthChange: false,
        paging: false,
        buttons: [
            {
                extend: 'copyHtml5',
                header: true,
                exportOptions: {columns: $("th:not('.no-export')")}
            },
            {
                extend: 'excelHtml5',
                header: true,
                exportOptions: {columns: $("th:not('.no-export')")}
            },
            {
                extend: 'pdfHtml5',
                header: true,
                message: _message,
                title: _title,
                exportOptions: {columns: $("th:not('.no-export')")}
            },
            {
                extend: 'print',
                header: true,
                message: _message,
                title: _title,
                exportOptions: {columns: $("th:not('.no-export')")}
            },
        ]
    });

})
</script>
