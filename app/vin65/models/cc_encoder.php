<?php namespace wgm\vin65\models;

  class CCEncoder{

    public static function encode($num, $key, $salt){
      return base64_encode(openssl_encrypt($num, 'des-ede3', base64_decode($key), OPENSSL_RAW_DATA));
    }

    public static function decode($code, $key, $salt){
      return openssl_decrypt(base64_decode($code), 'des-ede3', $key, OPENSSL_RAW_DATA);
    }

  }

?>
