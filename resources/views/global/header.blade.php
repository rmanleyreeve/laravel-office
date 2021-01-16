			<!-- Top Bar -->
			<header>
				<nav class="navbar navbar-default">

					<!-- Search Bar -->
					<div class="search-bar">
						<div class="search-icon">
							<i class="material-icons">search</i>
						</div>
						<input type="text" placeholder="Start typing...">
						<div class="close-search js-close-search">
							<i class="material-icons">close</i>
						</div>
					</div>
					<!-- #END# Search Bar -->

					<div class="container-fluid">
						<div class="navbar-header">

							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
									<i class="material-icons">swap_vert</i>
							</button>
							<a href="javascript:void(0);" class="left-toggle-left-sidebar js-left-toggle-left-sidebar">
									<i class="material-icons">menu</i>
							</a>
							<!-- Logo -->
							<a class="navbar-brand" href="/">
									<span class="logo-minimized">RE</span>
									<span class="logo">RE Media BackOffice</span>
							</a>
							<!-- #END# Logo -->
						</div>
						<div class="collapse navbar-collapse" id="navbar-collapse">
							<ul class="nav navbar-nav">
								<li data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse Menu">
									<a href="javascript:void(0);" class="toggle-left-sidebar js-toggle-left-sidebar">
										<i class="material-icons">menu</i>
									</a>
								</li>
							</ul>

                            @if(Session::get('user_id'))
							<ul class="nav navbar-nav navbar-right">

								<!-- Call Search -->
								<!--
								<li data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Search">
									<a href="javascript:void(0);" class="js-search" data-close="true">
										<i class="material-icons">search</i>
									</a>
								</li>
								-->
								<!-- #END# Call Search -->

								<!-- Fullscreen Request -->
								<li data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Full Screen">
									<a href="javascript:void(0);" class="fullscreen js-fullscreen">
										<i class="material-icons">fullscreen</i>
									</a>
								</li>
								<!-- #END# Fullscreen Request -->

								<!-- Notifications -->
								<li class="dropdown notification-menu" id="header-notifications">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
										<i class="material-icons">notifications</i><span class="label-count bg-primary" style="display:none">0</span>
									</a>
									<ul class="dropdown-menu">
										<li class="header">NOTIFICATIONS</li>
										<li class="body">
											<ul class="menu" id="header-notifications-list"></ul>
										</li>
										<li class="footer">
											<a href="javascript:void(0);" id="mark-all-read" data-uids="">Mark All Notifications Read</a>
										</li>
									</ul>
								</li>
								<!-- #END# Notifications -->

								<!-- Alerts -->
								<li class="dropdown alerts-menu" id="header-alerts">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
										<i class="material-icons">flag</i><span class="label-count bg-danger" style="display:none">0</span>
									</a>
									<ul class="dropdown-menu">
										<li class="header">ALERTS</li>
										<li class="body">
											<ul class="menu" id="header-alerts-list"></ul>
										</li>
									</ul>
								</li>
								<!-- #END# Alerts -->

								<!-- User Menu -->
								<li class="dropdown user-menu">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
										<?php
                                        $img_id = file_exists(
                                            storage_path("app/public/media/user/user_".Session::get('user_id').".jpg")
                                        ) ? Session::get('user_id') : 0;
                                        ?>
										<img src="/storage/media/user/user_{{ $img_id }}.jpg?t={{ time() }}" alt="User Image" />
										<span class="hidden-xs">{{ Session::get('user.fullname') }}</span>
									</a>
									<ul class="dropdown-menu">
										<li class="header">
											<img src="/storage/media/user/user_{{ $img_id }}.jpg?t={{ time() }}" alt="User Image" />
											<div class="user">{{ Session::get('user.fullname') }}<div class="title">{{ \Illuminate\Support\Facades\Session::get('user.position') }}</div></div>
										</li>
										<li class="body">
											<ul>
												<li><a href="/users/{{ Session::get('user_id') }}/profile"><i class="material-icons">account_circle</i> Profile</a></li>
												<li><a href="/change-password"><i class="material-icons">lock_open</i> Change Password</a></li>
												<li><a href="/logout" class="confirm-logoff"><i class="material-icons">exit_to_app</i> Log Out</a></li>
											</ul>
										</li>
										<li class="footer">&nbsp;</li>
									</ul>
								</li>
								<!-- #END# User Menu -->

								<li class="pull-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Settings" style="visibility:hidden;">
									<a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a>
								</li>

							</ul>
                            @endif
						</div>
					</div>

				</nav>
			</header>
			<!-- #END# Top Bar -->
