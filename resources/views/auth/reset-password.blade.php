<?php if(!@$alert){ @$alert = $_SESSION['alert']; } ?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>RE Media BackOffice</title>
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
			<div class="signin-top-info">Please enter your new password.</div>
			<div class="row padding-15">
				<div class="col-sm-3 col-md-4 col-lg-5"></div>
				<div class="col-sm-6 col-md-4 col-lg-2">
					<form id="frmReset" method="post" action="/reset">
						<div class="form-group password-meter" style="background-color: #fff;">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" class="form-control" placeholder="Password" minlength="4" name="Password" id="Password" required />
							</div>
							<div class="meter"></div>
							<span class="strength-text"></span>
						</div>
						<br>
						<div class="form-group has-feedback">
							<div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="password" class="form-control" placeholder="Confirm Password" minlength="4" name="ConfirmPassword" id="ConfirmPassword" required />
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
							</div>
						</div>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
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

			<?php if($alert) { ?>
			// notifications
			$('#toast-container').remove();
			toastr.options = { <?php echo toastr_options(); ?> };
			toastr['<?php echo $alert['type']; ?>']("<?php echo $alert['msg']; ?>", "<?php echo strtoupper($alert['type']); ?>");
			<?php unset($alert); unset($_SESSION['alert']); } ?>			
			//jQuery validation
			$('#frmReset').validate({
				rules: {
					'Password': {
						required: true
					},
					'ConfirmPassword': {
						equalTo: '#Password'
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
			$('#Password').on('propertychange change keyup paste input', function () {
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
