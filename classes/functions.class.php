<?php

class functions
{

    public static function get_iplocation($ip)
    {
        $location = file_get_contents('https://ip2c.org/?ip=' . $ip);
        $var = explode(";", $location);
        return $var[1] == '' ? 'none' : $var[1];
    }

    public static function get_ip()
    {
        if ($_SERVER) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } else if (isset($_SERVER["REMOTE_ADDR"])) {
                return $_SERVER["REMOTE_ADDR"];
            } else {
                return '0.0.0.0';
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                return getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                return getenv('HTTP_CLIENT_IP');
            } else if (getenv('REMOTE_ADDR')) {
                return getenv('REMOTE_ADDR');
            } else {
                return '0.0.0.0';
            }
        }
    }

    public static function convert_route_to_h1($string)
    {
        return ucwords(str_replace('-', ' ', $string));
    }


    public static function to_slug($string, $maxLength = 200, $separator = '-')
    {
        if (function_exists('transliterator_transliterate')) {
            $slug = transliterator_transliterate("Any-Latin; Latin-ASCII;", $string);
        } else {
            $slug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string));
        }
        $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug);
        $slug = trim(substr(strtolower($slug), 0, $maxLength));
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);
        $slug = (!empty($slug)) ? $slug : 'str_0';
        return $slug;
    }

    public static function get_randomAlphanumerics($length = '10')
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); // turn the array into a string
    }

    public static function get_randomNumbers($length)
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    public static function break_slug($route)
    {
        $results = array();
        $position = strripos($route, '-');
        $results[] = substr($route, 0, $position);
        $results[] = substr($route, $position + 1, strlen($route));
        if ($results[0] == '' or $results[1] == '') {
            http_response_code(404);
            die('404 Not Found');
        }
        if (!is_numeric($results[1])) {
            http_response_code(404);
            die('404 Not Found');
        }

        return $results;
    }

    // to get the id from the string route when '-' is given
    public static function get_routeid($route)
    {
        $position = strripos($route, '-');
        return substr($route, $position + 1, strlen($route));
    }

    public static function review_ranking($number = 5)
    {
        $array = array();
        $array[] = $number == 1 ? '<option value="1" selected>1-Poor</option>' : '<option value="1">1-Poor</option>';
        $array[] = $number == 2 ? '<option value="2" selected>2-Fair</option>' : '<option value="2">2-Fair</option>';
        $array[] = $number == 3 ? '<option value="3" selected>3-Good</option>' : '<option value="3">3-Good</option>';
        $array[] = $number == 4 ? '<option value="4" selected>4-Very Good</option>' : '<option value="4">4-Very Good</option>';
        $array[] = $number == 5 ? '<option value="5" selected>5-Excellent</option>' : '<option value="5">5-Excellent</option>';
        return $array;
    }

    public static function pagi_start_record($current_page_no = null)
    {
        $common = new common();
        $per_page = $common->get_value('per_page');
        return ($current_page_no > 1) ? ($current_page_no * $per_page) - $per_page : 0;
    }

    public static function offset($current_page_no = null)
    {
        if (!is_numeric($current_page_no) || $current_page_no < 1) {
            $current_page_no = 1;
        }

        $common = new common();
        $per_page = $common->get_value('per_page');

        if (!is_numeric($per_page) || $per_page < 1) {
            $per_page = 10;
        }

        return ($current_page_no - 1) * $per_page;
    }

    public static function gen_filename()
    {
        $datePart = date('Ymd');
        $randomPart = mt_rand(10000, 99999);
        $timePart = date('His') . substr(microtime(), 2, 3);
        return $datePart . $timePart . $randomPart;
    }

    public static function timeAgo($timestamp)
    {
        $datetime1 = new DateTime($timestamp);
        $datetime2 = new DateTime('now');
        $interval = $datetime1->diff($datetime2);

        if ($interval->y > 0) {
            return $interval->format('%y years ago');
        } elseif ($interval->m > 0) {
            return $interval->format('%m months ago');
        } elseif ($interval->d > 0) {
            return $interval->format('%d days ago');
        } elseif ($interval->h > 0) {
            return $interval->format('%h hours ago');
        } elseif ($interval->i > 0) {
            return $interval->format('%i minutes ago');
        } else {
            return 'just now';
        }
    }

    public static function formatDateTime($dateTime)
    {
        $givenTime = new DateTime($dateTime);
        $currentTime = new DateTime();
        $interval = $currentTime->diff($givenTime);

        if ($interval->d > 0) {
            return $givenTime->format('d-M-Y');
        } elseif ($interval->i > 0) {
            return $interval->i . ' min' . ($interval->i > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }


    public static function formatCustomDateTime($dateTime)
    {
        $givenTime = new DateTime($dateTime);
        return $givenTime->format('d-M-Y H:i');
    }

    public static function formatRoute($route)
    {
        $formattedRoute = str_replace('-', ' ', $route);
        $formattedRoute = ucwords($formattedRoute);
        return $formattedRoute;
    }
    public static function validateJson($json)
    {
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON Error: ' . json_last_error_msg());
        }
        return $decoded;
    }

    public static function get_lookups_value($table, $id)
    {
        $web = new websettings();
        $condition = [
            'id' => ['=', $id]
        ];
        return $web->fetch($table, $condition)->description ?? '';
    }
    public function explode_values($values)
    {
        $a = 0;
        $list = null;
        foreach (explode(';', $values) as $row) {
            $a ? $list .= ', ' : $a = 1;
            $list .= $this->get_lookups_value('lookups', $row);
        }
        return $list;
    }
}
