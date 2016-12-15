<?php namespace wgm\models;

  require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";
  require_once $_ENV['APP_ROOT'] . "/models/abstract_service_data.php";

  use \PHPExcel_IOFactory as IOFactory;

  class Excel extends AbstractServiceData implements IServiceData{

    function __construct($page_limit=25, $display_limit=50, $set_limit=1){
      $this->_page_limit = $page_limit;
      $this->_display_limit = $display_limit;
      $this->_set_limit = $set_limit;
    }

    public function readData($file){
      $limit = $this->_record_index + ($this->_page_limit * $this->_set_limit);
      $current = 0;
      $cnt = 0;
      $this->_file = $file;
      $file_parts = pathinfo($file);

      switch($file_parts['extension']){
        case 'xls' :
          $reader = IOFactory::createReader('Excel5');
          break;
        case 'xlsx' :
          $reader = IOFactory::createReader('Excel2007');
          break;
        case 'csv' :
          $reader = IOFactory::createReader('CSV');
      }

      if( isset($reader) ){
        $data = $reader->load($file);
        $records = $data->getActiveSheet()->toArray(null,true,true,true);
        $this->_record_cnt = count($records) - 1;
        $headers = array_shift($records);
        foreach ($headers as $key => $value) {
          array_push($this->_headers, $value);
        }
        foreach ($records as $assoc) {
          $rec = [];
          foreach ($assoc as $key => $value) {
            array_push($rec, $value);
          }
          $this->addRecord($rec);
        }
        return TRUE;
      }
      return FALSE;
    }



  }

?>
