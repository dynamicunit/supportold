<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-contact');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$contact_id = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $contact_id]
];

// check if the designer id is valid
if (!$contact_id && !$user->fetch('contactform', $conditions)) {
    $view->get_page404();
}
$table = 'contactform';
$conditions = [
    'id' => ['=', $contact_id],
];

$data = $user->fetch($table, $conditions);


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'full-name' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            ),
            'email' => array(
                'required' => true,
                'min' => 5,
                'max' => 50,
                'format_email' => 'true'
            ),
            'message-body' => array(
                'required' => true,
                'min' => 10,
                'max' => 1000
            )
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                $sanitizeddata = sanitizer::sanitize([
                    'email' => input::get('email'),
                    'phone' => input::get('phone'),
                    'full_name' => input::get('full-name'),
                    'message_body' => input::get('message-body')
                ]);
                $conditions = [
                    'id' => ['=', input::get('contact_id')],
                ];
                $user->update('contactform', $sanitizeddata, $conditions);


                $user->commit();

                session::flash('success', 'Contact Message Updated Successfully.');
                redirect::to($baseurl . '/admin/contact-form-messages');
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $contact_id);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $contact_id;
