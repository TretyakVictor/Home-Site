<?php
class FieldHidden extends Field{
  function __construct($name, $value = "", $is_required = false){
    parent::__construct($name, "hidden", "-", $value, $is_required, $parameters = '', "", "");
  }
  function get_html(){
    $tag = "<input type=\"".$this->type."\"
      name=\"".$this->name."\"
      value=\"".htmlspecialchars($this->value, ENT_QUOTES)."\">\n";
    return array("", $tag);
  }
  function check(){
    if (!get_magic_quotes_gpc()) {
      // $this->value = mysql_real_escape_string($this->value);
    }
    if ($this->is_required) {
      if (empty($this->value)) {
        return "Скрытое поле не заполнено.";
      }
    }
    return "";
  }
}
?>
