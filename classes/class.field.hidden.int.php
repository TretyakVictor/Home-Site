<?php
class FieldHiddenInt extends FieldHidden{
  function check(){
    if ($this->is_required) {
      if (empty($this->value) && $this->value != 0) {
        return "Скрытое поле $this->name не заполнено $this->value.";
      }
      if (!preg_match("|^[-\d]+$|i", $this->value)) {
        return "Скрытое поле $this->name должно быть целым.";
      }
    }
    if (!preg_match("|^[-\d]+$|i", $this->value)) {
      return "Скрытое поле $this->name должно быть целым.";
    }
    return "";
  }
}
?>
