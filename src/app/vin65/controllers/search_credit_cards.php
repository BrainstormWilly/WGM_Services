<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_credit_cards.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SearchCreditCards as SearchCreditCardsModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class SearchCreditCards extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchCreditCards" );
      $this->_input_form = new ServiceInputForm( new SearchCreditCardsModel($session) );
    }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        $res = $model->getResult();
        if( !empty($res) ){
          $r = "<table class='table table-condensed'>";
          $r .= "<tr>" .
                "<th>CreditCard ID</th>" .
                "<th>Masked Card Number</th>" .
                "<th>Expiry Month</th>" .
                "<th>Expiry Year</th>" .
                "</tr>";
          foreach ($res->CreditCards as $value) {
            $r .= "<tr>" .
                  "<td>" . $value->CreditCardID . "</td>" .
                  "<td>" . $value->MaskedCardNumber . "</td>" .
                  "<td>" . $value->CardExpiryMonth . "</td>" .
                  "<td>" . $value->CardExpiryYear . "</td>" .
                  "</tr>";
          }

          $r .= "</table>";
          return $r;
        }
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
            $rec["ContactID"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchCreditCardsModel::METHOD_NAME ){
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
