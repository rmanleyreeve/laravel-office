<section class="content">

	<div class="page-heading">
		<h1>Employee Absence Management</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">

			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><span>Employee Absences (from {{ date('l j F Y',strtotime($start_date)) }} forward)</span></div>
					<div class="panel-body">

						<div class="table-responsive">
							<table class="table table-striped table-hover js-exportable dataTable">
								<thead>
									<tr>
										@foreach(array_keys($recordset) as $n)
											<th>{{ $n }}</th>
                                        @endforeach
									</tr>
								</thead>
								<tbody>
									<tr>
										@foreach($recordset as $data)
											<td>
                                                <ul class="sub-list">
												@foreach($data as $d)
													<li class="absence cal-{{ strtolower($d['type']) }}">
														{{ date('D j M Y',strtotime($d['date'])) }} : {{ $utils->hrname($d['duration']) }}
														@if($d['notes']) &nbsp; (<em><small>{{ $d['notes'] }}</small></em>) @endif
														<a data-toggle="tooltip" data-placement="top" title="Delete" class="confirm-delete" href="/absences/{{ $d['absence_id'] }}/delete"><i class="fa fa-times"></i></a>
														<a role="button" data-toggle="modal" data-target="#bsModal" data-toggle="tooltip" data-placement="top" title="Edit" href="/absences/{{ $d['absence_id'] }}/edit"><i class="fa fa-edit"></i></a>
														<span class="icon"></span>
													</li>
                                                @endforeach
												</ul>
                                            </td>
                                        @endforeach
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
	var _message = 'from {{ date('l j F Y',strtotime($start_date)) }} forward';
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
