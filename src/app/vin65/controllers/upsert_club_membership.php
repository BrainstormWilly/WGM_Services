<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_club_membership.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_shipping_address.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_credit_cards.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";


  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\UpsertClubMembership as UpsertClubMembershipModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\GetShippingAddress as GetShippingAddressModel;
  use wgm\vin65\models\SearchCreditCards as SearchCreditCardsModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpsertClubMembership extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetShippingAddress" );
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchCreditCards" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpsertClubMembership" );
      $this->_input_form = new ServiceInputForm( new UpsertClubMembershipModel($session) );
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      // print_r($rec);
      // exit;
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["contactid"] = $model->getResultID();
            // print_r($rec);
            // exit;
            if( $rec["shipto"] == "ShippingAddress" ){
              $this->_queue->processNextService($rec);
            }else{
              $this->_queue->processWithService(SearchCreditCardsModel::METHOD_NAME, $rec);
            }
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==GetShippingAddressModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $res = $model->getResult();
            if( count($res->shippingAddresses)==1 ){
              $rec["shippingaddressid"] = $res->shippingAddresses[0]->ShippingAddressID;
            }elseif (count($res->shippingAddresses)>1) {
              foreach ($res->shippingAddresses as $value) {
                if( $value->IsPrimary ){
                  $rec["shippingaddressid"] = $value->ShippingAddressID;
                  break;
                }
              }
            }
            if( isset($rec["shippingaddressid"]) ){
              $this->_queue->processNextService($rec);
            }else{
              $this->_queue->recordModelError($model, "No Shipping Address found for " . $model->getValuesID());
              $this->_queue->processNextRecord();
            }
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchCreditCardsModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $res = $model->getResult();
            foreach ($res->CreditCards as $value) {
              if( $value->IsPrimary ){
                $rec["creditcardid"] = $value->CreditCardID;
                break;
              }
            }
            if( isset($rec["creditcardid"]) ){
              $this->_queue->processNextService($rec);
            }else{
              $this->_queue->recordModelError($model, "No Primary CreditCard found for " . $model->getValuesID());
              $this->_queue->processNextRecord();
            }
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==UpsertClubMembershipModel::METHOD_NAME ){
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
