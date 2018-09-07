<?php
abstract class Pager{
  protected function __construct(){}
  protected function get_total(){}
  protected function get_page_link(){}
  protected function get_page_number(){}
  protected function get_parameters(){}

  public function __toString(){
    $return_page = "";
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = intval($_GET['page']);
    }
    // $page = intval($_GET['page']);
    // if (empty($page)) {
    //   $page = 1;
    // }
    $number = (int)($this->get_total()/$this->get_page_number());
    if ((float)($this->get_total()/$this->get_page_number()) - $number != 0) {
      ++$number;
    }
    $return_page .= "<ul class='pagination'>";
    if ($page - $this->get_page_link() > 1) {
      $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=1{$this->get_parameters()}>1-{$this->get_page_number()}</a></li>";
      for ($i = $page - $this->get_page_link(); $i < $page; $i++) {
        $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
      }
    }else {
      for ($i = 1; $i < $page; $i++) {
        $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
      }
    }
    if ($page + $this->get_page_link() < $number) {
      for ($i = $page; $i <= $page + $this->get_page_link(); $i++) {
        if ($page == $i) {
          $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
        }else {
          $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
        }
      }
      $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$number{$this->get_parameters()}>".(($number - 1) * $this->get_page_number() + 1)."-{$this->get_total()}</a></li>";
    }else {
      for ($i = $page; $i <= $number; $i++) {
        if ($number == $i) {
          if ($page == $i) {
            $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-{$this->get_total()}</a></li>";
          }else {
            $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-{$this->get_total()}</a></li>";
          }
        }else {
          if ($page == $i) {
            $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
          }else {
            $return_page .= "<li><a href=$_SERVER[PHP_SELF]"."?page=$i{$this->get_parameters()}>".(($i - 1) * $this->get_page_number() + 1)."-".$i * $this->get_page_number()."</a></li>";
          }
        }
      }
    }
    return $return_page."</ul>";
  }
  public function print_page(){
    $return_page = "";
    if (empty($page)) {
      $page = 1;
    } else {
      $page = intval($_GET['page']);
    }
    $number = (int)($this->get_total()/$this->get_page_number());
    if ((float)($this->get_total()/$this->get_page_number()) - $number != 0) {
      ++$number;
    }
    $return_page .= "<ul class='pagination'>
      <li><a href='$_SERVER[PHP_SELF]?page=1{$this->get_parameters()}'>&lt;&lt;</a></li>";
    if ($page != 1) {
      $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=".($page - 1)."{$this->get_parameters()}'>&lt;</a></li>";
    }
    if ($page > $this->get_page_link() + 1) {
      for ($i=$page - $this->get_page_link(); $i < $page; $i++) {
        $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->get_parameters()}'>$i</a></li> ";
      }
    }else {
      for ($i=1; $i < $page; $i++) {
        $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->get_parameters()}'>$i</a></li> ";
      }
    }
    $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->get_parameters()}'>$i</a></li>";
    if ($page + $this->get_page_link() < $number) {
      for ($i=$page + 1; $i <= $page + $this->get_page_link(); $i++) {
        $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->get_parameters()}'>$i</a></li> ";
      }
    }else {
      for ($i=$page + 1; $i <= $number; $i++) {
        $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$i{$this->get_parameters()}'>$i</a></li> ";
      }
    }
    if ($page != $number) {
      $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=".($page + 1)."{$this->get_parameters()}'>&gt;</a></li>";
    }
    $return_page .= "<li><a href='$_SERVER[PHP_SELF]?page=$number{$this->get_parameters()}'>&gt;&gt;</a></li>
      </ul>";
    return $return_page;
  }
}
?>
