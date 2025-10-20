<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/');
$view = new vwcommon($route[0]);
$function = new functions();
$settings = new websettings();




$canonical = $baseurl . $page->get_page_slug();
