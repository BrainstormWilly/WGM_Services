<?php namespace wgm\vin65\validators;

  require_once $_ENV['APP_ROOT'] . '/models/excel.php';

  use \wgm\models\Excel as Excel;


  abstract class AbstractValidator{

    const
      STATE_UPLOAD_COMPLETE = 0,
      STATE_TESTS_COMPLETE = 1,
      STATE_READY = 2;

    protected $_db;
    protected $_tables = [];
    protected $_sql = "";
    protected $_state = self::STATE_READY;
    protected $_results = [];
    protected $_tests = [];
    protected $_reader;
    protected $_status = "Begin validation by uploading Excel or CSV file.";

    function __construct($db){
      $this->_db = $db;
      $this->_reader = new Excel();
    }

    public function getState(){
      return $this->_state;
    }
    public function setState($state){
      if( $state==self::STATE_TESTS_COMPLETE ){
        $this->_state = self::STATE_TESTS_COMPLETE;
      }elseif ($state==self::STATE_UPLOAD_COMPLETE) {
        $this->_state = self::STATE_UPLOAD_COMPLETE;
      }else{
        $this->_state = self::STATE_READY;
      }
    }

    public function createTables(){
      $this->dropTables();
      if( $this->_db->query($this->_sql) ){
        return true;
      }
      return false;
    }

    public function dropTables(){
      foreach ($this->_tables as $key => $value) {
        $this->_db->query( "DROP TABLE " . $key );
      }
    }

    public function csvForm(){
      return '<div class="panel panel-default">
              <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="data_file">Upload Excel file</label>
                    <input type="file" id="data_file" name="data_file">
                  </div>
                  <button type="submit" class="btn btn-primary">Load File</button>
                </form>
              </div>
            </div>';
    }

    public function resultsHTML(){
      if( empty($this->_results) ){
        return '<div class="panel panel-default">
                <div class="panel-body">
                  <h4>All Good!</h4>
                </div>
              </div>';
      }
      $r = '<div class="panel panel-default">
              <div class="panel-body">
                <h4>Test Results</h4>';
      foreach ($this->_results as $value) {
        $r .= $value->toHtml();
      }
      $r .= '</div></div>';
      return $r;
    }

    public function runTests(){
      //override
    }

    public function statusHTML(){
      if( $this->_state == self::STATE_READY ){
        $msg = "Begin validation by uploading Excel or CSV file.";
      }

      return '<div class="well">
              <h5>' . $this->_status . '</h5>
            </div>';
    }

    public function testHTML(){
      return '<div class="panel panel-default">
              <div class="panel-body">
                <a href="?run=1" class="btn btn-primary">Run Tests</a>
              </div>
            </div>';
    }

    public function uploadFile($file){
      if( $this->_reader->readData($file) ){
        $this->_status = "File uploaded successfully.</br>";
        if( $this->createTables() ){
          $this->_status .= "Database table(s) for " . implode(",", array_keys($this->_tables)) . " created.<br>";
          $this->_state = self::STATE_UPLOAD_COMPLETE;
        }else{
          $this->_status .= "Unable to create database tables.</br>";
        }
      }else{
        $this->_status = "Unable to upload file.</br>";
      }
    }



  }


?>
