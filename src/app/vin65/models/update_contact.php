<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/add_contact.php';
  use wgm\vin65\models\AddContact as AddContact;

  class UpdateContact extends AddContact{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "UpsertContact";

    function __construct($session, $version=2){
      parent::__construct($session);
      $this->_value_map["lookupemail"] = "lookupemail";
    }
  }

?>
