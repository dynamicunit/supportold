<?php include_once('inc_head.php') ?>

</head>

<body>
	<?php include_once('inc_header.php') ?>

	<section>
		<div class="container">
			<div class="row">
				<div class="col">
					<h1>Blog</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="blogs-suggestion">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="row justify-content-center mb-4">
						<?= $posts ?>
					</div>
					<nav aria-label="Page navigation">
						<?= $render_pagination ?>
					</nav>
				</div>
			</div>
		</div>
	</section>



	<?php include_once('inc_footer.php') ?>
</body>

</html>