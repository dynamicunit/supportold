<?php include_once('inc_head.php') ?>
</head>

<body>
	<?php include_once('inc_header.php') ?>

	<section class="my-5">
		<div class="container d-flex justify-content-center align-items-center">
			<div class="card shadow-lg p-4 rounded" style="max-width: 400px; width: 100%;">
				<div class="card-body">
					<h1 class="text-center mb-4">Sign In</h1>
					<?= $view->page_messages($validation_errors) ?>
					<form method="post" id="login-form" action="<?= $canonical ?>" novalidate>
						<!-- Email Input -->
						<div class="form-group position-relative">
							<label for="email" class="form-label">Email Address</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-envelope"></i></span>
								<input type="email" class="form-control" id="email" name="email"
									value="<?= $_COOKIE['email'] ?? '' ?>" placeholder="Enter your email" required>
							</div>
						</div>

						<!-- Password Input -->
						<div class="form-group mt-3 position-relative">
							<label for="password" class="form-label">Password</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
								<input type="password" class="form-control" id="password" name="password"
									value="<?= $_COOKIE['password'] ?? '' ?>" placeholder="Enter your password"
									required>
							</div>
						</div>

						<!-- Hidden Token -->
						<input type="hidden" name="token" value="<?php echo token::generate(); ?>">

						<!-- Sign-in Button -->
						<div class="d-grid mt-4">
							<button type="submit" id="submit" name="submit" class="btn btn-primary">
								Sign In
							</button>
						</div>
					</form>

					<!-- Divider -->
					<hr class="my-4">

					<!-- Reset Password Link -->
					<div class="text-center">
						<a href="<?= $baseurl ?>/forgot-password" class="text-muted">Forgot Password?</a>
					</div>
				</div>
			</div>
		</div>
	</section>


	<?php include_once('inc_footer.php') ?>
	<script src="<?= $baseurl ?>/templates/js/validate.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const form = document.getElementById('login-form');
			if (form) {
				form.addEventListener('submit', (event) => {
					if (!validateForm(form)) {
						event.preventDefault();
					} else {
						form.submit();
					}
				});

				const inputs = form.querySelectorAll('input, textarea, select, file');
				inputs.forEach(input => {
					input.addEventListener('blur', () => {
						validateInput(input);
					});
				});
			}
		});
	</script>
</body>

</html>