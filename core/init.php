<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        //'host' => '127.0.0.1:3310',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => '_2025_support_tickets'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => '604800'
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'website' => array(
         'website_url' => 'http://localhost:8080/supporttickets',
        //'website_url' => 'http://localhost/dynamicunit/supporttickets',
        'website_code' => 1
    )
);


// base classes
require __DIR__ . '/../classes/config.class.php';
require __DIR__ . '/../classes/db.class.php';
require __DIR__ . '/../classes/cookie.class.php';
require __DIR__ . '/../classes/hash.class.php';
require __DIR__ . '/../classes/input.class.php';
require __DIR__ . '/../classes/redirect.class.php';
require __DIR__ . '/../classes/session.class.php';
require __DIR__ . '/../classes/token.class.php';
require __DIR__ . '/../classes/pagination.class.php';
require __DIR__ . '/../classes/gcaptcha.class.php';
require __DIR__ . '/../classes/validate.class.php';
require __DIR__ . '/../classes/seswrapper.class.php';
require __DIR__ . '/../classes/s3bucket.class.php';
require __DIR__ . '/../classes/sanitizer.class.php';

// profile classes
require __DIR__ . '/../classes/editpictures.class.php';
// blog
require __DIR__ . '/../classes/blog.class.php';
require __DIR__ . '/../classes/vwblog.class.php';

// configuration
require __DIR__ . '/../classes/common.class.php';
require __DIR__ . '/../classes/vwcommon.class.php';

// web settings. Includes Contact, Lookup
require __DIR__ . '/../classes/websettings.class.php';

// emails 
require __DIR__ . '/../classes/email.class.php';
require __DIR__ . '/../classes/vwemail.class.php';

// listings 
require __DIR__ . '/../classes/listings.class.php';

// memberships

// pages
require __DIR__ . '/../classes/pages.class.php';

// users
require __DIR__ . '/../classes/users.class.php';

// functions
require __DIR__ . '/../classes/functions.class.php';

// autoload class
require __DIR__ . '/../vendor/autoload.php';

// check if the environment variables are set
$common = new common();


// global variables

$baseurl = config::get('website/website_url');
$basecode = config::get('website/website_code');
$errors = array();
$validation_errors = array();
$currentpageno = isset($_GET['page']) ? input::get('page') : '1';
$baseimageurl = config::get('website/website_url') . '/assets/imgs/';
