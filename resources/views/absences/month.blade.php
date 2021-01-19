<section class="content">

	<div class="page-heading">
		<h1>Absence Calendar</h1>
	</div>

	<div class="page-body">

		<div class="row clearfix">
			<!-- Calendar -->
			<div class="col-xs-12">
				<div class="panel panel-default" data-panel-close="false" data-panel-fullscreen="true" data-panel-collapsable="true">
					<div class="panel-body">
						<div id="calendar"></div>
					</div>
				</div>
			</div>
			<!-- #END# Calendar -->
		</div>

	</div>
</section>

<script src="/assets/plugins/moment/moment.js"></script>
<script src="/assets/plugins/fullcalendar/dist/fullcalendar.min.js"></script>
<script>

$(function () {

	$('#calendar').fullCalendar({
		events:  {!! json_encode($events) !!},
		defaultView: 'month',
		dayOfMonthFormat: 'ddd D MMM',
		weekends: false,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		eventRender: function(event, element) {
			element.tooltip({
				title: event.info,
				container: 'body',
				trigger : 'hover'
			});
		},
		firstDay: 1,
		showNonCurrentDates: false,
		editable: false,
		droppable: false,
		contentHeight: "auto",
	});

});
</script>

