<?php

class gcaptcha
{
    private $_key;
    private $_url;
    private $_result = false;

    public function __construct($response)
    {
        $common = new common();
        $this->_key = $common->get_value('secret_key');
        $this->_url = $common->get_value('captcha_site_url');

        $this->verify_captcha($response);
    }

    private function verify_captcha($response)
    {
        $data = array(
            'secret' => $this->_key,
            'response' => $response
        );

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                            "Content-Length: " . strlen(http_build_query($data)) . "\r\n" .
                            "User-Agent: MyAgent/1.0\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $verify = file_get_contents($this->_url, false, $context);

        if ($verify === false) {
            throw new Exception("Error verifying CAPTCHA");
        }

        $captcha_success = json_decode($verify);
        $this->_result = $captcha_success->success;
    }

    public function get_results(): bool
    {
        return $this->_result;
    }
}
?>
