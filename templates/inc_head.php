<?php
$page_title = $page->get_meta_title() != '' ? $page->get_meta_title() : $metatitle;
$meta_desc = $page->get_meta_desc() != '' ? $page->get_meta_desc() : $metadesc;
$meta_keywords = $page->get_meta_keywords() ? '<meta name="keywords" content="' . $page->get_meta_keywords() . '">' : '';

?>

<!doctype html>
<html lang="<?= $common->get_value('html_lang') ?>">

<head>
	<title><?= $page_title ?></title>
	<meta charset="utf-8" />
	<meta name="description" content="<?= $meta_desc ?>">
	<?= $meta_keywords ?>
	<meta name="Author" content="dynamicunit.com" />
	<link rel="canonical" href="<?= $canonical; ?>">

	<link rel="icon" href="<?= $baseurl . '/assets/imgs/' . $common->get_value('mascot_file_name') ?>"
		type="image/x-icon">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.2/feather.min.js"
		integrity="sha512-zMm7+ZQ8AZr1r3W8Z8lDATkH05QG5Gm2xc6MlsCdBz9l6oE8Y7IXByMgSm/rdRQrhuHt99HAYfMljBOEZ68q5A=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/flag-icons.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
	<link href="<?= $baseurl ?>/templates/css/main.css" rel="stylesheet">

