<?php namespace wgm\vin65\controllers;

  class SearchNotes{

    private $_model;

    function __construct($model){
      $this->_model = $model;
    }

    public function getInputForm(){
      $f =  '<form action="search_notes.php" method="post">' .
              '<div class="form-group">' .
                '<label for="NoteID">Note ID</label>' .
                '<input type="text" class="form-control" id="NoteID" name="NoteID">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="KeyCodeID">KeyCode ID</label>' .
                '<input type="text" class="form-control" id="KeyCodeID" name="KeyCodeID" placeholder="Order or Note KeyCode">' .
              '</div>' .
              '<div class="form-group">' .
                '<strong>Note is Related To</strong></br>' .
                '<label class="radio-inline"><input type="radio" name="RelatedTo" value="Contact" checked="checked">Contact</input></label>' .
                '<label class="radio-inline"><input type="radio" name="RelatedTo" value="Order">Order</input></label>' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="DateModifiedFrom">Date From</label>' .
                '<input type="text" class="form-control" id="DateModifiedFrom" name="DateModifiedFrom">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="DateModifiedTo">Date To</label>' .
                '<input type="text" class="form-control" id="DateModifiedTo" name="DateModifiedTo">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="MaxRows">Max Rows</label>' .
                '<input type="tel" class="form-control" id="MaxRows" name="MaxRows" value="'. $this->_model->getValues()["MaxRows"] . '">' .
              '</div>' .
              '<div class="form-group">' .
                '<label for="Page">Page</label>' .
                '<input type="tel" class="form-control" id="Page" name="Page" value="'. $this->_model->getValues()["Page"] . '">' .
              '</div>' .
              '<button type="submit" class="btn btn-primary">Submit</button>' .
            '</form>';
      return $f;
    }

    public function getResultsTable(){
      $results = $this->_model->getResults();
      $output = "";
      if( empty($results) ){
        $output = "<strong>Waiting for service..</strong>";
      }elseif( count($results->Errors) ){
        return print_r($results->Errors);
      }elseif( $results->IsSuccessful && count($results->Notes) > 0 ){

        foreach ($results->Notes as $note) {
          $output .= '<div style="margin-bottom: 20px">';
          $output .= '<div class="row"><div class="col-md-2"><strong>Note ID: </strong></div><div class="col-md-10">' . $note->NoteID . '</div></div>';
          $output .= '<div class="row"><div class="col-md-2"><strong>Type: </strong></div><div class="col-md-10">' . $note->Type . '</div></div>';
          $output .= '<div class="row"><div class="col-md-2"><strong>Related To: </strong></div><div class="col-md-10">' . $note->RelatedTo  . '</div></div>';
          $output .= '<div class="row"><div class="col-md-2"><strong>KeyCode ID: </strong></div><div class="col-md-10">' . $note->KeyCodeID . '</div></div>';
          $output .= '<div class="row"><div class="col-md-2"><strong>Subject: </strong></div><div class="col-md-10">' . $note->Subject . '</div></div>';
          $output .= '<div class="row"><div class="col-md-2"><strong>Note: </strong></div><div class="col-md-10">' . $note->Note . '</div></div>';
          $output .= '</div>';
        }

      }else{
        return print_r($results);
        // $output = "<strong>No Results Found</strong>";
      }

      return $output;
    }

  }

?>
