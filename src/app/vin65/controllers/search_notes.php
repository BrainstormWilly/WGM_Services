<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_notes.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SearchNotes as SearchNotesModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class SearchNotes extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchNotes" );
      $this->_input_form = new ServiceInputForm( new SearchNotesModel($session) );
    }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        $res = $model->getResult();
        $r = "";
        if( !empty($res) ){
          foreach ($res->Notes as $value) {
            $r .= '<div class="media">';
            $r .= '<div class="media-body">';
            $r .= '<h4 class="media-heading">Note ID: ' . $value->NoteID . '</h4>';
            $r .= '<p><strong>Subject:</strong> ' . $value->Subject . '</p>';
            $r .= '<p><strong>Related To:</strong> ' . $value->RelatedTo . '</p>';
            $r .= '<p><strong>Note:</strong>' . $value->Note . '</p>';
            $r .= '</div></div>';
          }
        }
        return $r;
      }

      return parent::getFullResultsTable();

    }




    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["keycodeid"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchNotesModel::METHOD_NAME ){
          $this->_queue->processNextRecord();
        }
      }elseif( $status==SoapServiceQueue::QUEUE_COMPLETE ){
        $this->setResultsTable($this->_queue->getLog());
        $this->_queue->processNextPage( $this->getClassFileName() );
      }elseif( $status==SoapServiceQueue::FAIL ){
        $this->setResultsTable($this->_queue->getLog());
      }
    }



  }


?>
