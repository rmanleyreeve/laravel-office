@if(!isset($alert)) @php $alert = Session::get('alert'); @endphp @endif
    <!doctype html>
<html lang="en">
	<!--
	RMR BackOffice System
	(c){{ date('Y') }} by rich@rmrdigitalmedia.co.uk
	Powered by: PHP {{ phpversion() }}, Laravel {{ app()::VERSION }}
	UI: AdminBSB-Sensitive by gurayyarar, jQuery, Bootstrap 3
	-->
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
		<title>Back Office System</title>
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href="/assets/css/fonts.css" rel="stylesheet"/>
		<link href="/assets/plugins/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
		<link href="/assets/plugins/animate-css/animate.css" rel="stylesheet" />
		<link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="/assets/plugins/iCheck/skins/all.css" rel="stylesheet" />
		<link href="/assets/plugins/switchery/dist/switchery.css" rel="stylesheet" />
		<link href="/assets/plugins/metisMenu/dist/metisMenu.css" rel="stylesheet" />
		<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.css" rel="stylesheet" />
		<link href="/assets/plugins/pace/themes/white/pace-theme-flash.css" rel="stylesheet" />
		<link href="/assets/plugins/toastr/toastr.css" rel="stylesheet" />
		<link href="/assets/plugins/fullcalendar/dist/fullcalendar.css" rel="stylesheet" />
		<link href="/assets/css/app.css" rel="stylesheet" />
		<!-- Older browser support -->
		<script>(window.Promise && window.fetch) || document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=Promise%2Cfetch"><\/script>');</script>
		<!-- Jquery Core Js -->
		<script src="/assets/plugins/jquery/dist/jquery.min.js"></script>
		<!-- JQuery UI Js -->
		<script src="/assets/plugins/jquery-ui/jquery-ui.js"></script>
		<!-- Bootstrap Core Js -->
		<script src="/assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="/assets/plugins/bootbox.js/dist/bootbox.min.js"></script>
		<!-- iCheck Js -->
		<script src="/assets/plugins/iCheck/icheck.js"></script>
		<!-- Jquery Validation Js -->
		<script src="/assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
		<!-- JQuery Datatables Js -->
		<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
		<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/dataTables.buttons.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/buttons.bootstrap.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/buttons.flash.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/jszip.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/pdfmake.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/vfs_fonts.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/buttons.html5.min.js"></script>
		<script src="/assets/plugins/DataTables/extensions/export/buttons.print.min.js"></script>
		<script>
		$.extend( true, $.fn.dataTable.defaults, {
			"aaSorting": [],
			responsive: true,
			dom: '<"html5buttons"B>lTfgtip',
			columnDefs: [{ targets: 'no-sort', orderable: false }],
			lengthMenu: [[10,20,50,75,100,-1], [10,20,50,75,100,"All"]],
			pageLength : 20
		});
		</script>
        <!-- Toastr Js -->
        <script src="/assets/plugins/toastr/toastr.js"></script>
        <script>
            $(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                @if($alert)
                // notifications
                $('#toast-container').remove();
                toastr.options = {  {!! \App\Domain\AppFuncs::toastr_options() !!} };
                toastr['{{ $alert['type'] }}']("{{ $alert['msg'] }}", "{{ strtoupper($alert['type']) }}");
                {{ $alert = NULL }} {{ Session::forget('alert') }} @endif

                //set active menu from URL
                var uri = '/{{ Request::path() }}';
                var current = '{{ $menu ?? '' }}';
                $('#main-menu li a').each(function(i, e) {
                    //console.log($(this).attr('href'), uri, current);
                    if(uri == $(this).attr('href')) {
                        $(this).parents('li').addClass('active');
                        $(this).parents('ul').addClass('in');
                        $(this).parent().addClass('active');
                        return false;
                    } else if (current == $(this).attr('href')) {
                        $(this).parents('li').addClass('active');
                        $(this).parent().removeClass('active');
                        return false;
                    }
                });
            });
        </script>
	</head>

	<body class="footer-fixed theme-blue">
		<div class="all-content-wrapper">

            @include('global.header')
            @include('global.menu')
            @include('global.rightsidebar')

            <!-- Page Content -->
            @include($content)
            <!-- #END# Page Content -->
			<!-- Footer -->
			<footer>
				<div class="container-fluid">
					<div class="row clearfix">
						<div class="col-sm-6">
							Copyright &copy; {{ date('Y') }} <strong><a href="https://www.re-media.biz/" target="_blank">RE Media</a></strong>
						</div>
						<div class="col-sm-6 align-right">
							Developed by <strong><a href="https://re-media.biz" target="_blank">RE MEDIA</a></strong> {{ date('Y') }}
						</div>
					</div>
				</div>
			</footer>
			<!-- #END# Footer -->

		</div><!-- //all-content-wrapper -->

		<!-- Modal -->
		<div class="modal fade" id="bsModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
		</div>

		<!-- Pace Loader Js -->
		<script src="/assets/plugins/pace/pace.js"></script>
		<!-- Screenfull Js -->
		<script src="/assets/plugins/screenfull/src/screenfull.js"></script>
		<!-- Metis Menu Js -->
		<script src="/assets/plugins/metisMenu/dist/metisMenu.js"></script>
		<!-- Jquery Slimscroll Js -->
		<script src="/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
		<!-- Switchery Js -->
		<script src="/assets/plugins/switchery/dist/switchery.js"></script>
		<!-- Custom Js -->
		<script src="/assets/js/app.js"></script>
	</body>

</html>
