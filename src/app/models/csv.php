<?php namespace wgm\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";
  require_once $_ENV['APP_ROOT'] . "/models/abstract_service_data.php";

  class CSV extends AbstractServiceData implements IServiceData{

    public function addRecord($values){
      // print_r($values);exit;
      array_push( $this->_values, $values );
      if( empty($this->_headers) ){
        $this->_field_cnt = count($values);
        for($i=0; $i<$this->_field_cnt; $i++){
          array_push( $this->_headers, $values[$i] );
        }
      }else{
        $record = [];
        for($i=0; $i<$this->_field_cnt; $i++) {
          $record[$this->_headers[$i]] = $values[$i];
        }
        array_push( $this->_records, $record );
      }
    }

    // public function getFileName(){
    //   $bits = explode("/", $this->_file);
    //   return array_pop($bits);
    // }

  }

?>
