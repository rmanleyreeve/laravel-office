<div hidden id="dynamicModalTitle">Edit Absence</div>

<form class="form-horizontal validate" id="formAddEdit" method="post" action="/absences/{{ $selected->uid }}/edit">
    @csrf
	<div class="form-group">
		<label class="col-sm-2 control-label">Type:</label>
		<div class="col-sm-6">
			<label class="radio-inline" for="absence_type_holiday">
				<input type="radio" name="absence_type" id="absence_type_holiday" value="HOLIDAY"@php $utils->radio('HOLIDAY',$selected->absence_type); @endphp> Holiday
            </label>
			<label class="radio-inline" for="absence_type_sickness">
			    <input type="radio" name="absence_type" id="absence_type_sickness" value="SICKNESS"@php $utils->radio('SICKNESS',$selected->absence_type); @endphp> Sickness
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Notes:</label>
		<div class="col-sm-6">
			<textarea rows="2" class="form-control no-resize auto-growth" name="notes" id="notes">{{ $selected->notes }}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Duration:</label>
		<div class="col-sm-4">
			<select class="form-control input-sm" name="duration" id="duration" required>
				@foreach($durations as $e)
					<option value="{{ $e }}"@php $utils->select($e,$selected->duration); @endphp>{{ $utils->hrname($e) }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 p-l-15">
			<button type="submit" class="m-w-100 btn btn-sm btn-primary">Submit</button>
		</div>
	</div>
</form>
<script src="/assets/plugins/autosize/dist/autosize.js"></script>
<script>
$(function(){

	//Textarea auto growth
	autosize($('.auto-growth'));

});
</script>
