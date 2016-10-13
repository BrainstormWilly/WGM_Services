<?php namespace wgm\vin65\validators;


  abstract class AbstractValidator{

    protected $_db;
    protected $_tables = [];
    protected $_sql = "";

    function __construct($db){
      $this->_db = $db;
    }

    public function createTables(){
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
      
    }

  }


?>
