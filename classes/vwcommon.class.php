<?php

class vwcommon
{

    private $_db;

    public function __construct($route = null)
    {
        $this->_db = db::getinstanace();
        $user = new users();

        $list1 = [
            'user',
            'admin'
        ];
        if ((!$user->get_loggedin()) and (in_array($route, $list1))) {
            redirect::to(config::get('website/website_url') . '/sign-in');
        }

        if ($user->get_loggedin()) {
            if (($route == 'admin') and ($user->get_loggeduser()->user_type == '2')) {
                redirect::to(config::get('website/website_url') . '/user/dashboard');
            }
        }

        $list2 = [
            'forgot-password',
            'sign-in',
            'register'
        ];

        if (($user->get_loggedin()) and (in_array($route, $list2))) {
            redirect::to(config::get('website/website_url') . '/user/dashboard');
        }
    }

    // standard function to be called from all pages
    public function page_messages($errors = null)
    {
        echo $this->get_sessionmessage();
        if ($errors)
            echo $this->get_formerrors($errors);
    }

    // show the session messages
    private function get_sessionmessage()
    {
        $view = null;
        if (session::exists('success')) {
            $view = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            $view .= '<strong>Success!</strong> ' . session::flash('success');
            $view .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            $view .= '</div>';
        } elseif (session::exists('error')) {
            $view = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $view .= '<strong>Error!</strong> ' . session::flash('error');
            $view .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            $view .= '</div>';
        }
        return $view;
    }

    // show the form errors on server side processing
    private function get_formerrors($_errors)
    {
        $view = '<div class="alert alert-danger my-3" role="alert">';
        foreach ($_errors as $error) {
            $view .= $error . '<br>';
        }
        $view .= '</div>';
        return $view;
    }

    // displays the 404 page
    public function get_page404()
    {
        http_response_code(404);
        die('404 Not Found');
    }
}
