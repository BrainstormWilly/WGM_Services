<?php namespace wgm\vin65\models;

  use \DateTime as DateTime;

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
      $s .= 'UploadID: ' . $this->_customer_num . "</br>";
      $s .= '&nbsp;&nbsp;Heading: ' . $this->_heading . "</br>";
      if( $this->_is_success ){
        $s .= '&nbsp;&nbsp;ResultID: ' . $this->_message . "</p>";
      }else{
        $s .= '&nbsp;&nbsp;Message: ' . $this->_message . "</p>";
      }

      return $s;
    }

    public function toText(){
      if( $this->_is_success ){
        $s = 'SUCCESS: ';
      }else{
        $s = 'FAIL: ';
      }
      $s .= 'Record: ' . $this->_record_num . ", ";
      $s .= 'UploadID: ' . $this->_customer_num . PHP_EOL;
      $s .= '  Heading:' . $this->_heading . PHP_EOL;
      $s .= '  Message: ' . $this->_message . PHP_EOL;
      if( $this->_is_success ){
        $s .= '  ResultID: ' . $this->_message . PHP_EOL;
      }else{
        $s .= '  Message: ' . $this->_message . PHP_EOL;
      }
      return $s;
    }

    public function toRecord(){
      $s = "";
      if( $this->_is_success ){
        $s .= 'SUCCESS,';
      }else{
        $s .= 'FAIL,';
      }
      $s .= $this->_record_num . ",";
      $s .= $this->_customer_num . ",";
      $s .= $this->_heading . ",";
      $s .= $this->_message . PHP_EOL;
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
      $d = new DateTime();
      $file = str_replace('.csv','_log.csv',$csv);
      if( file_exists($file) ){
        $this->_file_handle = fopen( $file, 'a');
      }else{
        $this->_file_handle = fopen( $file, 'w');
        $this->_log = [];
        fwrite( $this->_file_handle, "Status,Record,UploadID,Service,Result" . PHP_EOL);
      }
      fwrite( $this->_file_handle, "TIMESTAMP,0,OPEN," . $d->format('Y-m-d H:i:s') . PHP_EOL);
    }

    public function writeToLog(ServiceLogItem $log_item){
      if( isset($this->_file_handle) ){
        array_unshift($this->_log, $log_item);
        fwrite($this->_file_handle, $log_item->toRecord() );
        // fwrite($this->_file_handle, $log_item->toRecord() );
        // $_SESSION['log'] = 'working';
        //$_SESSION["log"] = $log_item->toHtml();
      }else{
        throw new \Exception("No log file opened for writing.");
      }

    }

    public function closeLog(){
      if( isset($this->_file_handle) ){
        $d = new DateTime();
        fwrite( $this->_file_handle, "TIMESTAMP,0,CLOSE," . $d->format('Y-m-d H:i:s') . PHP_EOL);
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
