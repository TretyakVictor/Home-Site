<?php
class ExceptionMember extends Exception
{
  protected $key;

  public function __construct($key, $message)
  {
    $this->key = $key;
    parent::__construct($message);
  }
  public function getKey()
  {
    return $this->key;
  }
}
?>
