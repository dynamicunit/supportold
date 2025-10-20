<?php

class email
{
    private $_db;
    private $_email_template;
    private $_email_body;
    private $_email_subject;
    private $_email_address;
    private $_email_priority;
    private $_email_params;
    private $_smtp_sender_name;
    private $_smtp_sender_address;

    public function __construct($type, $email_address = null, $params = null)
    {
        $this->_db = db::getinstanace();
        $view = new vwemail();
        $com = new common();

        $this->_smtp_sender_name = $com->get_value('smtp_sender_name');
        $this->_smtp_sender_address = $com->get_value('smtp_sender_address');

        if (!$this->get_emailtemplate($type)) {
            throw new Exception('Email template not found');
        }

        $this->_email_body = $view->get_emailheader() . $this->_email_template->email_body . $view->get_emailfooter();
        $this->_email_subject = $com->get_value('site_name_w_ext') . ' - ' . $this->_email_template->email_subject;
        $this->_email_address = $email_address;
        $this->_email_params = $params;
        $this->_email_priority = $this->_email_template->priority;

        $this->replace_variables();
        $this->_email_body = str_replace('%email_title%', $this->_email_subject, $this->_email_body);

        return $this->_email_template->priority == '1' ? $this->send_email() : $this->push_email();
    }

    private function get_emailtemplate($type): bool
    {
        $query = "SELECT * FROM email_templates WHERE text_id = ? AND website_id = ?";
        $temp = $this->_db->query($query, array($type, config::get('website/website_code')));

        if ($temp->count()) {
            $this->_email_template = $temp->first();
            return true;
        }
        return false;
    }

    private function send_email(): bool
    {
        $phpmailer = new seswrapper();
        $phpmailer->sendemail($this->_email_address, $this->_email_subject, $this->_email_body, $this->_smtp_sender_address, $this->_smtp_sender_name);

        return $phpmailer->get_results();
    }

    private function push_email()
    {
        $this->_db->insert(
            'emails.r5emails',
            array(
                'eml_subject' => $this->_email_subject,
                'eml_address' => $this->_email_address,
                'eml_body' => $this->_email_body,
                'eml_priority' => $this->_email_template->etp_priority,
                'eml_smtp_sender_name' => $this->_smtp_sender_name,
                'eml_smtp_sender_address' => $this->_smtp_sender_address
            )
        );
        return 1;
    }

    private function replace_variables()
    {
        foreach ($this->_email_params as $key => $value) {
            $param_value = $this->get_emailparam($key);
            if ($param_value) {
                $this->insert_variable($param_value, $value);
            }
        }
    }

    private function insert_variable($search, $replace)
    {
        $this->_email_body = str_replace($search, $replace, $this->_email_body);
    }

    private function get_emailparam($search_string): ?string
    {
        $query = "SELECT property_value FROM email_params WHERE property = ?";
        $temp = $this->_db->query($query, array($search_string));

        if ($temp->count()) {
            return $temp->first()->property_value;
        }
        return null;
    }
}
