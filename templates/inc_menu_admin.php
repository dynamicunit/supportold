<div class="d-flex order-lg-1">
	<!-- <div class="d-flex align-items-center me-4">
		<a href="<?= $baseurl ?>/user/inbox" class="text-decoration-none position-relative me-3">
		<i class="ft-mail fs-5"></i>

			<span id="unreadMessagesBadge"
				class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark bg-warning">
				0<span class="visually-hidden">unread messages</span> </span>

		</a>
		<a href="<?= $baseurl ?>/user/notifications" class="text-decoration-none  position-relative">
		<i class="ft-bell fs-5"></i>

			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-dark bg-warning">
				3
				<span class="visually-hidden">unread messages</span>
			</span>
		</a>
	</div> -->

	<div class="dropdown text-end">
		<a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
			aria-expanded="false">
			<?= $user->get_loggeduser()->profile_image ? '<img src="' . $user->get_loggeduser()->profile_image . '" alt="' . $user->get_loggeduser()->full_name . '" width="32" height="32" class="rounded-circle">' :
				'<span width="32" height="32" class="rounded-circle border p-2">' . strtoupper(substr($user->get_loggeduser()->full_name, 0, 2)) . '</span>' ?>
		</a>
		<ul class="dropdown-menu dropdown-menu-end text-small">
			<li><a class="dropdown-item" href="<?= $baseurl ?>/admin/edit-user">Profile Settings</a></li>
			<li><a class="dropdown-item" href="<?= $baseurl ?>/admin/change-password">Change Password</a></li>
			<li>
				<hr class="dropdown-divider">
			</li>
			<li><a class="dropdown-item" href="<?= $baseurl ?>/sign-out">Sign-out</a></li>
		</ul>
	</div>
</div>

<div class="collapse navbar-collapse" id="navbarNav">
	<ul class="navbar-nav ml-auto">
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/dashboard">Home</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/tickets">Tickets</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/workshops">Workshops</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/reports">Reports</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/companies">Companies</a></li>
		<li class="nav-item"><a class="nav-link" href="<?= $baseurl ?>/admin/sla-policies">Sla Policies</a></li>

		<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="dropdown01"
				data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Website Settings</a>
			<div class="dropdown-menu" aria-labelledby="dropdown01">
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/blog-posts">Blog
					Posts</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/users">Users</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/blog-categories">Categories</a> <a
					class="dropdown-item" href="<?= $baseurl ?>/admin/configurations">Configurations</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/pages">Pages</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/email-templates">Email Templates</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/entities">Entities</a>
				<a class="dropdown-item" href="<?= $baseurl ?>/admin/lookup-codes">Lookups</a>
			</div>
		</li>
	</ul>
</div>