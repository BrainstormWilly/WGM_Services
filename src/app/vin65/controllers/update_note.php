<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/add_update_note.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/update_note.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_notes.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/date_converter.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";


  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\AddUpdateNote as AddUpdateNoteModel;
  use wgm\vin65\models\UpdateNote as UpdateNoteModel;
  use wgm\vin65\models\SearchNotes as SearchNotesModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\DateConverter as DateConverter;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpdateNote extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchNotes" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpdateNote" );
      $this->_input_form = new ServiceInputForm( new UpdateNoteModel($session) );
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          // print_r($rec);
          // print_r("</br>");
          // print_r($model->getResult);
          // exit;
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            if( !isset($rec["keycodeid"]) ){
              $rec["keycodeid"] = $model->getResultID();
              $this->_queue->processNextService($rec);
            }else{ // when the change is on the contact
              // print_r(UpdateNoteModel::METHOD_NAME."</br>");
              $rec["keycodeid"] = $model->getResultID();
              $this->_queue->processWithService(UpdateNoteModel::METHOD_NAME, $rec);
            }
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchNotesModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $res = $model->getResult();
            $note_dif = 10000;
            foreach ($res->Notes as $value) {
              // print_r($rec['subject'] == $value->Subject);
              // print_r("</br>");
              // print_r(mb_convert_encoding($rec['note'], "UTF-8") . " : " . $value->Note);
              // print_r("</br>");
              // print_r(strcmp(mb_convert_encoding($rec['note'], "UTF-8"), $value->Note));
              // print_r("</br>");
              // print_r(DateConverter::equals($rec['notedate'], $value->NoteDate));
              // print_r("</br>");

              if( mb_convert_encoding($rec['subject'], "UTF-8") == $value->Subject &&
                  DateConverter::equals($rec['notedate'], $value->NoteDate)){

                $rec_note = preg_replace("/[^a-zA-Z0-9]+/", "", $rec["note"]);
                $val_note = preg_replace("/[^a-zA-Z0-9]+/", "", $value->Note);

                // compare as UTF-8
                $dif = strcmp($rec_note, $val_note);

                // exact match
                if( $dif == 0 ){
                  $note = $value;
                  break;
                // closest match (remove after Castoro)
                }
                // else if( abs($dif) < abs($note_dif) ){
                //   $note_dif = $dif;
                //   $note = $value;
                // }

              }
            }
            // print_r($note);
            //exit;
            if( isset($note) ){
              $rec['noteid'] = $note->NoteID;
              if( $rec['changekey'] == 'email' ||
                  $rec['changekey'] == 'customernumber' ){
                $rec[ $rec['changekey'] ] = $rec['changevalue'];
                $this->_queue->processWithService(GetContactModel::METHOD_NAME, $rec);
              }else{
                $rec[ $rec['changekey'] ] = $rec['changevalue'];
                $this->_queue->processNextService($rec);
              }
            }else{
              $this->_queue->recordModelError($model, "Unable to find Note");
              $this->_queue->processNextRecord();
            }

          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()=="UpdateNote" ){
          // print_r("run next record</br>");
          $this->_queue->processNextRecord();
        }else{
          print_r($model->getClassName() . " : " . UpdateNoteModel::METHOD_NAME );
          print_r("</br>");
          print_r("run amok on " . $model->getClassName());
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
