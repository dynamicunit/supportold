<?php

class vwemail
{

    private $_db;

    private $_user;

    private $_login;

    private $_common;

    private $_link_home;

    private $_website_code;

    public function __construct()

    {
        $this->_db = db::getinstanace();
        $this->_common = new common();
        $this->_link_home = config::get('website/website_url');
        $this->_website_code = config::get('website/website_code');

        $objuser = new users();
        $this->_user = $objuser->get_loggeduser();
        $this->_login = $objuser->get_loggedin();
    }

    public function get_emailheader()
    {
        return '<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width">
    <title>%email_title%</title>
</head>

<body>
    <div id="email" style="width:600px;">
        <table role="presentation" border="0" cellspacing="0" width="100%">
            <tr>
                <td>
                    <img alt="' . $this->_common->get_value('site_url') . '" height="40"
                        src="' . $this->_common->get_value('site_url') . '/assets/imgs/logo.png' . '" border="0">
                </td>
            </tr>
        </table>

        <table role="presentation" border="0" cellspacing="0" width="100%">
            <tr>
                <td>';
    }

    public function get_emailfooter()
    {
        if ($this->_login) {
            return '</td>
            </tr>
        </table>
        <table role="presentation" border="0" cellspacing="0" width="100%">
    <tr>
        <br>
       <div>Regards,<br>
            ' . $this->_common->get_value('smtp_sender_name') . '<br>
           <a href="' . $this->_common->get_value('site_url') . '">' . $this->_common->get_value('site_url') . '</a><br>
        </div>
        <div><br> If you no longer wish to receive emails from us, you can <a
                href="' . $this->_link_home . '/unsubscribe?email=' . $this->_user->usr_email . '&code="' . $this->_user->usr_ver_hash . '
                target="_blank">unsubscribe</a> here. For any inquiries, please <a
                href="' . $this->_link_home . '/contact" target="_blank">contact us</a>.</div>
        </td>
    </tr>
</table>
    </div>
</body>
</html>';
        } else {
            return '</td>
            </tr>
        </table>
        <table role="presentation" border="0" cellspacing="0" width="100%">
    <tr>
        <br>
        <div>Regards,<br>
            ' . $this->_common->get_value('smtp_sender_name') . '<br>
           <a href="' . $this->_common->get_value('site_url') . '">' . $this->_common->get_value('site_url') . '</a><br>
        </div>
        <div><br> If you wish to stop receiving emails from us, or if you have any inquiries, please feel free to <a
                href="' . $this->_link_home . '/contact" target="_blank">contact us</a>.</div>
        </td>
    </tr>
</table>
    </div>
</body>
</html>';
        }
    }
}
