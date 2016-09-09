<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  // require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/get_gift_card.php";

  // use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\controllers\GetGiftCard as GetGiftCardController;


  $controller = new GetGiftCardController( $_SESSION );

  include $_ENV['V65_INCLUDES'] . "/form_post_helper.php";
  include $_ENV['V65_INCLUDES'] . "/service_view.php";

?>
