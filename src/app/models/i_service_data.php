<?php namespace wgm\models;

interface IServiceData{

  public function addRecord($values);
  public function getCurrentPage();
  public function getCurrentRecord();
  public function getDisplayLimit();
  public function getFile();
  public function getFileName();
  public function getHeaders();
  public function getNextRecord($override_page=false);
  public function getNextSet();
  public function getPageLimit();
  public function getRecordCnt();
  public function getRecordIndex();
  public function getRecords($page=NULL);
  public function getSetLimit();
  public function hasHeader($header);
  public function hasNextPage();
  public function hasNextRecord($override_page=false);
  public function readData($file);
  public function resetRecordIndex($index=0);
  public function writeData($data, $include_headers=TRUE);

}

?>
