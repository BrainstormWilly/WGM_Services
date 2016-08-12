<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";
  require $_ENV['V65_INCLUDES'] . "/session_policy.php";

?>


<html>
  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Vin65 Web Services</br>
        <small>for <?php echo $_SESSION['username'] ?></small></h1>
      </div>


      <div class="panel-group" id="services-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='contact-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#contact-services-content">
                Contact Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='contact-services-content' role='tabPanel' aria-labelledby='contact-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='types/contacts/search_contacts.php'>SearchContacts<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='types/contacts/upsert_contact.php'>UpsertContact<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='types/contacts/upsert_shipping_address.php'>UpsertShippingAddress<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='note-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#cc-services-content">
                CreditCard Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='cc-services-content' role='tabPanel' aria-labelledby='cc-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='types/ccs/add_update_cc.php'>AddUpdateCreditCard<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='gift-card-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#gift-card-services-content">
                Gift Card Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='gift-card-services-content' role='tabPanel' aria-labelledby='gift-card-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='types/gift_cards/create_gift_card.php'>CreateGiftCard<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='types/gift_cards/update_gift_card.php'>UpdateGiftCard<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='note-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#note-services-content">
                Note Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='note-services-content' role='tabPanel' aria-labelledby='note-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='types/notes/search_notes.php'>SearchNotes<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='#'>GetNote<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='types/notes/add_update_note.php'>AddUpdateNote<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='order-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#order-services-content">
                Order Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='order-services-content' role='tabPanel' aria-labelledby='order-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='types/orders/upsert_order.php'>UpsertOrder<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
                <a class="list-group-item" href='types/orders/update_order_status.php'>Update Order Status Only<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


</html>