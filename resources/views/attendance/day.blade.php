@if($recordset)
<div hidden id="dynamicModalTitle">Daily Activity: {{ $recordset[0]->firstname }} {{ $recordset[0]->surname }}, {{ date('D j M Y',strtotime($date)) }}</div>
<div class="table-responsive">
    <table class="table table-condensed table-bordered">
        @foreach($recordset as $record)
        <tr><td>{{ $record->activity }}</td><td>{{ $record->time }}</td></tr>
        @endforeach
    </table>
</div><!-- //table-responsive -->
@else
<div hidden id="dynamicModalTitle">No activity recorded for {{ date('D j M Y',strtotime($date)) }}</div>
@endif
