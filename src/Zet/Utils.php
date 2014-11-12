<?php


namespace Zet;


class Utils 
{
    public static function dump($variable)
    {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
    }
}