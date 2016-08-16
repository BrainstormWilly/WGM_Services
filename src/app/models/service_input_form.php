<?php namespace wgm\models;

  class ServiceInputField {

    private $_values;

    function __construct($values){
      if( $values['required'] ){
        $values['name'] = "*" . $values['name'];
      }
      $this->_values = $values;
    }

    public function getFieldHtml(){
      $f =  "<div class=\"form-group\">" .
                "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>" .
                "<input type=\"{$this->_values['type']}\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" ";
      if( $this->_values['prompt']===NULL ){
        $f .= ">";
      }else{
        $f .= "placeholder=\"{$this->_values['prompt']}\">";
      }

      return $f . "</div>";
    }

  }


  class ServiceInputForm{

    public static function FieldValues() {
      return [
        'id' => '',
        'name' => '',
        'type' => 'text',
        'required' => TRUE,
        'prompt' => NULL
      ];
    }

    private $_action;
    private $_fields = [];

    function __construct($model){
      $this->_action = $model->getClassFileName() . ".php";
      $fields = $model->getValueFields();
      foreach ($fields as $value) {
        $this->addField($value);
      }
    }

    public function addField($value){
      array_push( $this->_fields, new ServiceInputField($value) );
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
