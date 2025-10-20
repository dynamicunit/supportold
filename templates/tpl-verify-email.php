<?php include_once('inc_head.php') ?>
</head>

<body>
	<?php include_once('inc_header.php') ?>

	<section class="my-3">
		<div class="container">
			<div class="row">
				<div class="col">
					<h1 class="text-center">Verify Email</h1>
					<div class="card col-md-4 offset-md-4 my-4 py-2">
						<div class="card-body">
							<?= $confirmed_error ? '<div class="alert alert-danger">' . $confirmed_error . '</div>' : '' ?>
							<?= $view->page_messages() ?>
							<form method="post" id="ver-form" action="<?= $canonical ?>?auth=<?= input::get('auth') ?>&ar=2">
								<div class="mb-3">
									<label for="code" class="form-label">Email Verification Code</label>
									<input type="text" class="form-control" id="code" name="code" placeholder="" required>
								</div>
								<input type="hidden" name="token" value="<?= token::generate(); ?>"> <input type="submit" id="submit" name="submit" value="Submit Code" class="btn btn-primary  px-4 mt-2">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php include_once('inc_footer.php') ?>
</body>

</html>