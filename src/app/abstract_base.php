<?php namespace wgm;

  use \ReflectionClass as ReflectionClass;

  abstract class AbstractBase{

    public function getClassName(){
      $class_ns = get_class($this);
      $class_bits = explode("\\", $class_ns);
      return array_pop($class_bits);
    }

    public function getClassFileName(){
      $class = new ReflectionClass($this);
      $path = $class->getFileName();
      $path_bits = explode("/", $path);
      $file = array_pop($path_bits);
      $file_bits = explode(".", $file);
      return $file_bits[0];
    }

    protected function _isRealValue($value, $key=NULL){
      if( $key===NULL ){
        return ( isset($value) && trim($value) !== '' && $value !== NULL );
      }
      return ( isset($value[$key]) && trim($value[$key]) !== '' && $value[$key] !== NULL );
    }

    public function out($value){
      print_r("[[" . $this->getClassName() . "]]");
      print_r(": ");
      print_r($value);
      print_r("<br/>");
    }

  }

?>
