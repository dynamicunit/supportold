<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-company');
$view = new vwcommon($route[0]);
$web = new websettings();
$company = new listings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$company_id = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'company_id' => ['=', $company_id]
];

// check if the designer id is valid
if ($company_id && !$company->fetch('companies', $conditions)) {
    $view->get_page404();
}
$table = 'companies';
$conditions = [
    'company_id' => ['=', $company_id],
];

$data = $company->fetch($table, $conditions);
$where = [
    'is_active' => ['=', 1]
];
$cbo_sla = $web->get_combo(
    $web->fetchbyarray('sla_policies', $where),
    array(
        'type' => 'select',
        'selected' => $data && $data->sla_id ? array($data->sla_id) : array(),
        'values' => 'single'
    ),
    'description',
    'sla_id'
);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'company_name' => array(
                'required' => true,
                'min' => 3,
                'max' => 200
            ),
            'company_code' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'industry' => array(
                'max' => 100
            ),
            'company_size' => array(
                'max' => 50
            ),
            'timezone' => array(
                'max' => 100
            ),
            'default_language' => array(
                'max' => 20
            ),
            'portal_domain' => array(
                'max' => 200
            ),
            'contact_person' => array(
                'max' => 150
            ),
            'contact_email' => array(
                'email' => true,
                'max' => 150
            ),
            'contact_phone' => array(
                'max' => 50
            ),
            'billing_email' => array(
                'email' => true,
                'max' => 150
            )
        ));

        if ($validate->get_passed()) {
            $s3Bucket = new s3bucket();
            try {
                $company->begin_transaction();
                $result = input::get('old-logo');

                if (!empty($_FILES['company-img']['name'])) {
                    if (input::get('old-logo') != '') {
                        $s3Bucket->deleteImage(input::get('old-logo'));
                    }
                    $result = $s3Bucket->addImage($_FILES['company-img']);
                } elseif (input::get('image-exists') == "false") {
                    if (input::get('old-logo') != '') {
                        $s3Bucket->deleteImage(input::get('old-logo'));
                    }
                    $result = '';
                }
                $sanitizeddata = sanitizer::sanitize([
                    'company_name' => input::get('company_name'),
                    'company_code' => input::get('company_code'),
                    'logo_url' => $result,
                    'industry' => input::get('industry'),
                    'company_size' => input::get('company_size'),
                    'timezone' => input::get('timezone'),
                    'default_language' => input::get('default_language'),
                    'portal_domain' => input::get('portal_domain'),
                    'theme_color' => input::get('theme_color'),
                    'sla_id' => input::get('sla_id'),
                    'contract_start' => input::get('contract_start'),
                    'contract_end' => input::get('contract_end'),
                    'billing_type' => input::get('billing_type'),
                    'contact_person' => input::get('contact_person'),
                    'contact_email' => input::get('contact_email'),
                    'contact_phone' => input::get('contact_phone'),
                    'billing_email' => input::get('billing_email'),
                    'is_active' => input::get('is_active'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                if (input::get('company_id')) {
                    $sanitizeddata['updated_at'] = date('Y-m-d H:i:s');
                    $conditions = [
                        'company_id' => ['=', input::get('company_id')],
                    ];
                    $company->update('companies', $sanitizeddata, $conditions);

                    $company->commit();
                    session::flash('success', 'Company Updated Successfully.');
                    redirect::to($baseurl . '/admin/companies');

                } else {
                    $company->add('companies', $sanitizeddata);
                    $last_id = $company->lastInsertId();

                    $company->commit();
                    session::flash('success', 'Company Added Successfully.');
                    redirect::to($baseurl . '/admin/companies');
                }
            } catch (exception $e) {
                $company->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.');
                redirect::to($baseurl . $page->get_page_slug() . '/' . $company_id);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}



$canonical = $baseurl . $page->get_page_slug() . '/' . $company_id;
