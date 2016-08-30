<?php namespace wgm\vin65\models;

  use \DateTime as DateTime;

  class DateConverter{

    public static function toYMD($date_str){
      $d = new DateTime($date_str);
      // return $d->format('Y-m-d\TH:m:s');
      return $d->format('Y-m-d\TH:m:s');
    }

    public static function toMDY($date_str){
      $d = new DateTime($date_str);
      // return $d->format('Y-m-d\TH:m:s');
      return $d->format('m/d/Y');
    }

  }

?>
