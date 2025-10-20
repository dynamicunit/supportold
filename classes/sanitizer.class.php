<?php

class sanitizer {
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        } else {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
}

?>