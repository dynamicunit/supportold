<div class="d-flex order-lg-1">
	<!-- <div class="d-flex align-items-center me-4">
		<a href="<?= $baseurl ?>/user/inbox" class="text-decoration-none position-relative me-3">
			<i class="fas fa-envelope text-secondary fs-5"></i>
			<span id="unreadMessagesBadge"
				class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark bg-warning">
				0 <span class="visually-hidden">unread messages</span> </span>

		</a>
		<a href="<?= $baseurl ?>/user/notifications" class="text-decoration-none  position-relative">
			<i class="fas fa-bell text-secondary fs-5"></i>
			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark bg-warning">
				3
				<span class="visually-hidden">unread messages</span>
			</span>
		</a>
	</div> -->
	<div class="dropdown menu-right">
		<a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
			aria-expanded="false">
			<?= $user->get_loggeduser()->profile_image ? '<img src="' . $user->get_loggeduser()->profile_image . '" alt="' . $user->get_loggeduser()->full_name . '" width="32" height="32" class="rounded-circle">' :
				'<span width="32" height="32" class="rounded-circle border p-2">' . strtoupper(substr($user->get_loggeduser()->full_name, 0, 2)) . '</span>' ?>
		</a>
		<ul class="dropdown-menu dropdown-menu-end text-small">
			<li><a class="dropdown-item" href="<?= $baseurl ?>/user/edit-user">Profile Settings</a></li>
			<li><a class="dropdown-item" href="<?= $baseurl ?>/user/change-password">Change Password</a></li>
			<li>
				<hr class="dropdown-divider">
			</li>
			<li><a class="dropdown-item" href="<?= $baseurl ?>/sign-out">Sign-out</a></li>
		</ul>
	</div>
</div>

<div class="collapse navbar-collapse" id="navbarNav">
	<ul class="navbar-nav ml-auto">
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/user/dashboard">Dashboard</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/user/tickets">Tickets</a></li>

	</ul>
</div>

