<?php
class FieldTextEmail extends FieldText{
  function check(){
    if ($this->is_required || !empty($this->value)) {
      $pattern = "#^[-0-9а-z_\.]+@[-0-9а-z^\.]+\.[а-z]{2,б}$#i";
      if (!preg_match($pattern, $this->value)) {
        return "Введите e-mail в виде 'nameMail@server.zone'";
      }
    }
    return "";
  }
}
?>
