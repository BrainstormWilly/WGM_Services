<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/post_order_tracking.php";

  use wgm\vin65\controllers\PostOrderTracking as PostOrderTrackingController;

  $controller = new PostOrderTrackingController($_SESSION);

  include $_ENV['V65_INCLUDES'] . "/form_post_helper.php";
  include $_ENV['V65_INCLUDES'] . "/service_view.php";

?>
