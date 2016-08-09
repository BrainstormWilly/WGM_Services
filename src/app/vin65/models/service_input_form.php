<?php namespace wgm\vin65\models;

  class ServiceInputField {

    private $_id;
    private $_name;
    private $_type;
    private $_prompt;

    function __construct($id, $name, $type, $prompt=NULL){
      $this->_id = $id;
      $this->_name = $name;
      $this->_type = $type;
      $this->_prompt = $prompt;
    }

    public function getFieldHtml(){
      $f =  "<div class=\"form-group\">" .
                "<label for=\"{$this->_id}\">{$this->_name}</label>" .
                "<input type=\"{$this->_type}\" class=\"form-control\" id=\"{$this->_id}\" name=\"{$this->_id}\" ";
      if( $this->_prompt===NULL ){
        $f .= ">";
      }else{
        $f .= "placeholder=\"{$this->_prompt}\">";
      }

      return $f . "</div>";
    }

  }


  class ServiceInputForm{

    private $_action;
    private $_fields = [];

    function __construct($model){
      $this->_action = $model->getClassFileName() . ".php";
      $fields = $model->getValueFields();
      foreach ($fields as $value) {
        $this->addField($value[0], $value[1], $value[2], $value[3]);
      }

    }

    public function addField($id, $name, $type, $prompt=NULL){
      array_push( $this->_fields, new ServiceInputField($id, $name, $type, $prompt) );
    }

    public function getFormHtml(){
      if( count($this->_fields)==0 ){
        $f = "<strong>No Form Available</strong>";
      }else{
        $f = "<form action='{$this->_action}' method='post'>";
        foreach ($this->_fields as $value) {
          $f .= $value->getFieldHtml();
        }
        $f .= '<button type="submit" class="btn btn-primary">Submit</button>';
        $f .= '</form>';

      }
      return $f;
    }


  }

?>
