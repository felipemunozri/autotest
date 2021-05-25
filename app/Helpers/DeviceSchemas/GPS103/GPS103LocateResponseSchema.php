<?php

namespace App\Helpers\DeviceSchemas\GPS103;

use App\Domain\Helpers\ValidatorsHelper;
use Carbon\Carbon;
use App\Helpers\DeviceSchemas\MessageTextHelper;


class GPS103LocateResponseSchema {

  private $lat;
  private $long;
  private $speed;
  private $timestamp;
  private $map_url;
  private $pwr;
  private $door;
  private $acc;
  private $message;

  public function __construct() {
  }

  /*
    'lat:-39.288950
    long:-71.931510
    speed:0.00
    T:21/03/04 04:51
    http://maps.google.com/maps?f=q&q=-39.288950,-71.931510&z=16
    Pwr: ON Door: OFF ACC: OFF'
  */
  public function parseFormat($message) {

    $this->message = $message;
    
    $data = preg_split('/\r\n|\r|\n/',$message);

    if(count($data) >= 6) {
      list($lat, $long, $speed, $timestamp, $map_url, $device_detail) = $data;
    } else {
      return $this->getSchema();
    }

    $this->lat = MessageTextHelper::extractValue($lat, 'lat:');
    $this->long = MessageTextHelper::extractValue($long, 'long:');
    $this->speed = MessageTextHelper::extractValue($speed, 'speed:');
    $timestamp = MessageTextHelper::extractValue($timestamp, 'T:', 0, false);
    if($this->dateHasFormat($timestamp)){
      $this->timestamp = Carbon::createFromFormat("y/m/d H:i",$timestamp)->toDateTimeString();
    } else {
      $this->timestamp = null;
    }
    $this->map_url = trim($map_url);

    if($device_detail) {
      $device_detail = str_replace(' ', '', $device_detail);
      $this->pwr = MessageTextHelper::extractValue($device_detail, 'Pwr:', strpos($device_detail, 'Door:'));
      $this->door = MessageTextHelper::extractValue($device_detail, 'Door:', strpos($device_detail, 'ACC:'));
      $this->acc = MessageTextHelper::extractValue($device_detail, 'ACC:');
    }
    return $this->getSchema();
  }

  private function dateHasFormat($date) {
    return Carbon::hasFormat($date, 'y/m/d H:i');
  }

  private function getSchema() {
    return (object) [
      'lat' => $this->lat,
      'long' => $this->long,
      'speed' => $this->speed,
      'timestamp' => $this->timestamp,
      'map_url' => $this->map_url,
      'pwr' => $this->pwr,
      'door' => $this->door,
      'acc' => $this->acc,
      'message' => $this->message
    ];
  }

  public function isValid() {
    try {
      $is_valid_lat = $this->lat ? ValidatorsHelper::latitude($this->lat): false;
      $is_valid_long = $this->long ? ValidatorsHelper::longitude($this->long) : false;
      $is_valid_speed = is_numeric($this->speed);
      $is_valid_timestamp = $this->timestamp;
      $is_valid_map_url = $this->map_url && ValidatorsHelper::url($this->map_url);

      $is_valid_pwr = $this->pwr && ($this->pwr === 'ON' || $this->pwr === 'OFF');
      $is_valid_door = $this->door && ($this->door === 'ON' || $this->door === 'OFF');
      $is_valid_acc = $this->acc && ($this->acc === 'ON' || $this->acc === 'OFF');
      return (
          $is_valid_lat &&
          $is_valid_long &&
          $is_valid_speed &&
          $is_valid_timestamp &&
          $is_valid_map_url &&
          $is_valid_pwr &&
          $is_valid_door &&
          $is_valid_acc
      );
    } catch (Exception $e) {
      return false;
    }
  
  }

}
