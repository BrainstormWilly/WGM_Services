<?php namespace wgm\vin65\validators;

  class ResultModel{

    const
      ERROR = 0,
      SUCCESS = 1,
      WARNING = 2;

    private $_status;
    private $_process;
    private $_message;
    private $_record = 0;

    public static function CreateError($process, $message, $record=NULL){
      return new ResultModel(self::ERROR, $process, $message, $record);
    }

    public static function CreateWarning($process, $message, $record = NULL){
      return new ResultModel(self::WARNING, $process, $message, $record);
    }

    public static function CreateSuccess($process, $message='', $record = NULL){
      return new ResultModel(self::SUCCESS, $process, $message, $record);
    }

    function __construct($status, $process, $message = '', $record = NULL){
      $this->_status = $status;
      $this->_process = $process;
      $this->_message = $message;
      $this->_record = $record;
    }

    public function getMessage(){
      return $this->_message;
    }

    public function getProcess(){
      return $this->_process;
    }

    public function getRecord(){
      return $this->_record;
    }

    public function getStatus(){
      if( $this->_status === self::WARNING ){
        return "WARNING";
      }elseif ($this->_status === self::ERROR) {
        return "ERROR";
      }
      return "SUCCESS";
    }

    public function toHtml(){
      $record = '';
      if( $this->_record !== NULL ){
        $record = $this->_record . ": ";
      }
      if( $this->_status === self::WARNING ){
        return '<p>' . $record . '<span style="color: orange">WARNING!</span> ' . $this->_process . ' - ' . $this->_message . '</p>';
      }elseif ($this->_status === self::ERROR) {
        return '<p>' . $record . '<span style="color: red">ERROR!</span> ' . $this->_process . ' - ' . $this->_message . '</p>';
      }
      return '<p>' . $record . '<span style="color: green">SUCCESS!</span> ' . $this->_process . ' - ' . $this->_message . '</p>';
    }

    public function toText(){
      if( $this->_status === self::WARNING ){
        return $this->_record . ': WARNING!' . $this->_process . ' - ' . $this->_message . PHP_EOL;
      }
      return $this->_record . ': ERROR! ' . $this->_process . ' - ' . $this->_message . PHP_EOL;
    }


  }

?>
