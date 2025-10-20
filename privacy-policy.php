<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/privacy-policy');
$view = new vwcommon($route[0]);


$canonical = $baseurl . $page->get_page_slug();
