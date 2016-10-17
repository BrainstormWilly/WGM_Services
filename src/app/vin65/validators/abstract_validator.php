<?php namespace wgm\vin65\validators;


  abstract class AbstractValidator{

    const
      STATE_UPLOAD = 0,
      STATE_TEST = 1;

    protected $_db;
    protected $_tables = [];
    protected $_sql = "";
    protected $_state = self::STATE_UPLOAD;

    function __construct($db){
      $this->_db = $db;
    }

    public function getState(){
      return $this->_state;
    }
    public function setState($state){
      if( $state==self::STATE_TEST ){
        $this->_state = self::STATE_TEST;
      }else{
        $this->_state = self::STATE_UPLOAD;
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
      foreach ($this->_tables as $value) {
        $this->_db->query( "DROP TABLE " . $value );
      }
    }

    public function csvForm(){
      $t = '<div class="panel panel-default">
              <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="csv_file">Upload CSV file</label>
                    <input type="file" id="csv_file" name="csv_file">
                  </div>
                  <button type="submit" class="btn btn-primary">Load File</button>
                </form>
              </div>
            </div>';
      return $t;
    }

  }


?>
