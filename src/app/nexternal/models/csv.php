<?php namespace wgm\nexternal\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";
  require_once $_ENV['APP_ROOT'] . "/models/abstract_service_data.php";

  use wgm\models\IServiceData as IServiceData;
  use wgm\models\AbstractServiceData as AbstractServiceData;

  class CSV extends AbstractServiceData implements IServiceData{

    public function addRecord($values){
      array_push( $this->_values, $values );
      array_push( $this->_records, $values );
      if( empty($this->_headers) ){
        $this->_headers = array_keys($values);
      }
    }

    // public function getFileName(){
    //   $bits = explode("/", $this->_file);
    //   return array_pop($bits);
    // }

  }

?>
