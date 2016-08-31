<?php namespace wgm\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";
  require_once $_ENV['APP_ROOT'] . "/models/abstract_service_data.php";

  class ServiceInput extends AbstractServiceData implements IServiceData{

    function __construct($page_limit=1, $display_limit=50, $set_limit=1){
      parent::__construct(1, $display_limit, 1);
    }

    public function addRecord($values){
      if( count($this->_records) > 0 ) return; // only 1 record allowed for input
      array_push($this->_records, $values);

      // just in case we want to write input to a file
      array_push($this->_headers, array_keys($values));
      array_push($this->_values, array_keys($values));
      array_push($this->_values, array_values($values));
    }

    public function getCurrentIndex(){
      return $this->_index;
    }

    public function getCurrentPage(){
      return 1;
    }

    public function getCurrentRecord(){
      if( count($this->_records)==1 ){
        return $this->_records[0];
      }
      return NULL;
    }

    public function getCurrentRecordIndex(){
      return 0;
    }

    public function getFile(){
      return "ServiceInput Form";
    }

    public function getFileName(){
      return "ServiceInput";
    }

    public function getRecordCnt(){
      return 1;
    }

    public function getRecordIndex(){
      return 0;
    }

    public function hasNextPage(){
      return false;
    }

    public function hasNextRecord($override_page=false){
      return $this->_index==0;
    }

    public function readData($data){
      $this->_file = $data;
      return true;
    }

    public function resetRecordIndex($index=0, $cnt=0){
      $this->_record_cnt = 1;
      $this->_record_index = 0;
      $this->_index = 0; // only 1 record allowed
    }

  }

?>
