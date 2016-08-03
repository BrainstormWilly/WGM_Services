<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . '/vin65/controllers/abstract_soap_controller.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/search_contacts.php';

  use wgm\models\CSV as CSVModel;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SearchContacts as SearchContactsModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;

  class SearchContacts extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_service_model = new SearchContactsModel($session);
    }

    public function getInputForm(){
      $list = $this->_service_model->getValues();
      $fe = "";
      foreach ($list as $key => $value) {
        if( $key != "Security" ){
          $fe .= '<div class="form-group">' .
                    '<label for="' . $key . '">' . $key . '</label>' .
                    '<input type="text" class="form-control" id="' . $key . '" name="' . $key . '" value="' . $value . '">' .
                  '</div>';
        }
      }
      return $fe;
    }

    public function getResultsTable(){

      $output = "";
      if( $this->_service_model->success() ){
        $results = $this->_service_model->getResult();
        print_r($results);
        if( empty($results) ){
          $output = "<strong>No Results Found</strong>";
        }else{
          foreach ($results->Contacts as $contact) {
            $output .= '<div style="margin-bottom: 20px">';
            $output .= '<div class="row"><div class="col-md-2"><strong>Contact ID: </strong></div><div class="col-md-10">' . $contact->ContactID . '</div></div>';
            $output .= '<div class="row"><div class="col-md-2"><strong>Customer Number: </strong></div><div class="col-md-10">' . $contact->CustomerNumber . '</div></div>';
            $output .= '<div class="row"><div class="col-md-2"><strong>Name: </strong></div><div class="col-md-10">' . $contact->FirstName . ' ' . $contact->LastName . '</div></div>';
            $output .= '<div class="row"><div class="col-md-2"><strong>Email: </strong></div><div class="col-md-10">' . $contact->Email . '</div></div>';
            $output .= '<div class="row"><div class="col-md-2"><strong>Address: </strong></div><div class="col-md-10">';
              if( !empty($contact->Company) ){
                $output .= $contact->Company . '</br>';
              }
              if( !empty($contact->Address) ){
                $output .= $contact->Address . '</br>';
              }
              if( !empty($contact->Address2) ){
                $output .= $contact->Address2 . '</br>';
              }
              if( !empty($contact->City) ){
                $output .= $contact->City . ', ';
              }
              if( !empty($contact->StateCode) ){
                $output .= $contact->StateCode . ' ';
              }
              if( !empty($contact->ZipCode) ){
                $output .= $contact->ZipCode . ' ';
              }
              if( !empty($contact->CountryCode) ){
                $output .= $contact->CountryCode;
              }
              $output .= '</div></div>';
            $output .= '<div class="row"><div class="col-md-2"><strong>Phone: </strong></div><div class="col-md-10">' . $contact->Phone . '</div></div>';
            $output .= '</div>';
          }
        }
      }else{
        $output = $this->_logger->toHtml();
      }

      return $output;
    }

  }

?>
