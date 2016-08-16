<?php namespace wgm\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";
  require_once $_ENV['APP_ROOT'] . "/models/abstract_service_data.php";

  class ServiceInput extends AbstractServiceData implements IServiceData{

    function __construct($page_limit=0, $display_limit=50, $set_limit=1){
      parent::__construct($page_limit, $display_limit, $set_limit);
    }

    public function addRecord($values){

      if( count($this->_records) > 0 ) return; // only 1 record allowed for input

      array_push($this->_records, $values);

      // just in case we want to write input to a file
      array_push($this->_headers, array_keys($values));
      array_push($this->_values, array_keys($values));
      array_push($this->_values, array_values($values));
    }

    public function getRecordCnt(){
      return 1;
    }

    public function getRecords($page=NULL){
      return $this->_records;
    }

    public function hasNextPage(){
      return false;
    }

    public function hasNextRecord(){
      return $this->_index==0;
    }

    public function readData($data){
      $this->_file = $data;
      return true;
    }

    public function resetRecordIndex($index=0){
      $this->_index = 0; // only 1 record allowed
    }

  }

?>
