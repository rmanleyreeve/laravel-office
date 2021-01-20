<?php
function prev_month_url($ms){
	$t = strtotime("-1 month",$ms);
	return date('Y/m', $t);
}
function next_month_url($ms){
	$t = strtotime("+1 month",$ms);
	return date('Y/m', $t);
}
?>
<section class="content">
	<div class="page-heading">
		<h1>Attendance - {{ $months[intval($month)] }} {{ $year }}</h1>
		<div class="fc fc-button-group" style="margin-left:20px;">
			<button title="Previous Month" data-month="{{ prev_month_url($monthstart) }}" type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left"><span class="fc-icon fc-icon-left-single-arrow"></span></button>
			@if((date('Y') == $year && $month != date('m')) || $year != date('Y'))
			<button title="Next Month" data-month="{{ next_month_url($monthstart) }}" type="button" class="fc-next-button fc-button fc-state-default fc-corner-right"><span class="fc-icon fc-icon-right-single-arrow"></span></button>
			<button title="Current Month" type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" onClick="window.location='/attendance/month/{{ date('Y/m') }}';">This Month</button>
            @endif
		</div>
	</div>

	<div class="page-body">

		<div class="row clearfix">

			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-heading"><span>Office Attendance: {{ date('D j M Y',$monthstart) }} - {{ date('D j M Y',$monthstop-1) }}</span></div>
					<div class="panel-body">

						<div class="table-responsive">
							<table class="table table-bordered js-exportable dataTable">
								<!-- employee names -->
								<thead>
									<tr>
										<th></th>
										@foreach(array_keys($data) as $n)
											<th>{{ $n }}</th>
                                        @endforeach
									</tr>
								</thead>
								<!-- data rows -->
								<tbody>
								@foreach($days as $d)
								    <?php $ss = (in_array(date('w',strtotime("$d $year")),array(6,0))); ?>
									<tr class="{{ $ss ? 'weekend':'' }}">
									<!-- date -->
									<th>{{ $d }}</th>
									<!-- daily records -->
									@foreach(array_keys($data) as $n)
										@isset($absences[$n][$d])
											<td class="absence">
											{{ $absences[$n][$d]['type'] }} {{ $utils->hrname($absences[$n][$d]['duration']) }}
											@if($absences[$n][$d]['notes'])<br><em><small>{{ $absences[$n][$d]['notes'] }}</small></em> @endif
											</td>
                                            @continue
                                        @endisset
									    <td>
                                            <?php
                                            //echo $n;
                                            $present = $funcs->calcMinsPresent($data[$n][$d]);
                                            $break = $funcs->calcMinsBreak($data[$n][$d]);
                                            $total = ($present + $break);
                                            $totals[$n]['total_present'] += $present;
                                            $totals[$n]['total_break'] += $break;
                                            if($total) {
                                                $hp = floor($present/60); $mp = ($present % 60);
                                                $hb = floor($break/60); $mb = ($break % 60);
                                                ?>
                                                {{ $hp }}h {{ $mp }}m / {{ $hb }}h {{ $mb }}m
                                                &nbsp;<a role="button" data-toggle="modal" data-target="#bsModal" href="/attendance/day/{{ date('Y-m-d',strtotime("$d $year")) }}/{{ $employees[$n] }}/view"><i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" title="View Activity"></i></a>
                                            <?php } ?>
										</td>
                                    @endforeach
									</tr>
                                @endforeach
								</tbody>
								<!-- employee names -->
								<thead>
									<tr>
										<th></th>
										@foreach(array_keys($data) as $n)
											<th>{{ $n }}</th>
                                        @endforeach
									</tr>
								</thead>
								<!-- totals row -->
								<tfoot>
									<tr>
										<th>Total in:<br>Total break:</th>
										@foreach(array_keys($data) as $n)
											<td>
											@if($totals[$n]['total_present'])
												{{ floor($totals[$n]['total_present']/60) }}h {{ $totals[$n]['total_present'] % 60 }}m<br>
												{{ floor($totals[$n]['total_break']/60) }}h {{ $totals[$n]['total_break'] % 60 }}m
											@endif
											</td>
                                        @endforeach
									</tr>
								</tfoot>

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

	$('.fc-prev-button, .fc-next-button').on('click',function(){
		var ym = $(this).data('month');
		window.location = '/attendance/month/' + ym;
	});

	// Exportable data table
	var _title = 'Office Attendance';
	var _message = ' {{ date('D j M Y',$monthstart) }} - {{ date('D j M Y',$monthstop-1) }}';
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
