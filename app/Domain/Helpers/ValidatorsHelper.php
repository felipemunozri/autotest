<?php


namespace App\Domain\Helpers;


class ValidatorsHelper
{
    static public function url($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            return true;
        } else {
            return false;
        }

    }

    static public function longitude($longitude)
    {
        if(preg_match("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/",$longitude)) {
            return true;
        } else {
            return false;
        }
    }

    static public function latitude($latitude)
    {
        if (preg_match("/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", $latitude)) {
            return true;
        } else {
            return false;
        }
    }
}
