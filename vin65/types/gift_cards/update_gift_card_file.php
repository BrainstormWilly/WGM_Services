<?php
require_once '../../../vendor/autoload.php';
require_once "../../../src/config/bootstrap.php";
require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/update_gift_card.php";

  use wgm\vin65\controllers\UpdateGiftCard as UpdateGiftCardController;
  $controller = new UpdateGiftCardController( $_SESSION );

  require $_ENV['V65_INCLUDES'] . "/csv_post_helper.php";

  include $_ENV['V65_INCLUDES'] . "/service_file_view.php";

?>
