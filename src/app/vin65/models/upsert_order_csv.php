<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/models/i_service_data.php';
require_once $_ENV['APP_ROOT'] . '/models/csv.php';

use wgm\models\CSV as CSVModel;
use wgm\models\IServiceData as IServiceData;


class UpsertOrderCSV implements IServiceData{

  private $_csv;
  private $_records = [];
  private $_index = 0;
  private $_page = 1;

  function __construct($page_limit=100, $display_limit=50, $set_limit=1){
    $this->_csv = new CSVModel($page_limit, $display_limit, $set_limit);
  }

  public function addRecord($values){
    $index = -1;
    $cnt = count($this->_records);
    for($i=0; $i<$cnt; $i++){
      if( $this->_records[$i]["OrderNumber"] == $values["OrderNumber"] ){
        array_push($this->_records[$i]['OrderItems'], $values);
        $index = $i;
        break;
      }
    }
    if( $index == -1 ){
      $order = $values;
      $order["OrderItems"] = [$values];
      $order["Tenders"] = [$values];
      array_push($this->_records, $order);
    }
  }

  public function getCurrentIndex(){
    return $this->_index;
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

  public function getCurrentRecordIndex(){
    $recs = 0;
    for($i=0; $i<$this->_index; $i++){
      $recs += count($this->_records[$i]['OrderItems']);
    }
    // print_r($recs . ":" . $this->getRecordIndex() . "</br>");
    return $recs + $this->getRecordIndex();
  }

  public function getDisplayLimit(){
    return $this->_csv->getDisplayLimit();
  }

  public function getFile(){
    return $this->_csv->getFile();
  }

  public function getFileName(){
    return $this->_csv->getFileName();
  }

  public function getHeaders(){
    return array_keys($this->_records);
  }

  public function getNextRecord($override_page=false){
    return $this->getNextSet();
  }

  public function getNextSet(){
    $set = [];
    $set_index = 0;
    while( $this->hasNextRecord() && $set_index < $this->getSetLimit() ){
      $set[ $set_index++ ] = $this->_records[$this->_index++];
    }

    if( count($set) > 0 ){
      return $set;
    }
    return false;
  }

  public function getPageLimit(){
    return $this->_csv->getPageLimit();
  }

  public function getRecordCnt(){
    return $this->_csv->getRecordCnt();
  }

  public function getRecordIndex(){
    return $this->_csv->getRecordIndex();
  }

  public function getRecords($page=NULL){
    return $this->_records;
  }

  public function getSetLimit(){
    return $this->_csv->getSetLimit();
  }

  public function hasHeader($header){
    return in_array( $header, $this->getHeaders() );
  }

  public function hasNextPage(){
    return $this->_csv->hasNextPage();
  }

  public function hasNextRecord($override_page=false){
    return $this->_index < count($this->_records);
  }

  public function readData($data){
    if( $this->_csv->readData($data) ){
      while( $this->_csv->hasNextRecord(true) ){
        $this->addRecord( $this->_csv->getNextRecord(true) );
      }
      return true;
    }
    return false;
  }

  public function resetRecordIndex($index=0, $cnt=0){
    $this->_csv->resetRecordIndex($index, $cnt);
    $this->_index = 0;
    $this->_page = floor($index/$this->getPageLimit()) + 1;
    // print_r("resetRecordIndex index: " . $this->_csv->getRecordIndex() . "</br>");
  }

  public function writeData($data, $include_headers=TRUE){
    $this->_csv->writeData($data, $include_headers);
  }

}

?>
