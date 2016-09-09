<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_gift_card.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetGiftCard as GetGiftCardModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class GetGiftCard extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetGiftCard" );
      $this->_input_form = new ServiceInputForm( new GetGiftCardModel($session) );
    }

    // public function inputRecord($record){
    //   // create consumable service model for queue
    //   $input = new ServiceInputModel();
    //   $input->addRecord($record);
    //
    //   $this->_queue->setData($input);
    //   $this->_queue->init($_ENV['UPLOADS_PATH'] . '/get_order_detail.csv');
    // }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        if( $model->success() ){
          $res = $model->getResult();
          if( !empty($res) ){
            $r = "<table class='table table-condensed'>";
            $r .= "<tr>" .
                  "<th>ID</th>" .
                  "<th>Number</th>" .
                  "<th>Code</th>" .
                  "<th>Info</th>" .
                  "<th>Initial Balance</th>" .
                  "<th>Current Balance</th>" .
                  "<th>Is Active</th>" .
                  "</tr>";
            $r .= "<tr>" .
                  "<td>" . $res->GiftCard->GiftCardID . "</td>" .
                  "<td>" . $res->GiftCard->CardNumber . "</td>" .
                  "<td>" . $res->GiftCard->Code . "</td>" .
                  "<td><strong>" . $res->GiftCard->Title  . "</strong></br>" . $res->GiftCard->Notes . "</td>" .
                  "<td>$" . $res->GiftCard->InitialAmount . "</td>" .
                  "<td>$" . $res->GiftCard->CurrentBalance . "</td>" .
                  "<td>" . $res->GiftCard->IsActive . "</td>" .
                  "</tr>";
            $r .= "</table>";
            return $r;
          }
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
