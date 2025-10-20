<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/user/dashboard');
$view = new vwcommon($route[0]);



$canonical = $page->get_page_slug();
