<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_shipping_address.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SearchShippingAddress as SearchShippingAddressModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class SearchShippingAddress extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\SearchShippingAddress" );
      $this->_input_form = new ServiceInputForm( new SearchShippingAddressModel($session) );
    }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        $res = $model->getResult();
        if( !empty($res) ){
          $r = "";

          foreach ($res->ShippingAddresses as $value) {
            $r .= "<div class='media'>";
            $r .= "<div class='media-body'><p>" .
                  "<strong>Contact ID:</strong> " . $value->ContactID . "<br/>" .
                  "<strong>Name:</strong> " . $value->Firstname . " " . $value->Lastname . "<br/>";
            if( !empty($value->Phone) ){
                 $r .= "<strong>Phone:</strong> " . $value->Phone . "<br/>";
            }
            if( !empty($value->Email) ){
                 $r .= "<strong>Email:</strong> " . $value->Email . "<br/>";
            }
            $r .= "<strong>ShippingAddressID:</strong> " . $value->ShippingAddressID . "<br/>" .
            $r .= "<strong>Address:</strong> " . $value->Address;
            if( !empty($value->Address2) ){
              $r .= ", " . $value->Addresss;
            }
            $r .= "<br/>&nbsp;&nbsp;" . $value->City . ", " . $value->StateCode . " " . $value->ZipCode;
            $r .= "</div>";

          }
          return $r;
        }
      }
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["contactid"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==SearchShippingAddressModel::METHOD_NAME ){
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
