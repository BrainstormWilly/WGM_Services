<?php namespace wgm\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";

  abstract class AbstractServiceData implements IServiceData{

    protected $_field_cnt = 0;        // the amount of fields/keys in a record
    protected $_file;                 // the full file path of the .csv as well as the log .txt
    protected $_headers = [];         // list of fields/keys for each record
    protected $_index = 0;            // current index of records
    protected $_display_limit = 50;   // max amount of records to show on screen
    protected $_page = 1;             // index of current page
    protected $_page_limit = 25;      // amount of method calls allowed per service session
    protected $_records = [];         // full list of records (data & headers)
    protected $_values = [];          // full list of records (data only + headers)
    protected $_set_limit = 1;        // amount of records allowed per service method (where allowed)

    function __construct($page_limit=25, $display_limit=50, $set_limit=1){
      $this->_page_limit = $page_limit;
      $this->_display_limit = $display_limit;
      $this->_set_limit = $set_limit;
    }

    public function addRecord($values){
      array_push($this->_records, $values);
    }

    public function getCurrentPage(){
      return $this->_page;
    }

    public function getCurrentRecord(){
      if( $this->_index==0 ){
        return $this->_records[$this->_index];
      }
      return $this->_records[$this->_index - 1];
    }

    public function getDisplayLimit(){
      return $this->_display_limit;
    }

    public function getFieldCnt(){
      return $this->_field_cnt;
    }

    public function getFile(){
      return $this->_file;
    }
    public function getFileName(){
      return $this->_file;
    }

    public function getHeaders(){
      return $this->_headers;
    }

    public function getNextRecord($override_page=false){
      if( $this->hasNextRecord($override_page) ){
        return $this->_records[$this->_index++];
      }
      return false;
    }

    public function getNextSet(){
      $set = [];
      $set_index = 0;
      while( $this->hasNextRecord() && $set_index < $this->_set_limit ){
        $set[ $set_index++ ] = $this->_records[$this->_index++];
      }
      if( count($set) > 0 ){
        return $set;
      }
      return false;
    }

    public function getPageLimit(){
      return $this->_page_limit;
    }

    public function getRecordCnt(){
      return count( $this->_records );
    }

    public function getRecordIndex(){
      return $this->_index;
    }

    public function getRecords($page=NULL){
      if( $page===NULL ){
        $page = $this->_current_page;
      }
      $r1 = $page - 1;
      $rn = $page * $this->_display_limit;
      return array_slice($this->_records, $r1, $rn);
    }

    public function getSetLimit(){
      return $this->_set_limit;
    }

    public function hasHeader($header){
      return in_array($header, $this->_headers);
    }

    public function hasNextPage(){
      if( $this->_page_limit==0 ) return false;
      return $this->_index < count($this->_records);
    }

    public function hasNextRecord($override_page=false){
      if( $this->_page_limit==0 || $override_page ) return $this->_index < count($this->_records);
      return $this->_index < ($this->_page * $this->_page_limit) && $this->_index < count($this->_records);
    }

    public function readData($file){
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

    public function resetRecordIndex($index=0){
      $this->_index = $index;
      $this->_page = floor($index/$this->_page_limit) + 1;
    }

    public function writeData($data, $include_headers=TRUE){
      if( $include_headers ){
        $perm = 'w';
      }else{
        array_shift($this->_values); // remove headers
        $perm = 'a';
      }
      if ( ($handle = fopen($file, $perm)) !== FALSE) {
        foreach ($this->_values as $val) {
          fputcsv($handle, $val);
        }
        fclose($handle);
        return true;
      }
      return false;
    }

  }

?>
