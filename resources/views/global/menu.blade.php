			<!-- Left Menu -->
			<aside class="sidebar">
				<nav class="sidebar-nav">
					<ul class="metismenu" id="main-menu">

						<li class="title">MAIN MENU</li>
                        @if(Session::get('user_id'))

						<li>
							<a href="/dashboard">
								<i class="material-icons col-menu">view_quilt</i>
								<span class="nav-label">Dashboard</span>
							</a>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-menu">event_available</i>
								<span class="nav-label">Attendance</span>
							</a>
							<ul>
								<li><a href="/attendance/week/<?php echo date('o/W');?>">Weekly Attendance</a></li>
								<li><a href="/attendance/month/<?php echo date('Y/m');?>">Monthly Attendance</a></li>
							</ul>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-menu">assignment</i>
								<span class="nav-label">Admin</span>
							</a>
							<ul>
								<li><a href="/admin/amend-attendance">Amend Attendance</a></li>
								<li><a href="/admin/check-attendance">Attendance Error Check</a></li>
								<li><a href="/admin/manual">Manual Entry</a></li>
							</ul>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-menu">insert_chart_outlined</i>
								<span class="nav-label">Reports</span>
							</a>
							<ul>
								<li><a href="/reports/overall">Overall Report</a></li>
								<li><a href="/reports/individual">Individual Report</a></li>
								<li><a href="/reports/amended">Amended Hours</a></li>
							</ul>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-menu">people</i>
								<span class="nav-label">Employees</span>
							</a>
							<ul>
								<li><a href="/employees">List Employees</a></li>
								<li><a href="/employees/add">Add Employee</a></li>
								<li><a href="/employees/RE_Employee_Export.csv" data-href="/employees/RE_Employee_Export.csv" class="add-date">Export Employee List</a></li>
							</ul>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-menu">exit_to_app</i>
								<span class="nav-label">Absences</span>
							</a>
							<ul>
								<li><a href="/absences/calendar">Absence Calendar</a></li>
								<li><a href="/absences">List Absences</a></li>
								<li><a href="/absences/add">Add Absence</a></li>
								<li><a href="/absences/RE_Employee_Absence_Export.csv" data-href="/absences/RE_Employee_Absence_Export.csv" class="add-date">Export Absences List</a></li>
							</ul>
						</li>

						<li>
							<a href="#" class="menu-toggle">
								<i class="material-icons col-warning">settings</i>
								<span class="nav-label">System</span>
							</a>
							<ul>
								<li><a href="/users">List Users</a></li>
								<li><a href="/users/add">Add User</a></li>
								<li><a href="/users/activity/date/<?php echo date('Y-m-d');?>">Daily Activity Log</a></li>
								<li><a href="/users/activity/export/RE_Media_User_Activity Log_Export.csv" data-href="/users/activity/export/RE_Media_User_Activity Log_Export.csv" class="add-date">Export Full Activity Log</a></li>
							</ul>
						</li>

						<li>
							<a href="/logout" class="confirm-logoff">
								<i class="material-icons col-danger">power_settings_new</i>
								<span class="nav-label">Log Out</span>
							</a>
						</li>
                        @endif
					</ul>
				</nav>
			</aside>
			<!-- #END# Left Menu -->
