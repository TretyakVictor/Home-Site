<?php
class FieldTextarea extends Field{
  protected $cols, $rows, $wrap, $disabled, $readonly;
  function __construct($name, $caption, $value = "", $is_required = false, $cols = 35, $rows = 7, $wrap = false, $disabled = false, $readonly = false, $parameters = "", $help = "", $help_url = ""){
    parent::__construct($name, "textarea", $caption, $value, $is_required, $parameters, $help, $help_url);
    $this->cols = $cols;
    $this->rows = $rows;
    $this->wrap = $wrap;
    $this->disabled = $disabled;
    $this->readonly = $readonly;
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
    if (!empty($this->cols)) {
      $cols = "cols=".$this->cols;
    }else {
      $cols = "";
    }
    if (!empty($this->rows)) {
      $rows = "rows=".$this->rows;
    }else {
      $rows = "";
    }
    if ($this->disabled) {
      $disabled = "disabled";
    }else {
      $disabled = "";
    }
    if ($this->readonly) {
      $readonly = "readonly";
    }else {
      $readonly = "";
    }
    if ($this->wrap) {
      $wrap = "wrap";
    }else {
      $wrap = "";
    }

    if (is_array($this->value)) {
      $this->value = implode("/r/n", $this->value);
    }
    if (!get_magic_quotes_gpc()) {
      $output = str_replace('/r/n', "/r/n", $this->value);
    }else {
      $output = $this->value;
    }
    $tag = "<textarea $style class='form-control $class'
      name=\"".$this->name."\"
      $cols, $rows, $wrap, $disabled, $readonly>".
      htmlspecialchars($output, ENT_QUOTES).
      "</textarea>\n";
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
    if (!get_magic_quotes_gpc()) {
      // $this->value = mysql_real_escape_string($this->value);
    }
    if ($this->is_required) {
      if (empty($this->value)) {
        return "Поле \"".$this->caption."\" не заполнено.";
      }
    }
    return "";
  }
}
?>
