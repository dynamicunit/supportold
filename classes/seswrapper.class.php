<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class seswrapper
{
    private $_results;

    public function sendemail($var_email, $var_subject, $var_bodytext, $sender_address, $sender_name)
    {
        $common = new common();
        $mail = new PHPMailer(true);

        try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Uncomment for debugging

            $mail->isSMTP();
            $mail->Host = $common->get_value("smtp_host");
            $mail->SMTPAuth = true;
            $mail->Username = $common->get_value("smtp_user_name");
            $mail->Password = $common->get_value("smtp_password");
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Changed to a constant for better clarity
            $mail->Port = $common->get_value("smtp_port");

            // Recipients
            $mail->setFrom($sender_address, $sender_name);
            $mail->addAddress($var_email); // Recipient email address
            // Optional: $mail->addReplyTo('info@example.com', 'Information');
            // Optional: $mail->addCC('cc@example.com');
            // Optional: $mail->addBCC('bcc@example.com');

            // Attachments
            // Optional: $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
            // Optional: $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $var_subject;
            $mail->Body = $var_bodytext;
            // Optional: $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $this->_results = 1;
        } catch (Exception $e) {
            $this->_results = 0;
            error_log("An error occurred while sending email.", 3, config::get('website/website_url') . 'errors.log');
        }
    }

    public function get_results()
    {
        return $this->_results;
    }
}
