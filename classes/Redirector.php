<?php

namespace App\Classes;

class Redirector
{
    public static function redirect(string $url)
    {
        header('Location: ' . $url);
        die();
    }
}