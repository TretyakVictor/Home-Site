<?php
class FieldCheckbox extends Field{
  function __construct($name, $caption, $value = false, $parameters = "", $help = "", $help_url = ""){
    parent::__construct($name, "checkbox", $caption, $value, false, $parameters, $help, $help_url);
    if ($value == "on") {
      $this->value = true;
    }elseif ($value === true) {
      $this->value = true;
    }else {
      $this->value = false;
    }
  }

  function get_html(){
    if (!empty($this->css_style)) {
      $style = "style=\"".$this->css_style."\"";
    }else {
      $style = "";
    }
    if (!empty($this->css_class)) {
      $class = "class=\"".$this->css_class."\"";
    }else {
      $class = "";
    }
    if ($this->value) {
      $checked = "checked";
    }else {
      $checked = "";
    }
    $tag = "<input $style class='form-control $class'
      type=\"".$this->type."\"
      name=\"".$this->name."\" $checked>\n";
    $help = "";
    if (!empty($this->help)) {
      $help .= "<span style = 'color:blue'>".nl2br($this->help)."</span>";
    }
    if (!empty($help)) {
      $help .= "<br>";
    }
    if (!empty($this->help_url)) {
      $help .= "<span style = 'color:blue'><a href=".$this->help_url.">Помощь</a></span>";
    }
    return array($this->caption, $tag, $help);
  }
  function check(){
    return "";
  }
}
?>
