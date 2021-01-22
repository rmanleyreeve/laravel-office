@if(!isset($alert)) @php $alert = Session::get('alert'); @endphp @endif
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>RMR BackOffice</title>
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href="/assets/css/fonts.css" rel="stylesheet"/>
		<link href="/assets/plugins/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
		<link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="/assets/plugins/toastr/toastr.css" rel="stylesheet" />
		<link href="/assets/css/app.css" rel="stylesheet" />
	</head>

	<body class="fp-page">
		<div class="fp-form-area">
			<h1><img src="/assets/images/logo-trans.png"></h1>
			<div class="fp-top-info">Please enter your username.<br>Password reset instructions will be sent to your registered email address.</div>
			<div class="row padding-15">
				<div class="col-sm-3 col-md-4 col-lg-5"></div>
				<div class="col-sm-6 col-md-4 col-lg-2">
					<form id="frmForgotPassword" method="post" action="forgot-password">
                        @csrf
						<div class="form-group has-feedback">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" placeholder="Username" name="username" id="username" required />
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-4"></div>
			</div>
		</div>
		<div class="fp-bottom-info"></div>

		<script src="/assets/plugins/jquery/dist/jquery.min.js"></script>
		<script src="/assets/plugins/bootstrap/dist/js/bootstrap.js"></script>
		<script src="/assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
    <script src="/assets/plugins/toastr/toastr.js"></script>
		<!-- Custom Js -->
		<script>
		$(function () {

			@if($alert)
			// notifications
			$('#toast-container').remove();
            toastr.options = {  {!! \App\Domain\AppFuncs::toastr_options() !!} };
            toastr['{{ $alert['type'] }}']("{{ $alert['msg'] }}", "{{ strtoupper($alert['type']) }}");
            {{ $alert = NULL }} {{ Session::forget('alert') }} @endif
			//jQuery validation
			$('#frmForgotPassword').validate({
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

		});
		</script>
	</body>
</html>
