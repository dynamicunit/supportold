<?php include_once('inc_head.php') ?>
</head>

<body class="blog-post">
	<?php include_once('inc_header.php') ?>

	<section class="blog-post-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2">
					<!-- Blog Title with Underline -->
					<h1 class="blog-title"><?= htmlspecialchars($row->blog_title) ?></h1>

					<!-- Blog Meta Info -->
					<div class="blog-meta">
						<img src="<?= !empty($row->profile_image) ? $row->profile_image : 'https://www.gravatar.com/avatar/?d=identicon'; ?>"
							alt="Author" class="author-img">
						<span class="author-name">by <?= htmlspecialchars($row->full_name ?? 'Unknown Author') ?></span> |
						<span class="blog-date"><?= date("F j, Y", strtotime($row->created_at)); ?></span>
					</div>

					<!-- Social Media Share Buttons -->
					<div class="blog-buttons">
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical) ?>" target="_blank" class="btn btn-facebook">
							<i class="ft-facebook"></i> Share
						</a>
						<a href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical) ?>&text=<?= urlencode($row->blog_title) ?>" target="_blank" class="btn btn-twitter">
							<i class="ft-twitter"></i> Tweet
						</a>
						<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonical) ?>" target="_blank" class="btn btn-linkedin">
							<i class="ft-linkedin"></i> Share
						</a>
						<a href="https://www.instagram.com/" target="_blank" class="btn btn-instagram">
							<i class="ft-instagram"></i> Instagram
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="blog-body">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div>
						<?= html_entity_decode($row->blog_body) ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="blogs-suggestion">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<h2>Blogs you may like</h2>
					<div class="row m-t-40 justify-content-center">
						<?= $blogs_list_html ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php include_once('inc_footer.php') ?>