<?php
class FieldDouble extends Field
{
  public $size, $maxlength;
  protected $min_value, $max_value, $step_value;

  function __construct($name, $caption, $value = "", $is_required = false, $min_value = 1, $max_value = 1, $step_value = 0.01, $parameters = "", $help = "", $help_url = "", $maxlength = "", $size = ""){
    parent::__construct($name, "number", $caption, $value, $is_required, $parameters, $help, $help_url);
    $this->size = $size;
    $this->maxlength = $maxlength;
    $this->min_value = $min_value;
    $this->max_value = $max_value;
    $this->step_value = $step_value;
    if ($this->min_value > $this->max_value = $max_value) {
      throw new Exception("Минимальное значение должно быть больше максимального и не равное ему. Поле \"{$this->caption}\".", 1);
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
    if (!empty($this->size)) {
      $size = "size=".$this->size;
    }else {
      $size = "";
    }
    if (!empty($this->maxlength)) {
      $maxlength = "maxlength=".$this->maxlength;
    }else {
      $maxlength = "";
    }

    if ($this->max_value != 1) {
      $tag = "<input $style class='form-control $class'
        type=\"" .$this->type."\"
        name=\"".$this->name."\"
        min=\"".$this->min_value."\"
        max=\"".$this->max_value."\"
        step=\"".$this->step_value."\"
        value=\"".
        htmlspecialchars($this->value, ENT_QUOTES)."\"
        $size $maxlength>\n";
    }else {
      $tag = "<input $style class='form-control $class'
        type=\"" .$this->type."\"
        name=\"".$this->name."\"
        min=\"".$this->min_value."\"
        step=\"".$this->step_value."\"
        value=\"".
        htmlspecialchars($this->value, ENT_QUOTES)."\"
        $size $maxlength>\n";
    }
    if ($this->is_required) {
      $this->caption .= " *";
    }
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
    if ($this->is_required) {
      if (empty($this->value)) {
        return "Поле \"".$this->caption."\" не заполнено.";
      }
    }
    return "";
  }
}
?>
