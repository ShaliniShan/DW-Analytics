<div class="sidebar">
	<div class="quickmenu">
		<div class="quickmenu_cont">
			<div class="quickmenu__list">
				<div class="quickmenu__item active">
					<i class="fa fa-fw fa-home"></i>
				</div>
				<div class="quickmenu__item">
					<i class="fa fa-fw fa-feed"></i>
			</div>
				<div class="quickmenu__item">
					<i class="fa fa-fw fa-cog"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="scrollable scrollbar-macosx">
		<div class="sidebar__cont">
			<div class="sidebar__menu active" style="margin-left: 0;">
				<div class="sidebar__title"></div>
				<ul class="nav nav-menu">
					<li <?php if(!isset($_GET['page'])) echo "class='active'"; ?>>
						<a href="/Organizer/">
							<div class="nav-menu__ico">
								<i class="fa fa-fw fa-star"></i>
							</div>
							<div class="nav-menu__text">
								<span>Dashboard</span>
							</div>
						</a>
					</li>
					<li <?php if(isset($_GET['page']) == 'Admin' && isset($_GET['action']) && $_GET['action'] == 'attendee' || $_GET['page'] == 'attendee') echo "class='active'"; ?>>
						<a href="/Organizer/Admin/attendee">
							<div class="nav-menu__ico">
								<i class="fa fa-fw fa-table"></i>
							</div>
							<div class="nav-menu__text">
								<span>My Events</span>
							</div>
						</a>
					</li>
					<li <?php if(isset($_GET['page']) == 'Admin' && isset($_GET['action']) && $_GET['action'] == 'attendees' || $_GET['page'] == 'attendees') echo "class='active'"; ?>>
						<a href="/Organizer/attendees/<?php echo $_SESSION['eventId']; ?>">
							<div class="nav-menu__ico">
								<i class="fa fa-smile-o"></i>
							</div>
							<div class="nav-menu__text">
								<span>Attendees</span>
							</div>
						</a>
					</li>
					<!-- <li <?php if(isset($_GET['page']) == 'Admin' && isset($_GET['action']) && $_GET['action'] == 'analytics') echo "class='active'"; ?>>
						<a href="#">
							<div class="nav-menu__ico">
								<i class="fa fa-bar-chart"></i>
							</div>
							<div class="nav-menu__text">
								<span>Analytics</span>
							</div>
							<div class="nav-menu__right">
								<i class="fa fa-fw fa-angle-right arrow"></i>
							</div>
						</a>
						<ul class="nav nav-menu__second collapse">
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Beacons">Total Interactions</a></li>
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Speakers">Speaker Ratings</a></li>
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Sessions">Session Interactions</a></li>
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Notifications">Triggered Content</a></li>
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Exhibitors-Interactions">Booth Interactions</a></li>
							<li><a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Content">Content Library</a></li>
						</ul> 
					</li> -->
				</ul>
			</div>
			<div class="sidebar__menu">
				<div class="sidebar__title">Recent Activity</div>
			</div>
			<div class="sidebar__menu">
				<div class="sidebar__title">Settings</div>
			</div>
		</div>
	</div>
</div>