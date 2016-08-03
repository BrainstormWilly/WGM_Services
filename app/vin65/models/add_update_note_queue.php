<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model_queue.php';

  use Clue\React\Soap\Factory;
  use Clue\React\Soap\Proxy;
  use Clue\React\Soap\Client;
  use wgm\vin65\models\AbstractSoapModelQueue as AbstractSoapModelQueue;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;


  class AddUpdateNoteQueue extends AbstractSoapModelQueue{

    public function callService(){
      $loop = React\EventLoop\Factory::create();
      $factory = new Factory($loop);
      $factory->createClient($_ENV['V65_CONTACT_SERVICE'])->then(
        $this->_processQueue($client);
      );
    }

    protected function _processQueue($client){
      while( $this->hasNextModel() ){
        $api = new Proxy($client);
        $api->GetContact($request)->then(function($result){
          echo ("Smith Results: " . count($result->Contacts) . "</br>");
        });
      }

    }

  }

?>
