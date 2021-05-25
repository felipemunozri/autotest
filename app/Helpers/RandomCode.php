<?php

namespace App\Helpers;


class RandomCode
{
    static public function generate($length = 5)
    {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      // Output: 54esmdr0qf
      return substr(str_shuffle($permitted_chars), 0, $length);
    }
}
