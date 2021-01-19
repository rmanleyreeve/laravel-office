<section class="content">
	<?php //pp($recordset); exit; ?>

	<div class="page-heading">
		<h1>Employee Absence Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">

			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><span>Employee Absences (from <?php echo date('l j F Y',strtotime($start_date));?> forward)</span></div>
					<div class="panel-body">
					
						<div class="table-responsive">
							<table class="table table-striped table-hover js-exportable dataTable">
								<thead>
									<tr>
										<?php foreach(array_keys($recordset) as $n) { ?>
											<th><?php echo $n; ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<?php foreach($recordset as $data) { ?>
											<td><ul class="sub-list">
												<?php foreach($data as $d) { ?>
													<li class="absence cal-<?php echo strtolower($d['type']);?>">
														<?php echo date('D j M Y',strtotime($d['date'])); ?> : <?php echo hrname($d['duration']);?>
														<?php if($d['notes']){ ?> &nbsp; (<em><small><?php echo $d['notes'];?></small></em>)<?php } ?>
														<a data-toggle="tooltip" data-placement="top" title="Delete" class="confirm-delete" href="/absences/<?php echo $d['absence_id'];?>/delete"><i class="fa fa-times"></i></a>
														<a role="button" data-toggle="modal" data-target="#bsModal" data-toggle="tooltip" data-placement="top" title="Edit" href="/absences/<?php echo $d['absence_id'];?>/edit"><i class="fa fa-edit"></i></a>
														<span class="icon"></span>
													</li>
												<?php } ?>
												</ul></td>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						</div><!-- //table-responsive -->
						
					</div><!-- //panel-body -->
				</div><!-- //panel -->
			</div><!-- //col -->
		</div><!-- //row -->

			
	</div>
</section>

<script>

$(function () {
	"use strict"
		
	// Exportable data table
	var _title = 'Employee Absences';
	var _message = 'from <?php echo date('l j F Y',strtotime($start_date));?> forward';
	$('.js-exportable').DataTable({
		aaSorting: [],
		searching: false,
		ordering:false,
		lengthChange:false,
		paging:false,
		info:false,
		buttons: [
			{ extend: 'copyHtml5', footer: true, header:true },
			{ extend: 'excelHtml5', footer: true, header:true },
			{ extend: 'pdfHtml5', footer: true, header:true, message:_message, title:_title },			
			{ extend: 'print', footer: true, header:true, message:_message, title:_title },
		]
	});

	
});
</script>