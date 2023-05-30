<?php

//Generate Random String
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

//Print Date Format by Y-m-d
function dateFormat($date,$format)
{
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($format);    
}

//