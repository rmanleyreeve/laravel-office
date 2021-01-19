<div hidden id="dynamicModalTitle">{{ $selected->surname }}, {{ $selected->firstname }}</div>

<div class="user-image"><img class="thumbnail" src="/employees/{{ $selected->uid }}/image"></div>
<div class="table-responsive">
	<table class="table table-condensed table-bordered">
		<tr>
			<td>Employee ID:</td>
			<td>{{ $selected->uid }}</td>
		</tr>
		<tr>
			<td>First Name:</td>
			<td>{{ $utils->hl($selected->firstname) }}</td>
		</tr>
		<tr>
			<td>Surname:</td>
			<td>{{ $utils->hl($selected->surname) }}</td>
		</tr>
		<tr>
			<td>Role:</td>
			<td>{{ $utils->hl($selected->role) }}</td>
		</tr>
		<tr>
			<td>Initials:</td>
			<td>{{ strtoupper($selected->initials) }}</td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><a href="mailto:{{ strtolower($selected->email) }}">{{ strtolower($selected->email) }}</a>&nbsp;</td>
		</tr>
		<tr>
			<td>Notes :</td>
			<td>{{ nl2br($utils->hl($selected->notes)) }}</td>
		</tr>
		<tr>
			<td>Access Fob Code:</td>
			<td>{{ $selected->rfid_code }}</td>
		</tr>
		<tr>
			<td>Date Created:</td>
			<td>{{ $selected->created_at }}</td>
		</tr>
	</table>
</div><!-- //table-responsive -->
