<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/date_converter.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_shipping_address.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\DateConverter as DateConverter;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\GetShippingAddress as GetShippingAddressModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class GetShippingAddress extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetShippingAddress" );
      $this->_input_form = new ServiceInputForm( new GetShippingAddressModel($session) );
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
          // print_r($model->getResult());
          // exit;
          $results = $model->getResult()->shippingAddresses;
          if( count($results) > 0 ){
            $r = "<div class='panel panel-default'>" .
                    "<div class='panel-body'>";
            foreach ($results as $res) {
              $r .= "<row>" .
                  "<div class='col-sm-2'>" .
                    "<strong>ID: </strong></br>" .
                    "<strong>Alt ID: </strong></br>" .
                    "<strong>Birthdate: </strong></br>" .
                    "<strong>Firstname: </strong></br>" .
                    "<strong>Lastname: </strong></br>" .
                    "<strong>Address: </strong></br>" .
                    "<strong>Address2: </strong></br>" .
                    "<strong>City: </strong></br>" .
                    "<strong>State: </strong></br>" .
                    "<strong>Zip: </strong></br>" .
                    "<strong>Email: </strong></br>" .
                    "<strong>Phone: </strong></br>" .
                    "<strong>Nickname: </strong></br>" .
                    "<strong>Added: </strong></br>" .
                    "<strong>Primary: </strong>" .
                  "</div>" .
                  "<div class='col-sm-4'>" .
                    $res->ShippingAddressID . "</br>" .
                    $res->AltShippingAddressID . "</br>" .
                    DateConverter::toMDY($res->Birthdate) . "</br>" .
                    $res->Firstname . "</br>" .
                    $res->Lastname . "</br>" .
                    $res->Address . "</br>" .
                    $res->Address2 . "</br>" .
                    $res->City . "</br>" .
                    $res->StateCode . "</br>" .
                    $res->ZipCode . "</br>" .
                    $res->Email . "</br>" .
                    $res->MainPhone . "</br>" .
                    $res->Nickname . "</br>" .
                    DateConverter::toMDY($res->DateAdded) . "</br>";
                if( $res->IsPrimary ){
                    $r .= "True";
                }else{
                    $r .= "False";
                }

                $r .= "</div></row";
            }
            $r .= "</div></div>";
            return $r;
          }
        }
      }

      return parent::getFullResultsTable();

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
            $rec["ContactID"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==GetShippingAddressModel::METHOD_NAME ){
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
