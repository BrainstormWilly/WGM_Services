<?php namespace wgm\vin65\models;

  use \DateTime as DateTime;

  class DateConverter{

    public static function toYMD($date_str){
      $d = new DateTime($date_str);
      // return $d->format('Y-m-d\TH:m:s');
      return $d->format('Y-m-d\T12:00:00');
    }

    public static function toMDY($date_str){
      $d = new DateTime($date_str);
      // return $d->format('Y-m-d\TH:m:s');
      return $d->format('m/d/Y');
    }

    public static function equals($date_str_1, $date_str_2){
      return self::toMDY($date_str_1) == self::toMDY($date_str_2);
    }

  }

?>
