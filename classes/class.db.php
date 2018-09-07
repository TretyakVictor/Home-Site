<?php
class DB{
  protected $dbhost, $dbname, $dbuser, $dbpasswd, $charset, $dsn, $opt;

  function __construct($dbname = "system", $dbhost = "localhost", $dbuser = "root", $dbpasswd = "", $charset = "UTF8"){
    $this->dbhost = $dbhost;
    $this->dbname = $dbname;
    $this->dbuser = $dbuser;
    $this->dbpasswd = $dbpasswd;
    $this->charset = $charset;
    // $this->dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
    $this->dsn = "mysql:host=".$this->dbhost.";dbname=".$this->dbname.";charset=".$this->charset."";
    $this->opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
  }
  public function pdoGet()
  {
    return new PDO($this->dsn, $this->dbuser, $this->dbpasswd, $this->opt);
  }
}
?>
