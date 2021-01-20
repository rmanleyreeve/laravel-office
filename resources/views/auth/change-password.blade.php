@if(!isset($alert)) <?php $alert = Session::get('alert'); ?> @endif
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>RMR BackOffice</title>
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href="/assets/css/fonts.css" rel="stylesheet"/>
		<link href="/assets/plugins/bootstrap/dist/css/bootstrap.css" rel="stylesheet"/>
		<link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/toastr/toastr.css" rel="stylesheet" />
		<link href="/assets/css/app.css" rel="stylesheet"/>
	</head>
	<body class="sign-in-page">
		<div class="signin-form-area">
			<h1 class="text-center"><img src="/assets/images/logo-trans.png"></h1>
			<div class="row padding-15">
				<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
					<form id="formChangePW" method="post" action="">
                        @csrf
						<div class="signin-top-info">Please enter your current password:</div>
						<div class="form-group" style="background-color: #fff;">
							<input type="password" class="form-control" placeholder="Current Password" minlength="4" name="oldPassword" id="oldPassword" required />
						</div>

						<div class="signin-top-info">Please enter your new password:</div>
						<div class="form-group password-meter" style="background-color: #fff;">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" class="form-control" placeholder="New Password" minlength="4" name="newPassword" id="newPassword" required />
							</div>
							<div class="meter"></div>
							<span class="strength-text"></span>
						</div>
						<div class="form-group has-feedback">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" class="form-control" placeholder="Confirm New Password" minlength="4" name="newPasswordConfirm" id="newPasswordConfirm" required />
							</div>
						</div>

						<div class="form-group m-t-30 text-center">
							<div class="col-xs-12">
								<button type="submit" class="m-w-100 m-l-30 btn btn-primary btn-inline btn-flat">Submit</button>
								<a href="/dashboard" class="m-w-100 m-l-30 btn btn-warning btn-inline btn-flat">Cancel</a>
							</div>
						</div>


					</form>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-4"></div>
			</div>
		</div>
		<div class="signin-bottom-info"></div>

		<script src="/assets/plugins/jquery/dist/jquery.min.js"></script>
		<script src="/assets/plugins/bootstrap/dist/js/bootstrap.js"></script>
		<script src="/assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
    <script src="/assets/plugins/zxcvbn/dist/zxcvbn.js"></script>
    <script src="/assets/plugins/toastr/toastr.js"></script>
		<script>
        $(function () {

            @if($alert)
            // notifications
            $('#toast-container').remove();
            toastr.options = {  {!! \App\Providers\AppFuncsProvider::toastr_options() !!} };
            toastr['{{ $alert['type'] }}']("{{ $alert['msg'] }}", "{{ strtoupper($alert['type']) }}");
            {{ $alert = NULL }} {{ Session::forget('alert') }} @endif
			//jQuery validation
			$('#formChangePW').validate({
				rules: {
					'newPassword': {
						required: true
					},
					'newPasswordConfirm': {
						equalTo: '#newPassword'
					}
				},
				highlight: function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorPlacement: function (error, element) {
					element.parents('.form-group').append(error);
				}
			});
			//tooltip init
			$('[data-toggle="tooltip"]').tooltip({
				container: 'body',
				trigger : 'hover'
			});
			// password strength meter
			$('#newPassword').on('propertychange change keyup paste input', function () {
				var $this = $(this).val();
				var paswScore = zxcvbn($this)['score'];
				var updateMeter = function (width, background, text) {
					$('.password-meter .meter').css({ 'width': 'calc(' + width + ' - 4px)', 'background-color': background });
					$('.strength-text').text('Strength: ' + text);
				}
				if (paswScore === 0) if ($this.length === 0) { updateMeter('0%', '#ffa0a0', 'None'); } else { updateMeter('20%', '#ffa0a0', 'Very Weak'); }
				if (paswScore === 1) updateMeter('40%', '#ffb78c', 'Weak');
				if (paswScore === 2) updateMeter('60%', '#ffec8b', 'Medium');
				if (paswScore === 3) updateMeter('80%', '#c3ff88', 'Strong');
				if (paswScore === 4) updateMeter('100%', '#ACE872', 'Very Strong');
			});

		});
		</script>
	</body>
</html>
