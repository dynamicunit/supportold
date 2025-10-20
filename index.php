<?php
/**
 * Router file
 *
 * .htaccess routes all requests to this file
 *
 */

// remove ?var=val
$request_uri = strtok($_SERVER["REQUEST_URI"], '?');

// get install path
$install_dir = rtrim($_SERVER['SCRIPT_NAME'], 'index.php');

// query vars array
$route = preg_replace('~^' . $install_dir . '~', '', $request_uri);

$route = rtrim($route, '/');
$route = explode('/', $route);

// sanitize route
foreach ($route as $k => $v) {
    $route[$k] = htmlspecialchars($v, ENT_QUOTES, 'utf-8');
}

/*
 * --------------------------------------------------
 * Public
 * --------------------------------------------------
 */
if ($route[0] != 'user' && $route[0] != 'admin') {
    $valid_routes = array(
        // geneal
        'home',
        'about',
        'terms-of-service',
        'privacy-policy',
        'contact',
        '404',

        // user mgt
        'sign-in',
        'sign-out',
        'register',
        'forgot-password',
        'verify-email',
        'report-profile',
        'support',
        'knowledgebase',
        'workshops',
        'reporting',
        // blog
        'blog',
        'blog-post',

    );

    if ($route[0] == '') {
        $route[0] = 'home';
    }



    if (in_array($route[0], $valid_routes)) {
        // include core file
        require_once(__DIR__ . '/' . $route[0] . '.php');

        // include child template if exists
        if (is_file(__DIR__ . '/templates/tpl-' . $route[0] . '-child.php')) {
            require_once(__DIR__ . '/templates/tpl-' . $route[0] . '-child.php');
        } // else include original template file
        else {
            require_once(__DIR__ . '/templates/tpl-' . $route[0] . '.php');
        }
    } else {
        http_response_code(404);
        die('404 Not Found');
    }
}

/*
 * --------------------------------------------------
 * User
 * --------------------------------------------------
 */
if ($route[0] == 'user') {
    $valid_routes = array(
        // General
        'dashboard',
        'change-password',
        'edit-user',
        'tickets',
        'manage-ticket'
    );

    if (in_array($route[1], $valid_routes)) {
        if ($route[0] != '') {
            // include core file
            require_once(__DIR__ . '/user/' . $route[1] . '.php');

            // include child template if exists
            if (is_file(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '-child.php')) {
                require_once(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '-child.php');
            } // else include original template file
            else {
                require_once(__DIR__ . '/templates/user-templates/tpl-' . $route[1] . '.php');
            }
        } else {
            http_response_code(404);
            die('404 Not Found');
        }
    } else {
        http_response_code(404);
        die('404 Not Found');
    }
}


/*
 * --------------------------------------------------
 * admin
 * --------------------------------------------------
 */
if ($route[0] == 'admin') {
    $valid_routes = array(
        // General
        'dashboard',
        'blog-categories',
        'blog-posts',
        'change-password',
        'configurations',
        'contact-form-messages',
        'edit-user',
        'email-templates',
        'entities',
        'lookup-codes',
        'manage-blog-category',
        'manage-blog-post',
        'manage-configuration',
        'manage-contact',
        'manage-email-template',
        'manage-entity',
        'manage-lookup-code',
        'manage-page',
        'manage-users',
        'pages',
        'users',
        'forgot-password',
        'inbox',

        'tickets',
        'manage-ticket',
        'workshops',
        'manage-workshop',
        'reports',
        'manage-report',

        'companies',
        'sla-policies',
        'manage-company',
        'manage-sla-policies'
    );

    if (in_array($route[1], $valid_routes)) {
        if ($route[0] != '') {
            // include core file
            require_once(__DIR__ . '/admin/' . $route[1] . '.php');

            // include child template if exists
            if (is_file(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '-child.php')) {
                require_once(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '-child.php');
            } // else include original template file
            else {
                require_once(__DIR__ . '/templates/admin-templates/tpl-' . $route[1] . '.php');
            }
        } else {
            http_response_code(404);
            die('404 Not Found');
        }
    } else {
        http_response_code(404);
        die('404 Not Found');
    }
}
