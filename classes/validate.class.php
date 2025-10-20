<?php

class validate
{

    private $_passed = false;

    private $_errors = array();

    private $_db = null;

    public function __construct()
    {
        $this->_db = db::getinstanace();
    }

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = trim($_POST[$item]);
                if (($rule === 'required') && empty($value)) {
                    $this->put_errors("{$item} is required!");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->put_errors("The minimum required characters for the <strong>{$item}</strong> field is {$rule_value}");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->put_errors(
                                    "The maximum required characters for the <strong>{$item}</strong> field is {$rule_value}."
                                );
                            }
                            break;
                        case 'unique_email':
                            $check = $this->_db->get(
                                $rule_value,
                                array(
                                    'email' => ['=',  $value]
                                )
                            );
                            if ($check->count()) {
                                $this->put_errors(
                                    "<strong>Email Address</strong> is already registered with us! please try to register with a different email address."
                                );
                            }
                            break;
                        case 'checkphone':
                            if (preg_match("/^[0-9]*$/", $value) === $rule_value) {
                                $this->put_errors("Only numbers [0-9] are allowed in the <strong>{$item}</strong> field.");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->put_errors("<strong>{$item}</strong> does not match");
                            }
                            break;
                        case 'old_password':
                            $temp = $this->_db->get('r5users', array(
                                'id',
                                '=',
                                session::get('user')
                            ));
                            if ($temp->first()->usr_password != hash::make($rule_value, $temp->first()->usr_salt)) {
                                $this->put_errors("{$item} is not correct");
                            }
                            break;
                        case 'format_email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->put_errors("<strong>{$item}</strong> must contain @ and . signs");
                            }
                            break;
                        case 'format_url':
                            if (!preg_match("/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w\.-]*)*\/?$/", $value)) {
                                $this->put_errors("<strong>{$item}</strong> enter a valid url");
                            }
                            break;
                        case 'format_name':
                            if (!preg_match("/^[a-zA-Z ]*$/", $value)) {
                                $this->put_errors("Only characters A-Z are allowed in the <strong>{$item}</strong> field. ");
                            }
                            break;
                        case 'vemail':
                            // $zba = new ZeroBounceAPI();
                            // @$validation = $zba->validate($value,'');

                            // if ($validation['status'] == 'invalid'){
                            // / $this->put_errors("{$item}:is either invalid / inactive. Please provide a working email address");
                            // }
                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = True;
        }
    }

    public function get_errors()
    {
        return $this->_errors;
    }

    public function get_passed()
    {
        return $this->_passed;
    }

    public function put_errors($error)
    {
        $this->_errors[] = $error;
    }
}
