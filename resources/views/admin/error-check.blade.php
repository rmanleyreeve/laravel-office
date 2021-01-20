<section class="content">

	<div class="page-heading">
		<h1>Employee Attendance Management</h1>
	</div>

	<div class="page-body">


		<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="false" data-panel-collapsable="false">
			<div class="panel-heading"><span>Attendance Errors</span></div>
			<div class="panel-body">

			@if($recordset)

				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Full Name</th>
								<th>Day</th>
								<th>Activity</th>
								<th class="no-sort no-export">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($recordset as $name=>$days)
								@foreach($days as $day=>$activity)
								<tr>
									<td>{{ $name }}</td>
									<td>{{ $day }}</td>
									<td>
                                        @php $arr = array_values($activity); @endphp
									    @foreach($arr as $a)
										 [{{ $a['activity'] }} {{ $a['time'] }}]
                                        @endforeach
									</td>
									<td>
										<div class="form-group">
											<a href="/admin/attendance/repair/{{ $arr[0]['employee_fk'] }}/{{ $day }}" class="btn btn-xs btn-outline btn-primary"><i class="fa fa-edit"></i> Repair</a>
										</div>
									</td>
								</tr>
                                @endforeach
                            @endforeach
						</tbody>
					</table>
				</div><!-- //table-responsive -->

				@else
					<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-6"><div class="alert alert-success" role="alert">
						<i class="fa fa-fw fa-check"></i><strong>No errors found</strong>
					</div></div></div>
                @endif

			</div><!-- //panel-body -->
		</div><!-- //panel -->


	</div><!-- //page-body -->
</section><!-- //content -->

<script>
$(function(){

});
</script>
