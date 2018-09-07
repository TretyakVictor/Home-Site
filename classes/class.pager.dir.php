<?php
class PagerDir extends Pager{
  protected $dirname;
  private $page_number, $page_link, $parameters;
  public function __construct($dirname, $page_number = 10, $page_link = 3, $parameters = ""){
    $this->dirname = trim($dirname, "/");
    $this->page_number = $page_number;
    $this->page_link = $page_link;
    $this->parameters = $parameters;
  }
  public function get_total(){
    $countline = 0;
    if (($dir = opendir($this->dirname)) !== false) {
      while (($file = readdir($dir)) !== false) {
        if (is_file($this->dirname."/".$file)) {
          ++$countline;
        }
      }
      closedir($dir);
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
    $first = ($page - 1) * $this->get_page_number();
    if (($dir = opendir($this->dirname)) === false) {
      return 0;
    }
    $i = -1;
    while (($file = readdir($dir)) !== false) {
      if (is_file($this->dirname."/".$file)) {
        ++$i;
        if ($i < $first) {
          continue;
        }
        if ($i > $first + $this->get_page_number() - 1) {
          break;
        }
        $arr[] = $this->dirname."/".$file;
      }
    }
    closedir($dir);
    return $arr;
  }
}
?>
