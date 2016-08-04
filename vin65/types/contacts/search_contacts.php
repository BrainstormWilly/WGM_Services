<?php

require_once '../../../vendor/autoload.php';
require_once "../../../src/config/bootstrap.php";
require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_contacts.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/search_contacts.php";

  use wgm\vin65\models\SearchContacts as SearchContactsModel;
  use wgm\vin65\controllers\SearchContacts as SearchContactsController;

  $controller = new SearchContactsController( $_SESSION );

  // d9bb4fcb-ba22-45e6-81af-614c9365c1d2

  if( count($_POST) > 0 ){
    $controller->callService($_POST);
  }

 ?>

 <html>

   <header>
     <?php require_once $_ENV['APP_INCLUDES'] . "/header.php"; ?>
   </header>

   <body class="body">
     <div class="container">

       <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

       <div class="page-header">
         <h1>SearchContacts <small>for <?php echo $_SESSION['username'] ?></small></h1>
       </div>

       <div class="panel-group" id="search-group">

         <div class="panel panel-default">
           <div class="panel-heading" id='search-form-heading'>
             <h4 class="panel-title">
               <a role='button' data-toggle="collapse" data-parent='#search-group' href="#search-form-content">
                 Search Form
               </a>
             </h4>
           </div>
           <div class="panel-collapse collapse" id='search-form-content' role='tabPanel' aria-labelledby='search-form-heading'>
             <div class="panel-body">
               <form action="search_contacts.php" method="post">
                 <?php echo $controller->getInputForm(); ?>
                 <button type="submit" class="btn btn-primary">Search</button>
               </form>
             </div>
           </div>
         </div>

         <div class="panel panel-default">
           <div class="panel-heading" id='search-results-heading'>
             <h4 class="panel-title">
               <a role='button' data-toggle="collapse" data-parent='#search-group' href="#search-result-content">
                 Search Results
               </a>
             </h4>
           </div>
           <div class="panel-collapse collapse" id='search-result-content' role='tabPanel' aria-labelledby='search-results-heading'>
             <div class="panel-body">
               <?php echo $controller->getResultsTable() ?>
             </div>
           </div>
         </div>

       </div>



     </div>
   </body>

</html>
