<?php

if (!function_exists('mask_string')) {
    function mask_string($string, $visible_start = 0, $visible_end = 0, $mask_char = '*') {
        $length = strlen($string);
        $start = substr($string, 0, $visible_start);
        $end = substr($string, $length - $visible_end);
        $mask_length = max(0, $length - $visible_start - $visible_end);

        return $start . str_repeat($mask_char, $mask_length) . $end;
    }
}
