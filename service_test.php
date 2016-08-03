<?php


  require "./config/environment.php";

  try{
    $contact = new \SoapClient($_ENV['V65_CONTACT_SERVICE']);
    echo "Contact Service OK</br>";
  }catch(Exception $e){
    echo "Contact Service Failed</br>";
  }

  try{
    $notes = new \SoapClient($_ENV['V65_NOTE_SERVICE']);
    echo "Note Service OK</br>";
  }catch(Exception $e){
    echo "Note Service Failed</br>";
  }
  

 ?>
