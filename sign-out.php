<?php
require(__DIR__ . '/core/init.php');

$user = new users();

$user->logout();
redirect::to($baseurl . '/sign-in');

?>