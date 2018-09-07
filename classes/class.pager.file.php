<?php
class PagerFile extends Pager{
  protected $filename;
  private $page_number, $page_link, $parameters;
  public function __construct($filename, $page_number = 10, $page_link = 3, $parameters = ""){
    $this->filename = $filename;
    $this->page_number = $page_number;
    $this->page_link = $page_link;
    $this->parameters = $parameters;
  }
  public function get_total(){
    $countline = 0;
    $fd = fopen($this->filename, "r");
    if ($fd) {
      while (!feof($fd)) {
        fgets($fd, 10000);
        ++$countline;
      }
      fclose($fd);
    }
    return $countline;
  }
  public function get_page_link(){
    return $this->page_link;
  }
  public function get_page_number(){
    return $this->page_number;
  }
  public function get_parameters(){
    return $this->parameters;
  }
  public function get_page(){
    $page = intval($_GET['page']);
    if (empty($page)) {
      $page = 1;
    }
    $total = $this->get_total();
    $number = (int)($total/$this->get_page_number());
    if ((float)($total/$this->get_page_number()) - $number != 0) {
      ++$number;
    }
    if ($page <= 0 || $page > $number) {
      return 0;
    }
    $arr = array();
    $fd = fopen($this->filename, "r");
    if (!$fd) {
      return 0;
    }
    $first = ($page - 1) * $this->get_page_number();
    for ($i = 0; $i < $total; $i++) {
      $str = fgets($fd, 10000);
      if ($i < $first) {
        continue;
      }
      if ($i > $first + $this->get_page_number() - 1) {
        break;
      }
      $arr[] = $str;
    }
    fclose($fd);
    return $arr;
  }
}
?>
