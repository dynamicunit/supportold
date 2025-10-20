<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/inbox');
$view = new vwcommon($route[0]);

$validation_errors = array();

$user_id = $user->get_loggeduser()->id;

$chat_users = $user->get_user_list($user_id);


$canonical = $baseurl . $page->get_page_slug();
