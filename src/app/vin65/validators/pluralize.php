<?php namespace wgm\vin65\validators;

  use \ReflectionClass as ReflectionClass;

  class Pluralize{

    private $_words = [
      "This" => "All",
      "is" => "are",
      "is a" => "are",
      "is not" => "are not",
      "is not a" => "are not",
      "has a" => "have"
    ];

    public function replace($cnt, $word){
      if( $cnt!=1 ){
        if( array_key_exists($word, $this->_words) ){
          return $this->_words[$word];
        }
        return $word . "s";
      }
      return $word;
    }


  }

?>
