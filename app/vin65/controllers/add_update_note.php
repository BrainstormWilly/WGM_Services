<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/add_update_note.php";

  use wgm\models\CSV as CSVModel;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\AddUpdateNote as AddUpdateNoteModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;
  use React\EventLoop\Factory as EventLoopFactory;
  use Clue\React\Soap\Factory as SoapFactory;
  use Clue\React\Soap\Proxy;
  use Clue\React\Soap\Client;

  class AddUpdateNote extends AbstractSoapController{

    private $_contact_proxy;
    private $_note_proxy;

    private function _processRecord(){
      $csv_record = $this->_csv_model->getNextRecord();
      if( !$csv_record ){
        $this->_logger->closeLog();
        $log = $this->_logger->getLog();
        if( $this->_csv_model->hasNextPage() ){
          $t = "<h4>Service In-Process: " . $this->_csv_model->getRecordIndex() . " of " . $this->_csv_model->getRecordCnt() . " records processed.</h4>";
        }else{
          $t = "<h4>Service Complete: " . $this->_csv_model->getRecordCnt() . " records processed.</h4>";
        }

        foreach($log as $rec){
          $t .= $rec->toHtml();
        }

        $this->setResultsTable($t);

        if( $this->_csv_model->hasNextPage() ){
          header("Refresh:1; url=add_update_note_file.php?file=" . $this->_csv_model->getFileName() . "&index=" . strval($this->_csv_model->getRecordIndex()));
        }

      }else{
        $contact = new GetContactModel($this->_session);
        $contact->setValues($csv_record);
        $this->_contact_proxy->GetContact($contact->getValues())->then(
          function($gc_result) use ($contact, $csv_record){
            if( $gc_result->isSuccessful){
              if( count($gc_result->contacts) > 0 ){
                $contact->setResult($gc_result);
                $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerID() , 'ContactServices->GetContact', $contact->getContact()->ContactID ));
                $csv_record['KeyCodeID'] = $contact->GetContact()->ContactID;
                $note = new AddUpdateNoteModel($this->_session);
                $note->setValues($csv_record);
                $this->_note_proxy->AddUpdateNote($note->getValues())->then(
                  function($aun_result) use ($note, $contact){
                    $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerID() , 'NotesServices->AddUpdateNote', $note->getNoteSubject()));
                    $this->_processRecord();
                  },
                  function($excp) use ($contact){
                    $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerID() , 'NotesServices->AddUpdateNote', $excp->getMessage()));
                    $this->_processRecord();
                  }
                );
              }else{
                $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerID() , 'ContactServices->GetContact', "Unable to find contact in system."));
              }
            }else{
              $gc_err = "";
              foreach($gc_result->Errors as $value){
                $gc_err .= $value["ErrorCode"] . ": " . $value["ErrorMessage"] . "; ";
              }
              $contact->setError($gc_err);
              $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerID() , 'ContactServices->GetContact', $contact->getError()));
              $this->_processRecord();
            }


          },
          function($excp) use ($contact){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerID() , 'ContactServices->GetContact', $excp->getMessage()));
            $this->_processRecord();
          }
        );
      }
    }

    public function queueRecords($file, $index=0){
      parent::queueRecords($file, $index);

      if( $this->_csv_model->readFile($file) ){
        $this->_logger->openLog($this->_csv_model->getFile(), $index);

        $loop = EventLoopFactory::create();
        $soap = new SoapFactory($loop);

        $soap->createClient($_ENV['V65_V2_CONTACT_SERVICE'])->then(
          function($contact_client) use ($soap){
            $this->_contact_proxy = new Proxy($contact_client);
            $soap->createClient($_ENV['V65_NOTE_SERVICE'])->then(
              function($note_client) use ($soap){
                $this->_note_proxy = new Proxy($note_client);
                $this->_processRecord();
              },
              function($excp){
                $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, "0000" , 'NotesServices->createClient', $excp->getMessage()));
              }

            );
          },
          function($excp){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, "0000" , 'ContactServices->createClient', $excp->getMessage()));
          }
        );

        $loop->run();
      }else{
        $this->_logger->writeToLog( ServiceLogger::createFailItem(1, '0000' , 'CSV Reader', 'Unable to read file.'));
      }
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

    public function getCsvTable(){
      if( $this->_csv_model->getFieldCnt() == 0 ){
        return '';
      }
      $rs = $this->_csv_model->getRecords($this->_csv_model->getCurrentPage());
      $hs = $this->_csv_model->getHeaders();
      $t = '<strong>File Results</strong></br>';
      $t .= '<div class="table-responsive">';
      $t .= '<table class="table table-bordered table-condensed"><tr>';
      foreach ($hs as $value) {
        $t .= '<th>' . $value . '</th>';
      }
      $t .= '</tr>';
      foreach ($rs as $r) {
        $t .= '<tr>';
        foreach($r as $key => $c){
          $t .= '<td>' . $c . '</td>';
        }
        $t .= '</tr>';
      }
      $t .= '</table></div>';

      return $t;
    }

    // public function getResultsTable(){
    //   $t = '<h4>Processed ' . $this->_csv_model->getRecordIndex() . ' of ' . $this->_csv_model->getRecordCnt() . ' Notes.</br>';
    //   $log = $this->_logger->getLog('fail');
    //   $log_cnt = count($log);
    //   $t .= "<small> with $log_cnt Errors</small></h4>";
    //   if( $log_cnt > 0 ){
    //     foreach($log as $value){
    //       $t .= $value->toHtml();
    //     }
    //   }
    //   return $t;
    // }

  }

  ?>
