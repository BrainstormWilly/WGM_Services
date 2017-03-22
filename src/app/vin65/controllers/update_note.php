<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
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
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["keycodeid"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchNotesModel::METHOD_NAME ){
          // $this->out($model, TRUE);
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $res = $model->getResult();
            foreach ($res->Notes as $note) {
              // print_r($rec['subject'] == $value->Subject);
              // print_r("</br>");
              // print_r(mb_convert_encoding($rec['note'], "UTF-8") . " : " . $value->Note);
              // print_r("</br>");
              // print_r(strcmp(mb_convert_encoding($rec['note'], "UTF-8"), $value->Note));
              // print_r("</br>");
              // print_r(DateConverter::equals($rec['notedate'], $value->NoteDate));
              // print_r("</br>");

              // if( mb_convert_encoding($rec['subject'], "UTF-8") == $value->Subject &&
              //     DateConverter::equals($rec['notedate'], $value->NoteDate)){
              //
              //   $rec_note = preg_replace("/[^a-zA-Z0-9]+/", "", $rec["note"]);
              //   $val_note = preg_replace("/[^a-zA-Z0-9]+/", "", $value->Note);
              //
              //   $dif = strcmp($rec_note, $val_note);
              //
              //   if( $dif == 0 ){
              //     $note = $value;
              //     break;
              //   }
              //
              // }

              if( $rec['changekey'] == 'note' ){
                $rec_note = preg_replace("/[^a-zA-Z0-9]+/", "", $rec["note"]);
                $note_note = preg_replace("/[^a-zA-Z0-9]+/", "", $note->Note);
                $dif = strcmp($rec_note, $note_note);
                if( $dif == 0 ){
                    $change_note = $note;
                    break;
                }
              }elseif($rec['changekey'] == 'subject') {
                $rec_note = preg_replace("/[^a-zA-Z0-9]+/", "", $rec["subject"]);
                $note_note = preg_replace("/[^a-zA-Z0-9]+/", "", $note->Subject);
                $dif = strcmp($rec_note, $note_note);
                if( $dif == 0 ){
                    $change_note = $note;
                    break;
                }
              }

            }

            if( isset($change_note) ){
              $rec['noteid'] = $change_note->NoteID;
              $rec[ $rec['changekey'] ] = $rec['changevalue'];
              $this->_queue->processNextService($rec);
              // if( $rec['changekey'] == 'email' ||
              //     $rec['changekey'] == 'customernumber' ){
              //   $rec[ $rec['changekey'] ] = $rec['changevalue'];
              //   $this->_queue->processWithService(GetContactModel::METHOD_NAME, $rec);
              // }else{
              //   $rec[ $rec['changekey'] ] = $rec['changevalue'];
              //   $this->_queue->processNextService($rec);
              // }
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
