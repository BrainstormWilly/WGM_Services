<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_contact_types.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\SearchContactTypes as SearchContactTypesModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class SearchContactTypes extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchContactTypes" );
      $this->_input_form = new ServiceInputForm( new SearchContactTypesModel($session) );
    }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        $res = $model->getResult();
        if( !empty($res) ){
          $r = "<table class='table table-condensed'>";
          $r .= "<tr>" .
                "<th>Contact ID</th>" .
                "<th>Contact Type</th>" .
                "<th>First Modified</th>" .
                "<th>Last Modified</th>" .
                "</tr>";

          foreach ($res->ContactTypes as $value) {
            $r .= "<tr>" .
                  "<td>" . $value->ContactTypeID . "</td>" .
                  "<td>" . $value->ContactType . "</td>" .
                  "<td>" . $value->DateAdded . "</td>" .
                  "<td>" . $value->DateModified . "</td>" .
                  "</tr>";
          }

          $r .= "</table>";
          return $r;
        }
      }

      return parent::getFullResultsTable();

    }




    // CALLBACKS

    // public function onSoapServiceQueueStatus($status){
    //   if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
    //     $model = $this->_queue->getCurrentServiceModel();
    //     if( $model->getClassName()==GetContactModel::METHOD_NAME ){
    //       if( $model->success() ){
    //         $rec = $this->_queue->getCurrentCsvRecord();
    //         $rec["ContactID"] = $model->getResultID();
    //         $this->_queue->processNextService($rec);
    //       }else{
    //         $this->_queue->processNextRecord();
    //       }
    //     }elseif( $model->getClassName()==UpdateOrderStatusModel::METHOD_NAME ){
    //       $this->_queue->processNextRecord();
    //     }
    //   }elseif( $status==SoapServiceQueue::QUEUE_COMPLETE ){
    //     $this->setResultsTable($this->_queue->getLog());
    //     $this->_queue->processNextPage( $this->getClassFileName() );
    //   }elseif( $status==SoapServiceQueue::FAIL ){
    //     $this->setResultsTable($this->_queue->getLog());
    //   }
    // }



  }


?>
