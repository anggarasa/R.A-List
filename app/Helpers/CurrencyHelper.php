<?php 

if (!function_exists('format_rupiah')) {
    function format_rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_nominal')) {
    function format_nominal($nominal)
    {
        if ($nominal >= 1000000) {
            return floor($nominal / 1000000) . 'jt';
        } elseif ($nominal >= 100000) {
            return floor($nominal / 1000) . 'K';
        }

        return number_format($nominal, 0, ',', '.');
    }
}
