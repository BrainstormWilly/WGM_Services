<?php namespace wgm\nexternal\models;

  require_once $_ENV['APP_ROOT'] . "/nexternal/models/abstract_xml_model.php";

  class TestSubmitRequest extends AbstractXmlModel{


    function __construct($session){
      $this->_url = "https://www.nexternal.com/shared/xml/testsubmit.rest";
      $this->_input = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" .
                        "<TestSubmitRequest>" .
                          "<Credentials>" .
                            "<AccountName>{$session['account']}</AccountName>" .
                            "<UserName>{$session['username']}</UserName>" .
                            "<Password>{$session['password']}</Password>" .
                          "</Credentials>" .
                        "</TestSubmitRequest>";
    }

  }

?>
