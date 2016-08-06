<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/add_update_note.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\AddUpdateNote as AddUpdateNoteModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class AddUpdateNote extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\AddUpdateNote" );
    }

    public function getInputForm(){
      $f =  '<form action="add_update_note.php" method="post">' .
              '<div class="form-group">' .
                '<label for="NoteID">Note ID</label>' .
                '<input type="text" class="form-control" id="NoteID" name="NoteID">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="KeyCodeID">KeyCode ID</label>' .
                '<input type="text" class="form-control" id="KeyCodeID" name="KeyCodeID" placeholder="Order or Note KeyCode">' .
              '</div>' .
              '<div class="form-group">' .
                '<strong>Note is Related To</strong></br>' .
                '<label class="radio-inline"><input type="radio" name="RelatedTo" value="Contact" checked="checked">Contact</input></label>' .
                '<label class="radio-inline"><input type="radio" name="RelatedTo" value="Order">Order</input></label>' .
              '</div>' .
              '<div class="form-group">' .
                '<strong>Note Type</strong></br>' .
                '<label class="radio-inline"><input type="radio" name="Type" value="Flag">Flag</input></label>' .
                '<label class="radio-inline"><input type="radio" name="Type" value="Note" checked="checked">Note</input></label>' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="NoteDate">Date From</label>' .
                '<input type="text" class="form-control" id="NoteDate" name="NoteDate">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="Subject">Subject</label>' .
                '<input type="text" class="form-control" id="Subject" name="Subject" placeholder="Required">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="Subject">Note</label>' .
                '<textarea rows="3" class="form-control" id="Note" name="Note" placeholder="Not Required"></textarea>' .
              '</div>' .
              '<input type="hidden" id="input_type" name="input_type" value="form">' .
              '<button type="submit" class="btn btn-primary">Submit</button>' .
            '</form>';
      return $f;

    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["KeyCodeID"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==AddUpdateNoteModel::METHOD_NAME ){
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
