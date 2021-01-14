@if(!@$alert) <?php $alert = Session::get('alert'); ?> @endif
<!DOCTYPE html>
<html lang="en">
	<!--
	Office Admin System
	(c)<?php echo date('Y');?> rmrdigitalmedia
	-->
	<head>
		<meta charset="utf-8"/>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Admin BackOffice</title>
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href="/assets/css/fonts.css" rel="stylesheet"/>
		<link href="/assets/plugins/bootstrap/dist/css/bootstrap.css" rel="stylesheet"/>
		<link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/toastr/toastr.css" rel="stylesheet" />
		<link href="/assets/css/app.css" rel="stylesheet"/>
	</head>
	<body class="sign-in-page">
		<div class="signin-form-area">
			<h1><img src="/assets/images/logo-trans.png"></h1>
			<div class="signin-top-info">Authorised users only.<br>Please enter your login details.</div>
			<div class="row padding-15">
				<div class="col-sm-3 col-md-4 col-lg-5"></div>
				<div class="col-sm-6 col-md-4 col-lg-2">
					<form name="login-form" id="login-form" method="POST" action="login">
                        @csrf
						<div class="form-group has-feedback">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" placeholder="Username" name="username" id="username" autocomplete="username" required/>
							</div>
						</div>
						<div class="form-group has-feedback">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="current-password" required/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<button type="submit" class="btn btn-primary btn-block btn-flat">Log In</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-4"></div>
			</div>
		</div>
		<div class="signin-bottom-info">
			<a href="forgot-password" id="forgot-password" class="pull-right">Forgot Password?
				<i class="fa fa-unlock m-l-5 font-14"></i>
			</a>
		</div>

		<script src="/assets/plugins/jquery/dist/jquery.min.js"></script>
		<script src="/assets/plugins/bootstrap/dist/js/bootstrap.js"></script>
		<script src="/assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
        <script src="/assets/plugins/toastr/toastr.js"></script>
		<!-- Custom Js -->
		<script>
		$(function () {

            @if(@$alert)
            // notifications
            $('#toast-container').remove();
            toastr.options = {  {!! \App\Providers\AppFuncsProvider::toastr_options() !!} };
            toastr['{{ $alert['type'] }}']("{{ $alert['msg'] }}", "{{ strtoupper($alert['type']) }}");
            {{ $alert = NULL }} {{ Session::forget('alert') }} @endif

			//jQuery validation
			$('#login-form').validate({
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

		});
		</script>
	</body>
</html>
