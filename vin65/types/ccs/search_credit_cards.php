<?php

require_once '../../../vendor/autoload.php';
require_once "../../../src/config/bootstrap.php";
require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/search_credit_cards.php";


  use wgm\vin65\controllers\SearchCreditCards as SearchCreditCardsController;

  $controller = new SearchCreditCardsController($_SESSION);

  // if( count($_POST) > 0 ) {
  //   if( $_POST['input_type']=='cc_credentials' ){
  //     $_SESSION['CC_KEY'] = $_POST['cc_key'];
  //     $_SESSION['CC_SALT'] = $_POST['cc_salt'];
  //   }
  // }

  // if( !isset($_SESSION['CC_KEY']) ){
  //   header("Location: cc_credentials.php?service=add_update_cc");
  //   exit;
  // }

  include $_ENV['V65_INCLUDES'] . "/form_post_helper.php";
  include $_ENV['V65_INCLUDES'] . '/service_view.php';

?>
