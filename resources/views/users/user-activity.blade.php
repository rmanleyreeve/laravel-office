<section class="content">

	<div class="page-heading">
		<h1>System Management</h1>
	</div>
							
	<div class="page-body">

		<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
			<div class="panel-heading">
				<span>Activity Log for user: <strong><?php echo $recordset[0]['user'];?></strong> &nbsp;&nbsp;(<?php echo $recordset[0]['fullname'];?>)</span>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover js-exportable dataTable">
						<thead>
							<tr>
								<th>Time</th>
								<th>Activity</th>
								<th>URL</th>
								<th class="no-sort no-export">Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($recordset as $record) { ?>
							<tr>
								<td><?php echo $record['timestamp'];?></td>
								<td><?php echo $record['activity'];?></td>
								<td><?php echo $record['url'];?></td>
								<td>
									<?php if($record['hasdata']) { ?>
									<div class="form-group">
										<a role="button" data-toggle="modal" data-target="#bsModal" href="/users/activity/data/<?php echo $record['uid'];?>" class="btn btn-xs btn-outline btn-success"><i class="fa fa-eye"></i> View Data</a>
									</div>
									<?php } ?> 
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div><!-- //table-responsive -->
			</div><!-- //panel-body -->
		</div><!-- //panel -->

	</div><!-- //page-body -->
</section><!-- //content -->

<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>

<script>
$(function(){

	$('.datepicker').datetimepicker({
		format: "YYYY-MM-DD",
		showClear: true,
		useCurrent: false
	});
	$('#activity-date').on("dp.change", function (e) {
		var url = '/users/activity/date/'+e.date;
		location.href=url;
	});


	// Exportable data table
	var _title = 'User Activity Log';
	var _message = 'User: <strong><?php echo $recordset[0]['user'];?></strong> &nbsp;&nbsp;(<?php echo $recordset[0]['fullname'];?>)';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
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
