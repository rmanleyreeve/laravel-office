<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Reporting</h1>
	</div>
	
	<div class="page-body">
	
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
						<div class="panel-heading"><span>Amended Hours Report</span></div>
						<div class="panel-body">
						
							<div class="table-responsive">
								<table class="table table-striped table-hover js-exportable dataTable">
									<thead>
										<tr>
											<th>Employee</th>
											<th>Original Value</th>
											<th>Amended Value</th>
											<th>Reason</th>
											<th>Changed on</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach($recordset as $r) {
											?>
										<tr>
											<td><?php echo $r['firstname'];?> <?php echo $r['surname'];?></td>
											<td><?php echo $r['original_value'];?></td>
											<td><?php echo $r['time_logged'];?></td>
											<td><?php echo $r['update_reason'];?></td>
											<td><?php echo $r['updated'];?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div><!-- //table-responsive -->
							
						</div><!-- //panel-body -->
					</div><!-- //panel -->
				</div><!-- //col -->
			</div><!-- //row -->
		
	</div><!-- //page-body -->
</section><!-- //content -->

<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>

<script>
$(function(){
	
	// Exportable data table
	var _title = 'Amended Hours Report';
	var _message = '';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
		buttons: [
			{ extend: 'copyHtml5', footer: true, header:true },
			{ extend: 'excelHtml5', footer: true, header:true },
			{ extend: 'pdfHtml5', footer: true, header:true, message:_message, title:_title },			
			{ extend: 'print', footer: true, header:true, message:_message, title:_title },
		]
	});
	
});
</script>
