<?php namespace wgm\models;

  class CSV{

    private $_headers = [];
    private $_values = [];
    private $_records = [];
    private $_field_cnt = 0;
    private $_max_display = 50;
    private $_page = 1;
    private $_page_limit = 25;
    private $_index = 0;
    private $_file;

    function __construct($page_limit=25, $max_display=50){
      $this->_page_limit = $page_limit;
      $this->_max_display = $max_display;
    }

    public function readFile($file){
      $this->_file = $file;
      // $ptr = 0;
      if ( ($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
          $this->addRecord($data);
        }
        fclose($handle);
        return true;
      }
      return false;
    }

    public function addRecord($values){

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

    public function getFile(){
      return $this->_file;
    }

    public function getFileName(){
      $bits = explode("/", $this->_file);
      return array_pop($bits);
    }

    public function getHeaders(){
      return $this->_headers;
    }

    public function hasHeader($header){
      return in_array($header, $this->_headers);
    }

    public function getRecords($page=NULL){
      if( $page===NULL ){
        $page = $this->_current_page;
      }
      //$this->_current_page = $page;
      $r1 = $page - 1;
      $rn = $page * $this->_max_display;
      return array_slice($this->_records, $r1, $rn);
    }

    public function hasNextRecord(){
      if( $this->_page_limit==0 ) return $this->_index < count($this->_records);
      return $this->_index < ($this->_page * $this->_page_limit) && $this->_index < count($this->_records);
    }

    public function hasNextPage(){
      if( $this->_page_limit==0 ) return false;
      return $this->_index < count($this->_records);
    }

    public function getCurrentRecord(){
      if( $this->_index==0 ){
        return $this->_records[$this->_index];
      }
      return $this->_records[$this->_index - 1];
    }

    public function getNextRecord(){

      if( $this->hasNextRecord() ){
        return $this->_records[$this->_index++];
      }
      return false;
    }

    public function resetRecordIndex($index=0){
      $this->_index = $index;
      $this->_page = floor($index/$this->_page_limit) + 1;
    }

    public function getFieldCnt(){
      return $this->_field_cnt;
    }

    public function getRecordCnt(){
      return count( $this->_records );
    }

    public function getRecordIndex(){
      return $this->_index;
    }

    public function getMaxDisplay(){
      return $this->_max_display;
    }

  }

?>
