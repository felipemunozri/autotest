<?php

namespace App\Helpers\DeviceSchemas;

class MessageTextHelper
{
  static function extractValue($text, $key, $finish_pos = 0, $clear_spaces = true){
    try {
      $text = trim($text);
      $key_position = strpos($text, $key);
      if($key_position >= 0) {
        $start = $key_position + strlen($key);
        $length = $finish_pos === 0 ? (strlen($text) - $start) : ($finish_pos - $start);
        $text = $clear_spaces ?  str_replace(' ', '', $text) : $text;
        return substr($text,$start,$length);
        
      } else {
        return null;
      }
    } catch (Exception $e) {
      return null;
    }
  }
}
