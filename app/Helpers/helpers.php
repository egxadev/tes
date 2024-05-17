<?php

if (!function_exists('moneyFormat')) {
    function moneyFormat($str)
    {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}

if (!function_exists('dateID')) {
    function dateID($date)
    {
        $value = Carbon\Carbon::parse($date);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
}
