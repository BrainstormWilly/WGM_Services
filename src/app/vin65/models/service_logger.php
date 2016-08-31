<?php namespace wgm\vin65\models;

  class ServiceLogItem{
    private $_is_success = true;
    private $_record_num = 0;
    private $_customer_num = 0;
    private $_heading = "";
    private $_message = "";

    function __construct($success, $record_num, $customer_num, $heading, $message){
      $this->_is_success = $success;
      $this->_record_num = $record_num;
      $this->_customer_num = $customer_num;
      $this->_heading = $heading;
      $this->_message = $message;
    }

    public function success(){
      return $this->_is_success;
    }

    public function toHtml(){
      if( $this->_is_success ){
        $s = '<p><strong>SUCCESS: </strong>';
      }else{
        $s = '<p style="color:red"><strong>FAIL: </strong>';
      }
      $s .= 'Record: ' . $this->_record_num . ", ";
      $s .= 'Upload ID: ' . $this->_customer_num . "</br>";
      $s .= '&nbsp;&nbsp;' . $this->_heading . "</br>";
      $s .= '&nbsp;&nbsp;Result ID: ' . $this->_message . "</p>";
      return $s;
    }

    public function toText(){
      if( $this->_is_success ){
        $s = 'SUCCESS: ';
      }else{
        $s = 'FAIL: ';
      }
      $s .= 'Record: ' . $this->_record_num . ", ";
      $s .= 'Upload ID: ' . $this->_customer_num . PHP_EOL;
      $s .= '  ' . $this->_heading . PHP_EOL;
      $s .= '  Error: ' . $this->_message . PHP_EOL;
      return $s;
    }

    public function toRecord(){
      $s = [];
      if( $this->_is_success ){
        $s['Status'] = 'SUCCESS,';
      }else{
        $s['Status'] = 'FAIL,';
      }
      $s['RecordNumber'] = $this->_record_num . ",";
      $s['CustomerID'] = $this->_customer_num . ",";
      $s['Heading'] = $this->_heading . ",";
      $s['Message'] = $this->_message . PHP_EOL;
      return $s;
    }
  }

  class ServiceLogger{

    private $_file_handle;
    private $_log = [];

    public static function createSuccessItem($record_num, $customer_num, $heading, $message){
      return new ServiceLogItem(true, $record_num, $customer_num, $heading, $message);
    }

    public static function createFailItem($record_num, $customer_num, $heading, $message){
      return new ServiceLogItem(false, $record_num, $customer_num, $heading, $message);
    }

    public function openLog($csv, $index){
      if( $index==0 ){
        $this->_log = [];
        $perm = 'w';
      }else{
        $perm = 'a';
      }
      $file = str_replace('.csv','_log.csv',$csv);
      $this->_file_handle = fopen( $file, $perm);
    }

    public function writeToLog(ServiceLogItem $log_item){
      if( isset($this->_file_handle) ){
        array_unshift($this->_log, $log_item);
        fputcsv($this->_file_handle, $log_item->toRecord() );
        // fwrite($this->_file_handle, $log_item->toRecord() );
        // $_SESSION['log'] = 'working';
        //$_SESSION["log"] = $log_item->toHtml();
      }else{
        throw new \Exception("No log file opened for writing.");
      }

    }

    public function closeLog(){
      if( isset($this->_file_handle) ){
        fclose($this->_file_handle);
      }
    }

    public function getLog($type='all'){
      $log = [];
      if( $type=='success' ){
        foreach($this->_log as $item){
          if($item->success()){
            array_push($log, $item);
          }
        }
        return $log;
      }elseif ( $type=='fail' ) {
        foreach($this->_log as $item){
          if(!$item->success()){
            array_push($log, $item);
          }
        }
        return $log;
      }
      return $this->_log;
    }
  }

?>
