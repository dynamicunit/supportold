<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-email-template');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$emailid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $emailid]
];

// check if the designer id is valid
if ($emailid && !$web->fetch('email_templates', $conditions)) {
    $view->get_page404();
}
$table = 'email_templates';
$conditions = [
    'id' => ['=', $emailid],
];

$data = $web->fetch($table, $conditions);


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'type' => array(
                'required' => true,
                'min' => 3,
                'max' => 50
            ),
            'desc' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            ),
            'subject' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            )
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();

                if (input::get('mail_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'text_id' => input::get('type'),
                        'field_description' => input::get('desc'),
                        'email_subject' => input::get('subject'),
                        'email_body' => input::get('body'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('mail_id')],
                    ];
                    $web->update('email_templates', $sanitizeddata, $conditions);


                    $web->commit();

                    session::flash('success', 'Email Template Updated Successfully.');
                    redirect::to($baseurl . '/admin/email-templates');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => config::get('website/website_code'),
                        'text_id' => input::get('type'),
                        'field_description' => input::get('desc'),
                        'email_subject' => input::get('subject'),
                        'email_body' => input::get('body'),
                    ]);

                    $web->add('email_templates', $sanitizeddata);

                    $last_id = $web->lastInsertId();
                    $web->commit();
                    session::flash('success', 'Email Template Added Successfully.');
                    redirect::to($baseurl . '/admin/email-templates');
                }
            } catch (exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $emailid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $emailid;
