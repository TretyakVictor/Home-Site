<?php
class FieldEmail extends Field
{
  public $size, $maxlength;

  function __construct($name, $caption, $value = "", $is_required = false, $parameters = "", $help = "", $help_url = "", $maxlength = "", $size = ""){
    parent::__construct($name, "email", $caption, $value, $is_required, $parameters, $help, $help_url);
    $this->size = $size;
    $this->maxlength = $maxlength;
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

    $tag = "<input $style class='form-control $class'
      type=\"" .$this->type."\"
      name=\"".$this->name."\"
      value=\"".
      htmlspecialchars($this->value, ENT_QUOTES)."\"
      $size $maxlength>\n";
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
    if ($this->is_required && empty($this->value)) {
      return "Поле для ввода e-mail не должно быть пустым.";
    }
    // if ($this->is_required || !empty($this->value)) {
    //   $pattern = "#^[-0-9а-z_\.]+@[-0-9а-z^\.]+\.[а-z]{2,б}$#i";
    //   if (!preg_match($pattern, $this->value)) {
    //     return "Введите e-mail в виде 'nameMail@server.zone'";
    //   }
    // }
    return "";
  }
}
?>
