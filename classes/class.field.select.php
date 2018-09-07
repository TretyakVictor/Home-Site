<?php
class FieldSelect extends Field{
  protected $options, $multi, $select_size;
  function __construct($name, $caption, $value, $options = array(), $multi = false, $select_size = 4, $parameters = ""){
    parent::__construct($name, "select", $caption, $value, false, $parameters, "", "");
    $this->options = $options;
    $this->multi = $multi;
    $this->select_size = $select_size;
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
    if ($this->multi && $this->select_size) {
      $multi = "multiple size=".$this->select_size;
      $this->name = $this->name."[]";
    }else {
      $multi = "";
    }
    $tag = "<select $style class='form-control $class'
      name=\"".$this->name."\" $multi>\n";
    if (!empty($this->options)) {
      foreach ($this->options as $key => $value) {
        if (is_array($this->value)) {
          if (in_array($key, $this->value)) {
            $selected = "selected";
          }else {
            $selected = "";
          }
        }elseif ($key == trim($this->value)) {
          $selected = "selected";
        }else {
          $selected = "";
        }
        $tag .= "<option value='".htmlspecialchars($key, ENT_QUOTES)."' $selected>".htmlspecialchars($value, ENT_QUOTES)."</option>\n";
      }
    }
    $tag .= "</select>\n";
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
    if (!in_array($this->value, array_keys($this->options))) {
      if (empty($this->value)) {
        return "Поле \"".$this->caption."\" содержит не корректное значение:[$this->value].";
      }
    }
    if (!get_magic_quotes_gpc()) {
      // for ($i=0; $i < count($this->value); $i++) {
      //   $this->value[$i] = mysql_real_escape_string($this->value[$i]);
      // }
    }
    return "";
  }
  function selected(){
    return $this->value;
  }
}
?>
