<?php
// require_once("classes/class.db.php");
// require_once 'configs\mysql.config.php';
class PagerMySQL extends Pager{
  public $pdo;
  protected $tablename, $where, $order;
  private $page_number, $page_link, $parameters;
  public function __construct($pdo, $tablename, $where = "", $order = "", $page_number = 10, $page_link = 3, $parameters = ""){
    $this->tablename = $tablename;
    $this->where = $where;
    $this->order = $order;
    $this->page_number = $page_number;
    $this->page_link = $page_link;
    $this->parameters = $parameters;
    // $connect = new DB();
    // $pdo = $connect->get_pdo();
    $this->pdo = $pdo;


  }
  public function get_total(){

    // $connect = new DB();
    // $pdo = $connect->get_pdo();
    // require 'configs\mysql.config.php';
    // $query = "SELECT COUNT(*) FROM {$this->tablename} {$this->where} {$this->order}";
    // $query = $pdo->prepare("SELECT COUNT(*) FROM '".$this->tablename."' :where :order");
    // if ($this->where != "") {
    //   $where = "WHERE";
    // }else {
    //   $where = "";
    // }
    // if ($this->order != "") {
    //   $orderby = "ORDER BY";
    // }else {
    //   $orderby = "";
    // }
    // $query = $pdo->prepare("SELECT COUNT(*) FROM ".$this->tablename."   ORDER BY :order");
    $query = "SELECT COUNT(*) FROM {$this->tablename} {$this->where} {$this->order}";
    $stmt = $this->pdo->query($query);
    // $query->execute(array('tablename' => $this->tablename, 'where' => $this->where, 'order' => $this->order));
    // $query->execute(array(':where'=>$this->where, ':order'=>$this->order));
    // $query->execute(array('order'=>$this->order));
    $result = $stmt->fetch(PDO::FETCH_LAZY);
    if (!$result) {
      throw new ExceptionMySQL(mysql_error(), $query, "Ошибка подсчета количества позиций.");
    }
    return $result[0];
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
    // $connect = new DB();
    // $pdo = $connect->get_pdo();


    if (empty($page)) {
      $page = 1;
    }else {
      $page = intval($_GET['page']);
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
    // $query = $pdo->prepare"SELECT * FROM {$this->tablename} {$this->where} {$this->order} LIMIT $first, {$this->get_page_number()}";
    // $query = $pdo->prepare("SELECT * FROM '".$this->tablename."' :where :order LIMIT :first, :pnumber");
    // $query->bindValues(':first', (int) $first, PDO::PARAM_INT);
    // $query->bindValues(':pnumber', (int) $this->get_page_number(), PDO::PARAM_INT);
    // $tbl = $query->execute(array('where' => $this->where, 'order' => $this->order));
    $query = "SELECT * FROM {$this->tablename} {$this->where} {$this->order} LIMIT $first, {$this->get_page_number()}";
    $tbl = $this->pdo->query($query);
    // if (!$tbl) {
    //   throw new ExceptionMySQL(mysql_error(), $query, "Ошибка обращения к таблице позиций.");
    // }
    if ($tbl->rowCount()) {
      while ($arr[] = $tbl->fetch());
    }
    unset($arr[count($arr) - 1]);
    return $arr;
  }
}
?>
