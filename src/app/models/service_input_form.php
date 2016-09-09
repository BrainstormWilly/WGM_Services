<?php namespace wgm\models;

  use \DateTime as DateTime;

  class ServiceInputField {

    private $_values;

    function __construct($values){
      if( $values['required'] ){
        $values['name'] = "*" . $values['name'];
      }
      $this->_values = $values;
    }

    public function getFieldHtml(){

      $d = new DateTime();
      $f =  "<div class=\"form-group\">";

      if( $this->_values['type']=='radio'){
        $f .= "<strong>" . $this->_values['name'] . "</strong>";
        for($i=0; $i<count($this->_values['choices']); $i++){
          $c = $this->_values['choices'][$i];
          $f .= "<div class='radio'>";
          $f .= "<label><input type='radio' name='{$this->_values['id']}' id='{$c['id']}' value='{$c['value']}'";
          if( $i==0 ){
            $f .= " checked>";
          }else{
            $f .= ">";
          }
          $f .= $c['name'];
          $f .= "</label></div>";
        }
      }elseif( $this->_values['type']=='textarea' ){
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<textarea rows=\"3\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" ";
        if( $this->_values['prompt']===NULL ){
          $f .= "></textarea>";
        }else{
          $f .= "placeholder=\"{$this->_values['prompt']}\"></textarea>";
        }
      }elseif( $this->_values['type']=='currency' ){
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<div class=\"input-group\">";
        $f .= "<span class=\"input-group-addon\" id=\"{$this->_values['id']}-currency-addon\">$</span>";
        $f .= "<input type=\"number\" min=\"0.01\" step=\"0.01\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" aria-describedby=\"{$this->_values['id']}-currency-addon\">";
        $f .= "</div>";
      }elseif( $this->_values['type']=='integer' ){
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<input type=\"number\" min=\"1\" step=\"1\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" >";
      }elseif( $this->_values['type']=='month' ){
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<input type=\"number\" min=\"1\" max=\"12\" step=\"1\" value=\"{$d->format('m')}\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" >";
      }elseif( $this->_values['type']=='year' ){
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<input type=\"number\" min=\"{$d->format('Y')}\" step=\"1\" value=\"{$d->format('Y')}\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" >";
      }else{
        $f .= "<label for=\"{$this->_values['id']}\">{$this->_values['name']}</label>";
        $f .= "<input type=\"{$this->_values['type']}\" class=\"form-control\" id=\"{$this->_values['id']}\" name=\"{$this->_values['id']}\" ";
        if( $this->_values['prompt']===NULL ){
          $f .= ">";
        }else{
          $f .= "placeholder=\"{$this->_values['prompt']}\">";
        }
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
        'prompt' => NULL,
        'choices' => []
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

    public function hasFields(){
      return count($this->_fields) > 0;
    }

    public function getFormHtml(){
      if( count($this->_fields)==0 ){
        $f = "<h4>No Form Available</h4>";
      }else{
        $f =  '<div class="panel panel-default">' .
                '<div class="panel-heading" id="input-heading">' .
                  '<h4 class="panel-title">' .
                    '<a role="button" data-toggle="collapse" data-parent="#choices-group" href="#input-content">' .
                      'Input Single Item' .
                    '</a>' .
                  '</h4>' .
                '</div>' .
                '<div class="panel-collapse collapse" id="input-content" role="tabPanel" aria-labelledby="input-heading">' .
                  '<div class="panel-body">';
        $f .= "<p><small>*Required Fields</small></p>";
        $f .= "<form action='{$this->_action}' method='post'>";
        foreach ($this->_fields as $value) {
          $f .= $value->getFieldHtml();
        }
        $f .= '<button type="submit" class="btn btn-primary">Submit</button></form>';
        $f .= '</div></div></div>';
      }
      return $f;
    }


  }

?>
