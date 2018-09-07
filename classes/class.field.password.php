<?php
class FieldPassword extends FieldText{
  function __construct($name, $caption, $value = "", $is_required = false, $parameters = "", $help = "", $help_url = "", $maxlength = 255, $size = 41){
    parent::__construct($name, $caption, $value, $is_required, $parameters, $help, $help_url, $maxlength, $size);
    $this->type = "password";
  }
}
?>
