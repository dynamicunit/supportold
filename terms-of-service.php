<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/terms-of-service');
$view = new vwcommon($route[0]);


$canonical = $baseurl . $page->get_page_slug();
