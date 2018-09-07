<?php
class FieldTextInt extends FieldText{
  protected $min_value, $max_value;
  function __construct($name, $caption, $value = "", $is_required = false, $min_value = 0, $max_value = 0, $parameters = "", $help = "", $help_url = "", $maxlength = 255, $size = 41){
    parent::__construct($name, $caption, $value, $is_required, $parameters, $help, $help_url);
    $this->size = $size;
    $this->min_value = $min_value;
    $this->max_value = $max_value;
    if ($this->min_value > $this->max_value = $max_value) {
      throw new Exception("Минимальное значение должно быть больше максимального и не равное ему. Поле \"{$this->caption}\".", 1);
    }
  }
  function check(){
    $pattern = "|^[-\d]*$|i";
    if ($this->is_required) {
      if ($this->min_value != $this->max_value) {
        if ($this->value < $this->min_value || $this->value > $this->max_value) {
          return "Поле \"".$this->caption."\" должно быть больше ".$this->min_value." и меньше ".$this->max_value.".";
        }
      }
      $pattern = "|^[-\d]+$|i";
    }
    if (!preg_match($pattern, $this->value)) {
      return "Поле \"".$this->caption."\" должно содержать цифры.";
    }
    return "";
  }
}
?>
